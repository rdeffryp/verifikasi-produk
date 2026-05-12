<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Carbon\Carbon;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display dashboard
     */
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_produk', 'LIKE', "%{$search}%")
                  ->orWhere('batch_number', 'LIKE', "%{$search}%");
            });
        }
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            if ($status === 'KADALUARSA') {
                $query->where('tanggal_kadaluarsa', '<', now());
            } elseif ($status === 'DUPLIKASI') {
                $query->where('scan_count', '>', 5);
            } elseif ($status === 'ASLI') {
                $query->where('scan_count', '>', 0)
                      ->where('scan_count', '<=', 5)
                      ->where('tanggal_kadaluarsa', '>=', now());
            } elseif ($status === 'BELUM_DISCAN') {
                $query->where('scan_count', 0);
            }
        }
        
        $sort = $request->get('sort', 'newest');
        
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'scan_count') {
            $query->orderBy('scan_count', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(10);
        
        $stats = [
            'total_products' => Product::count(),
            'verified_products' => Product::verified()->count(),
            'unverified_products' => Product::unverified()->count(),
            'expired_products' => Product::expired()->count(),
        ];
        
        return view('products.index', compact('products', 'stats'));
    }

    /**
     * Show form tambah produk (single)
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Show form bulk create (tambah banyak sekaligus)
     */
    public function bulkCreate()
{
    $labelTemplates = \DB::table('label_templates')->where('is_active', true)->get();
    return view('products.bulk-create', compact('labelTemplates'));
}

    /**
     * Generate QR Code + Digital Signature (single product)
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
    'nama_produk' => 'required|string|max:100',
    'tanggal_produksi' => 'required|date',
    'tanggal_kadaluarsa' => 'required|date|after:tanggal_produksi',
    'quantity' => 'required|integer|min:1|max:100',
    'batch_prefix' => 'nullable|string|max:20',
    'label_template_id' => 'nullable|integer|exists:label_templates,id',
]);

        $productId = Product::generateProductId();

        $dataToHash = $productId . 
                      $validated['nama_produk'] . 
                      $validated['tanggal_produksi'] . 
                      $validated['tanggal_kadaluarsa'];

        $hashData = hash('sha256', $dataToHash);

        $privateKey = env('PRIVATE_KEY');
        $signature = $this->generateSignature($hashData, $privateKey);

        if (!$signature) {
            return back()->withErrors(['error' => 'Gagal generate signature. Cek PRIVATE_KEY di .env']);
        }

       $product = Product::create([
    'product_id' => $productId,
    'nama_produk' => $validated['nama_produk'],
    'tanggal_produksi' => $validated['tanggal_produksi'],
    'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'],
    'batch_number' => $batchNumber,
    'hash_data' => $hashData,
    'signature' => $signature,
    'is_verified' => false,
    'scan_count' => 0,
    'label_template_id' => $validated['label_template_id'] ?? null,
]);

        $qrCodePath = $this->generateQrCode($product);
        
        if (!$qrCodePath) {
            return back()->withErrors(['error' => 'Produk tersimpan, tapi QR Code gagal dibuat. Cek log error.']);
        }
        
        $product->update(['qr_code_path' => $qrCodePath]);

        return redirect()->route('products.show', $product->id)
                         ->with('success', 'Produk berhasil dibuat! QR Code sudah di-generate.');
    }

    /**
     * Store bulk products (banyak sekaligus)
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
    'nama_produk' => 'required|string|max:100',
    'tanggal_produksi' => 'required|date',
    'tanggal_kadaluarsa' => 'required|date|after:tanggal_produksi',
    'quantity' => 'required|integer|min:1|max:100',
    'batch_prefix' => 'nullable|string|max:20',
    'label_template_id' => 'nullable|integer|exists:label_templates,id',
]);

        $quantity = $validated['quantity'];
        $batchPrefix = $validated['batch_prefix'] ?? 'BATCH';
        $date = now()->format('Ymd');
        $masterBatch = "{$batchPrefix}-{$date}";
        
        $createdProducts = [];
        $failedCount = 0;

        for ($i = 1; $i <= $quantity; $i++) {
            try {
                $productId = Product::generateProductId();
                $batchNumber = "{$masterBatch}-" . str_pad($i, 3, '0', STR_PAD_LEFT);

                $dataToHash = $productId . 
                              $validated['nama_produk'] . 
                              $validated['tanggal_produksi'] . 
                              $validated['tanggal_kadaluarsa'] .
                              $batchNumber;

                $hashData = hash('sha256', $dataToHash);

                $privateKey = env('PRIVATE_KEY');
                $signature = $this->generateSignature($hashData, $privateKey);

                if (!$signature) {
                    $failedCount++;
                    continue;
                }

                $product = Product::create([
    'product_id' => $productId,
    'nama_produk' => $validated['nama_produk'],
    'tanggal_produksi' => $validated['tanggal_produksi'],
    'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'],
    'batch_number' => $batchNumber,
    'hash_data' => $hashData,
    'signature' => $signature,
    'is_verified' => false,
    'scan_count' => 0,
    'label_template_id' => $validated['label_template_id'] ?? null,
]);

                $qrCodePath = $this->generateQrCode($product);
                
                if ($qrCodePath) {
                    $product->update(['qr_code_path' => $qrCodePath]);
                    $createdProducts[] = $product;
                } else {
                    $failedCount++;
                }

            } catch (\Exception $e) {
                $failedCount++;
                \Log::error("Bulk create error for item {$i}: " . $e->getMessage());
            }
        }

        $successCount = count($createdProducts);
        
        if ($successCount > 0) {
            $message = "Berhasil membuat {$successCount} produk!";
            if ($failedCount > 0) {
                $message .= " ({$failedCount} gagal)";
            }
            
            return redirect()->route('products.index')
                           ->with('success', $message)
                           ->with('bulk_batch', $masterBatch);
        } else {
            return back()->withErrors(['error' => 'Gagal membuat produk. Silakan coba lagi.']);
        }
    }

    /**
     * Show detail produk
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show semua produk dalam 1 batch
     */
    public function showBatch($batchNumber)
    {
        $products = Product::where(function($query) use ($batchNumber) {
                $query->where('batch_number', 'LIKE', $batchNumber . '-%')
                      ->orWhere('batch_number', '=', $batchNumber);
            })
            ->orderBy('batch_number')
            ->get();

        if ($products->isEmpty()) {
            return redirect()->route('products.index')
                           ->withErrors(['error' => 'Batch tidak ditemukan']);
        }

        $firstProduct = $products->first();
        
        $batchInfo = [
            'batch_number' => $batchNumber,
            'nama_produk' => $firstProduct->nama_produk,
            'tanggal_produksi' => $firstProduct->tanggal_produksi,
            'tanggal_kadaluarsa' => $firstProduct->tanggal_kadaluarsa,
            'total_products' => $products->count(),
            'verified_count' => $products->where('is_verified', true)->count(),
            'total_scans' => $products->sum('scan_count'),
        ];

        return view('products.batch', compact('products', 'batchInfo', 'batchNumber'));
    }

    /**
     * Print semua QR dalam 1 batch
     */
    public function printBatch($batchNumber)
    {
        $products = Product::where(function($query) use ($batchNumber) {
                $query->where('batch_number', 'LIKE', $batchNumber . '-%')
                      ->orWhere('batch_number', '=', $batchNumber);
            })
            ->orderBy('batch_number')
            ->get();

        if ($products->isEmpty()) {
            return redirect()->route('products.index')
                           ->withErrors(['error' => 'Batch tidak ditemukan']);
        }

        return view('products.batch-print', compact('products', 'batchNumber'));
    }

    /**
     * Download QR Code (single)
     */
    public function downloadQr($id)
    {
        $product = Product::findOrFail($id);
        
        if (!$product->qr_code_path || !file_exists(public_path($product->qr_code_path))) {
            return back()->withErrors(['error' => 'QR Code tidak ditemukan']);
        }

        $extension = pathinfo($product->qr_code_path, PATHINFO_EXTENSION);
        $filename = $product->product_id . '.' . $extension;

        return response()->download(
            public_path($product->qr_code_path),
            $filename
        );
    }

    /**
     * Download semua QR code dari satu batch dalam ZIP
     */
    public function bulkDownloadQr($batchNumber)
    {
        $products = Product::where(function($query) use ($batchNumber) {
                $query->where('batch_number', 'LIKE', $batchNumber . '-%')
                      ->orWhere('batch_number', '=', $batchNumber);
            })
            ->get();

        if ($products->isEmpty()) {
            return back()->withErrors(['error' => 'Tidak ada produk dengan batch tersebut']);
        }
        set_time_limit(300);
        ini_set('memory_limit', '256M');

        $zipFileName = "QR_Codes_{$batchNumber}.zip";
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors(['error' => 'Gagal membuat file ZIP']);
        }

        foreach ($products as $product) {
            if ($product->qr_code_path && file_exists(public_path($product->qr_code_path))) {
                $zip->addFile(
                    public_path($product->qr_code_path),
                    $product->product_id . '.' . pathinfo($product->qr_code_path, PATHINFO_EXTENSION)
                );
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
            'ngrok-skip-browser-warning' => 'true',
            'X-Content-Type-Options' => 'nosniff',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Download Label (single) - label lengkap dengan QR tertempel
     */
    public function downloadLabel($id)
    {
        $product = Product::findOrFail($id);

        $labelPath = $this->generateLabel($product);

        if (!$labelPath) {
            return back()->withErrors(['error' => 'Gagal generate label. Cek log error.']);
        }

        $filename = $product->product_id . '_label.png';

        return response()->download($labelPath, $filename);
    }

    /**
     * Download semua label dari satu batch dalam ZIP
     */
    public function bulkDownloadLabel($batchNumber)
    {
        $products = Product::where(function($query) use ($batchNumber) {
                $query->where('batch_number', 'LIKE', $batchNumber . '-%')
                      ->orWhere('batch_number', '=', $batchNumber);
            })
            ->get();

        if ($products->isEmpty()) {
            return back()->withErrors(['error' => 'Tidak ada produk dengan batch tersebut']);
        }

        $zipFileName = "Labels_{$batchNumber}.zip";
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors(['error' => 'Gagal membuat file ZIP']);
        }

        foreach ($products as $product) {
            $labelPath = $this->generateLabel($product);
            if ($labelPath) {
                $zip->addFile($labelPath, $product->product_id . '_label.png');
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
        ])->deleteFileAfterSend(true);
    }
    /**
     * Halaman verifikasi langsung dari kamera HP
     */
    public function verifyPage($productId)
    {
        $product = Product::where('product_id', $productId)->first();

        if (!$product) {
            return view('verify', ['status' => 'PALSU', 'message' => 'Produk tidak ditemukan di database!', 'data' => null]);
        }

        $publicKey = $this->getPublicKey();
        $isValid = $this->verifySignature($product->hash_data, $product->signature, $publicKey);

        if (!$isValid) {
            return view('verify', ['status' => 'PALSU', 'message' => 'Signature tidak valid! Produk kemungkinan palsu.', 'data' => null]);
        }

        if ($product->isExpired()) {
            return view('verify', [
                'status' => 'KADALUARSA',
                'message' => 'Produk sudah melewati tanggal kadaluarsa!',
                'data' => [
                    'product_id' => $product->product_id,
                    'nama_produk' => $product->nama_produk,
                    'tanggal_kadaluarsa' => $product->tanggal_kadaluarsa->format('d-m-Y'),
                    'hash_data' => $product->hash_data,
                    'signature' => $product->signature,
                ]
            ]);
        }

        $scanCount = $product->scan_count + 1;
        $product->update([
            'is_verified' => true,
            'first_scan_at' => $product->first_scan_at ?? now(),
            'last_scan_at' => now(),
            'scan_count' => $scanCount,
        ]);

        $status = $scanCount <= 5 ? 'ASLI' : 'DUPLIKASI';
        $message = match(true) {
            $scanCount === 1  => 'Produk ASLI! Ini adalah scan pertama.',
            $scanCount <= 5   => "Produk ASLI! Produk ini sudah di-scan {$scanCount}x.",
            default           => "PERINGATAN! Produk sudah di-scan {$scanCount}x. Kemungkinan duplikasi QR Code!",
        };

        return view('verify', [
            'status' => $status,
            'message' => $message,
            'data' => [
                'product_id' => $product->product_id,
                'nama_produk' => $product->nama_produk,
                'tanggal_produksi' => $product->tanggal_produksi->format('d-m-Y'),
                'tanggal_kadaluarsa' => $product->tanggal_kadaluarsa->format('d-m-Y'),
                'batch_number' => $product->batch_number,
                'scan_count' => $scanCount,
                'first_scan_at' => $product->first_scan_at->format('d-m-Y H:i:s'),
                'hash_data' => $product->hash_data,
                'signature' => $product->signature,
            ]
        ]);
    }
    /**
     * API: Verify QR Code
     */
    public function verify(Request $request)
    {
        $productId = $request->input('product_id');
        
        $product = Product::where('product_id', $productId)->first();

        if (!$product) {
            return response()->json([
                'status' => 'PALSU',
                'message' => 'Produk tidak ditemukan di database!',
                'data' => null
            ], 404);
        }

        $publicKey = $this->getPublicKey();
        $isValid = $this->verifySignature($product->hash_data, $product->signature, $publicKey);

        if (!$isValid) {
            return response()->json([
                'status' => 'PALSU',
                'message' => 'Signature tidak valid! Produk kemungkinan palsu.',
                'data' => null
            ], 400);
        }

        if ($product->isExpired()) {
            return response()->json([
                'status' => 'KADALUARSA',
                'message' => 'Produk sudah melewati tanggal kadaluarsa!',
                'data' => [
                    'product_id' => $product->product_id,
                    'nama_produk' => $product->nama_produk,
                    'tanggal_kadaluarsa' => $product->tanggal_kadaluarsa->format('d-m-Y'),
                ]
            ], 200);
        }

        $scanCount = $product->scan_count + 1;
        $product->update([
            'is_verified' => true,
            'first_scan_at' => $product->first_scan_at ?? now(),
            'last_scan_at' => now(),
            'scan_count' => $scanCount,
        ]);

        $status = $scanCount <= 5 ? 'ASLI' : 'DUPLIKASI';
$message = match(true) {
    $scanCount === 1        => 'Produk ASLI! Ini adalah scan pertama.',
    $scanCount <= 5         => "Produk ASLI! Produk ini sudah di-scan {$scanCount}x.",
    default                 => "PERINGATAN! Produk sudah di-scan {$scanCount}x. Kemungkinan duplikasi QR Code!",
};

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => [
                'product_id' => $product->product_id,
                'nama_produk' => $product->nama_produk,
                'tanggal_produksi' => $product->tanggal_produksi->format('d-m-Y'),
                'tanggal_kadaluarsa' => $product->tanggal_kadaluarsa->format('d-m-Y'),
                'batch_number' => $product->batch_number,
                'scan_count' => $scanCount,
                'first_scan_at' => $product->first_scan_at->format('d-m-Y H:i:s'),
                'hash_data' => $product->hash_data,    // ← tambah ini
                'signature' => $product->signature,    // ← tambah ini
            ]
        ], 200);
    }
