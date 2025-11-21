<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\Pembelian\PenerimaanProduk;
use App\Services\PrintService;
use Illuminate\Http\Request;

class PenerimaanProdukPrintController extends Controller
{
    public function __invoke(PenerimaanProduk $penerimaanProduk, PrintService $pdf)
    {
        $pdf->AddPage('', 'A4');
        $pdf->SetFont('Arial', '', '10');
        $pdf->Watermark();
        $pdf->Header(true);
        $pdf->Title('PENERIMAAN PRODUK');

        $penerimaanProduk->load(['supplier', 'penerimaanDetail.produk']);
        $page_width = $pdf->page_width;
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(10, $pdf->GetY() + 20);
        $width = $page_width / 2;
        $pdf->Cell(30, 0, 'Supplier');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell($width - 35, 0, $penerimaanProduk->supplier->nama);
        $pdf->Ln(7);
        $pdf->Cell(30, 0, 'Nomor Telp.');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell($width - 35, 0, $penerimaanProduk->supplier->nomor_telepon);
        $pdf->Ln(7);
        $pdf->Cell(30, 0, 'Alamat');
        $pdf->Cell(5, 0, ':');
        $pdf->SetY($pdf->GetY() - 2, false);
        $pdf->MultiCell($width - 35, 5, $penerimaanProduk->supplier->alamat, 0, 'L');

        if ($penerimaanProduk->nomor_tagihan and $penerimaanProduk->nomor_tagihan != '-') {
            $pdf->Ln(7);
            $pdf->Cell(30, 0, 'Tagihan');
            $pdf->Cell(5, 0, ':');
            $pdf->Cell($width - 35, 0, $penerimaanProduk->nomor_tagihan . '/' . $penerimaanProduk->tanggal_tagihan_formatted);
            $pdf->Ln(7);
        }

        // item
        $columns = [
            'kode' => [
                'width' => 20,
                'align' => 'L'
            ],
            'nama produk' => [
                'width' => 70,
                'align' => 'L'
            ],
            'qty' => [
                'width' => 15,
                'align' => 'C'
            ],
            'harga' => [
                'width' => 25,
                'align' => 'R'
            ],
            'diskon' => [
                'width' => 25,
                'align' => 'R'
            ],
            'sub total' => [
                'width' => 25,
                'align' => 'R'
            ],
        ];
        $data = [];
        foreach ($penerimaanProduk->penerimaanDetail as $item) {
            $data[] = [
                [
                    'type' => 'cell',
                    'value' => $item->produk->kode
                ],
                [
                    'type' => 'cell',
                    'value' => $item->produk->nama
                ],
                [
                    'type' => 'cell',
                    'value' => $item->qty_formatted
                ],
                [
                    'type' => 'cell',
                    'value' => $item->produk->harga_beli_formatted
                ],
                [
                    'type' => 'cell',
                    'value' => $item->diskon_formatted
                ],
                [
                    'type' => 'cell',
                    'value' => $item->sub_total_formatted
                ],
            ];
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($page_width - 20, 7, 'Nomor : ' . $penerimaanProduk->nomor . ' / ' . $penerimaanProduk->tanggal_penerimaan_formatted, 0, 0, 'R');
        $pdf->Ln(3);
        $pdf->Table($columns, $data, 0);
        $y = $pdf->GetY();

        if ($penerimaanProduk->keterangan) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(165, 7, 'Note :');
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(115, 5, $penerimaanProduk->keterangan);
        }

        $pdf->SetXY(10, $y);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(165, 7, 'TOTAL : ', 0, 0, 'R');
        $pdf->Cell(25, 7, $penerimaanProduk->total_formatted, 0, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(165, 7, 'PPN : ', 0, 0, 'R');
        $pdf->Cell(25, 7, $penerimaanProduk->ppn_formatted, 0, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(165, 7, 'GRAND TOTAL : ', 0, 0, 'R');
        $pdf->Cell(25, 7, numberFormat($penerimaanProduk->total - $penerimaanProduk->ppn), 0, 0, 'R');
        $pdf->Ln(10);

        // signature
        $pdf->SetXY($page_width - 72, $pdf->GetY());
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60, 7, 'Tangerang Selatan, ' . getFullDate($penerimaanProduk->tanggal), 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetXY($page_width - 72, $pdf->GetY());
        $pdf->Cell(60, 7, 'Dibuat Oleh,', 0, 0, 'C');
        $pdf->Ln(15);
        $pdf->SetXY($page_width - 72, $pdf->GetY());
        $pdf->Cell(60, 7, strtoupper($penerimaanProduk->user_input), 0, 0, 'C');

        $pdf->Output('I', 'Penerimaan Produk - ' . $penerimaanProduk->nomor . '.pdf');
        exit;
    }
}
