<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Gudang\BeritaAcaraGudang;
use App\Services\PrintService;
use Illuminate\Http\Request;

class BeritaAcaraGudangPrintController extends Controller
{
    public function __invoke(BeritaAcaraGudang $beritaAcaraGudang, PrintService $pdf)
    {
        $pdf->AddPage('', 'A4');
        $pdf->SetFont('Arial', '', '10');
        $pdf->Watermark();
        $pdf->Header(true);
        $pdf->Title('BERITA ACARA GUDANG');

        $beritaAcaraGudang->load(['beritaAcaraGudangDetail.produk']);
        $page_width = $pdf->page_width;
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(10, $pdf->GetY() + 20);
        $width = $page_width / 2;
        $pdf->Cell(30, 0, 'Jenis');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell($width - 35, 0, $beritaAcaraGudang->jenis);
        $pdf->Ln(7);
        $pdf->Cell(30, 0, 'Ketrangan ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell($width - 35, 0, $beritaAcaraGudang->keterangan);
        $pdf->Ln(7);


        // item
        $columns = [
            'Nomor' => [
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
            'keterangan' => [
                'width' => 75,
                'align' => 'L'
            ],
        ];
        $data = [];
        foreach ($beritaAcaraGudang->beritaAcaraGudangDetail as $item) {
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
                    'value' => $item->qty
                ],
                [
                    'type' => 'cell',
                    'value' => $item->keterangan
                ],
            ];
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($page_width - 20, 7, 'Nomor : ' . $beritaAcaraGudang->nomor . ' / ' . $beritaAcaraGudang->tanggal_berita_acara_formatted, 0, 0, 'R');
        $pdf->Ln(3);
        $pdf->Table($columns, $data, 0);
        $y = $pdf->GetY();


        // signature
        $pdf->SetXY($page_width - 72, $pdf->GetY());
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60, 7, 'Tangerang Selatan, ' . getFullDate($beritaAcaraGudang->tanggal), 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetXY($page_width - 72, $pdf->GetY());
        $pdf->Cell(60, 7, 'Dibuat Oleh,', 0, 0, 'C');
        $pdf->Ln(15);
        $pdf->SetXY($page_width - 72, $pdf->GetY());
        $pdf->Cell(60, 7, strtoupper($beritaAcaraGudang->user_input), 0, 0, 'C');

        $pdf->Output('I', 'Penerimaan Produk - ' . $beritaAcaraGudang->nomor . '.pdf');
        exit;
    }
}