/**
     * Delete produk single
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
       // Hapus file QR
        if ($product->qr_code_path && file_exists(public_path($product->qr_code_path))) {
            unlink(public_path($product->qr_code_path));
        }

        // Hapus file label
        $labelPath = public_path('labels/' . $product->product_id . '_label.png');
        if (file_exists($labelPath)) {
            unlink($labelPath);
        }

        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Delete semua produk dalam 1 batch
     */
    public function destroyBatch($batchNumber)
    {
        $products = Product::where(function($query) use ($batchNumber) {
                $query->where('batch_number', 'LIKE', $batchNumber . '-%')
                      ->orWhere('batch_number', '=', $batchNumber);
            })->get();

        foreach ($products as $product) {
            if ($product->qr_code_path && file_exists(public_path($product->qr_code_path))) {
                unlink(public_path($product->qr_code_path));
            }

            // Hapus file label
            $labelPath = public_path('labels/' . $product->product_id . '_label.png');
            if (file_exists($labelPath)) {
                unlink($labelPath);
            }

            $product->delete();
        }

        return redirect()->route('products.index')
                         ->with('success', "Batch {$batchNumber} berhasil dihapus.");
    }

    /**
     * Delete semua produk
     */
    public function destroyAll()
    {
        $products = Product::all();

        foreach ($products as $product) {
            if ($product->qr_code_path && file_exists(public_path($product->qr_code_path))) {
                unlink(public_path($product->qr_code_path));
            }
            $product->delete();
        }

        return redirect()->route('products.index')
                         ->with('success', 'Semua produk berhasil dihapus.');
    }
    // ========================================
    // HELPER FUNCTIONS
    // ========================================

    /**
     * Generate ECDSA Signature
     */
    private function generateSignature($data, $privateKey)
    {
        try {
            $key = openssl_pkey_get_private($privateKey);
            
            if (!$key) {
                return false;
            }

            $signature = '';
            $success = openssl_sign($data, $signature, $key, OPENSSL_ALGO_SHA256);

            if (!$success) {
                return false;
            }

            return base64_encode($signature);
            
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify ECDSA Signature
     */
    private function verifySignature($data, $signatureBase64, $publicKey)
    {
        try {
            $signature = base64_decode($signatureBase64);
            $key = openssl_pkey_get_public($publicKey);
            
            if (!$key) {
                return false;
            }

            $result = openssl_verify($data, $signature, $key, OPENSSL_ALGO_SHA256);

            return $result === 1;
            
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get Public Key dari database
     */
    private function getPublicKey()
    {
        $key = \DB::table('system_keys')
                   ->where('key_name', 'main_public_key')
                   ->where('is_active', true)
                   ->first();

        return $key ? $key->public_key : null;
    }

    /**
     * Generate QR Code SVG (fungsi lama, tidak diubah)
     */
    private function generateQrCode($product)
    {
       $baseUrl = env('APP_NGROK_URL') 
            ? env('APP_NGROK_URL') 
            : env('APP_URL', 'http://127.0.0.1:8000');
        
        $qrData = $baseUrl . '/verify/' . $product->product_id;

        $fileName = $product->product_id . '.svg';
        $path = 'qrcodes/' . $fileName;
        $fullPath = public_path($path);

        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0755, true);
        }

        try {
            $qrGenerator = new \SimpleSoftwareIO\QrCode\Generator;
            
            $qrCodeImage = $qrGenerator->format('svg')
                                       ->size(300)
                                       ->margin(2)
                                       ->generate($qrData);
            
            file_put_contents($fullPath, $qrCodeImage);
            
        } catch (\Exception $e) {
            \Log::error('QR Code Generation Error: ' . $e->getMessage());
            return null;
        }

        return $path;
    }

    /**
     * Generate QR Code PNG (pakai endroid/qr-code, untuk label)
     */
    private function generateQrCodePng($product)
    {
        try {
           $baseUrl = env('APP_NGROK_URL') 
                ? env('APP_NGROK_URL') 
                : env('APP_URL', 'http://127.0.0.1:8000');
            
            $qrData = $baseUrl . '/verify/' . $product->product_id;

           $qrCode = \Endroid\QrCode\QrCode::create($qrData)
    ->setSize(260)
    ->setMargin(15)
    ->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High);

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);

            $fileName = $product->product_id . '_qr.png';
            $path = 'qrcodes/' . $fileName;
            $fullPath = public_path($path);

            if (!file_exists(public_path('qrcodes'))) {
                mkdir(public_path('qrcodes'), 0755, true);
            }

            file_put_contents($fullPath, $result->getString());

            return $fullPath;

        } catch (\Exception $e) {
            \Log::error('QR PNG Generation Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate Label PNG (tempel QR ke template label)
     */
    private function generateLabel($product)
    {
        try {
            // 1. Generate QR PNG
            $qrPngPath = $this->generateQrCodePng($product);

            if (!$qrPngPath) {
                return null;
            }

          // 2. Load template label — dari template yang dipilih atau default
$templateRecord = null;
\Log::info('label_template_id: ' . $product->label_template_id);
if ($product->label_template_id) {
    $templateRecord = \DB::table('label_templates')->find($product->label_template_id);
    \Log::info('template ditemukan: ' . ($templateRecord ? $templateRecord->nama_template : 'TIDAK ADA'));
}

$templatePath = $templateRecord
    ? public_path($templateRecord->file_path)
    : public_path('label-template.png');

if (!file_exists($templatePath)) {
    \Log::error('Label template tidak ditemukan: ' . $templatePath);
    return null;
}

$template = imagecreatefrompng($templatePath);

// 3. Load QR PNG
$qrImage = imagecreatefrompng($qrPngPath);

// 4. Koordinat dan ukuran QR — dari template atau default hardcode
$qrSizePx = $templateRecord ? $templateRecord->qr_size_px : 180;
$posX     = $templateRecord ? $templateRecord->pos_x      : 681;
$posY     = $templateRecord ? $templateRecord->pos_y      : 396;

            // 5. Resize QR ke ukuran yang sesuai
            $qrResized = imagecreatetruecolor($qrSizePx, $qrSizePx);
            imagecopyresampled(
                $qrResized, $qrImage,
                0, 0, 0, 0,
                $qrSizePx, $qrSizePx,
                imagesx($qrImage), imagesy($qrImage)
            );

            // 6. Tempel QR ke template
            imagecopy($template, $qrResized, $posX, $posY, 0, 0, $qrSizePx, $qrSizePx);

            // 7. Simpan hasil label
            $labelFileName = $product->product_id . '_label.png';
            $labelPath     = 'labels/' . $labelFileName;
            $labelFullPath = public_path($labelPath);

            if (!file_exists(public_path('labels'))) {
                mkdir(public_path('labels'), 0755, true);
            }

            imagepng($template, $labelFullPath);

            // 8. Bersihkan memory
            imagedestroy($template);
            imagedestroy($qrImage);
            imagedestroy($qrResized);

            return $labelFullPath;

        } catch (\Exception $e) {
            \Log::error('Generate Label Error: ' . $e->getMessage());
            return null;
      }
    }

    /**
     * Export laporan produk ke Excel
     */
    public function exportExcel(Request $request)
    {
        $filter = $request->input('filter', '1bulan');

        if ($filter === '1minggu') {
            $startDate   = Carbon::now()->subWeek();
            $endDate     = Carbon::now();
            $filterLabel = '1 Minggu Terakhir';
        } else {
            $startDate   = Carbon::now()->subMonth();
            $endDate     = Carbon::now();
            $filterLabel = '1 Bulan Terakhir';
        }

        $fileName = 'Laporan_Produk_' . str_replace(' ', '_', $filterLabel) . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new ProductsExport($startDate, $endDate, $filterLabel),
            $fileName
        );
    }
}