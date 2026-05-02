<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $filterLabel;

    public function __construct($startDate, $endDate, $filterLabel)
    {
        $this->startDate    = $startDate;
        $this->endDate      = $endDate;
        $this->filterLabel  = $filterLabel;
    }

    public function collection()
    {
        return Product::whereBetween('created_at', [
                $this->startDate->startOfDay(),
                $this->endDate->endOfDay(),
            ])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function title(): string
    {
        return 'Laporan Produk';
    }

    public function headings(): array
    {
        return [
            'No',
            'Product ID',
            'Nama Produk',
            'Batch Number',
            'Tanggal Produksi',
            'Tanggal Kadaluarsa',
            'Status',
            'Jumlah Scan',
            'Pertama Scan',
            'Terakhir Scan',
            'Dibuat',
        ];
    }

    public function map($product): array
    {
        static $no = 0;
        $no++;

        $status = $product->status;
        $statusLabel = match($status) {
            'BELUM_DISCAN' => 'Belum Di-scan',
            'ASLI'         => 'Asli',
            'DUPLIKASI'    => 'Duplikasi',
            'KADALUARSA'   => 'Kadaluarsa',
            default        => $status,
        };

        return [
            $no,
            $product->product_id,
            $product->nama_produk,
            $product->batch_number ?? '-',
            $product->tanggal_produksi->format('d/m/Y'),
            $product->tanggal_kadaluarsa->format('d/m/Y'),
            $statusLabel,
            $product->scan_count,
            $product->first_scan_at ? $product->first_scan_at->format('d/m/Y H:i') : '-',
            $product->last_scan_at  ? $product->last_scan_at->format('d/m/Y H:i')  : '-',
            $product->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header row styling
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF6B3E26'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Semua baris data center vertical
        $sheet->getStyle('A1:K1000')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Row height header
        $sheet->getRowDimension(1)->setRowHeight(22);

        return [];
    }
}