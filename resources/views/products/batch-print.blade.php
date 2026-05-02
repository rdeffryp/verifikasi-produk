<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Batch {{ $batchNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            page-break-after: avoid;
        }
        
        .print-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .print-header p {
            font-size: 14px;
            color: #666;
        }
        
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .qr-item {
            border: 2px solid #333;
            padding: 15px;
            text-align: center;
            page-break-inside: avoid;
            background: white;
        }
        
        .qr-item svg {
            width: 100%;
            height: auto;
            max-width: 200px;
            margin: 0 auto;
            display: block;
        }
        
        .qr-item img {
            width: 100%;
            height: auto;
            max-width: 200px;
            margin: 0 auto;
            display: block;
        }
        
        .product-id {
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
            word-break: break-all;
        }
        
        .batch-number {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .no-print {
                display: none !important;
            }
            
            .qr-grid {
                gap: 15px;
            }
            
            .qr-item {
                border: 1px solid #000;
                padding: 10px;
            }
            
            @page {
                margin: 1cm;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button onclick="window.print()" class="print-button no-print">
        <i>🖨️</i> Print Halaman Ini
    </button>

    <!-- Header -->
    <div class="print-header">
        <h1>QR Code Batch: {{ $batchNumber }}</h1>
        <p>
            <strong>Produk:</strong> {{ $products->first()->nama_produk }} | 
            <strong>Total:</strong> {{ $products->count() }} produk | 
            <strong>Tanggal:</strong> {{ now()->format('d F Y') }}
        </p>
    </div>

    <!-- QR Code Grid -->
    <div class="qr-grid">
        @foreach($products as $product)
        <div class="qr-item">
            @if($product->qr_code_path && file_exists(public_path($product->qr_code_path)))
                @if(str_ends_with($product->qr_code_path, '.svg'))
                    {!! file_get_contents(public_path($product->qr_code_path)) !!}
                @else
                    <img src="{{ asset($product->qr_code_path) }}" alt="QR {{ $product->product_id }}">
                @endif
            @else
                <div style="width: 200px; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #999;">QR Not Found</span>
                </div>
            @endif
            <div class="product-id">{{ $product->product_id }}</div>
            <div class="batch-number">{{ $product->batch_number }}</div>
        </div>
        @endforeach
    </div>

    <!-- Footer Info -->
    <div class="print-header" style="margin-top: 30px; page-break-before: avoid;">
        <p style="font-size: 12px; color: #999;">
            Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB | 
            Sistem Verifikasi Produk dengan ECDSA Digital Signature
        </p>
    </div>

    <script>
        // Auto print setelah load (optional)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // }
    </script>
</body>
</html>