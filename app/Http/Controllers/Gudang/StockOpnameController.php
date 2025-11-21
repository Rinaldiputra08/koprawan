<?php

namespace App\Http\Controllers\Gudang;

use Codedge\Fpdf\Fpdf\Fpdf;

use Illuminate\Http\Request;
use App\Models\MasterData\Produk;
use App\Http\Controllers\Controller;
use App\Services\PrintService;

class StockOpnameController extends Controller
{

    public function index(Request $request)
    {
        if($request->has('Cetak'))
        {
            
            $this->cetakStockOpname();
        
        }        
        return view('gudang.stock-opname');
    }
    
    private function cetakStockOpname()
    {
        $produk = Produk::get();
        
        $pdf = new PrintService;       
        // dd($pdf);

        $pdf->AddPage('', 'legal');
        $pdf->SetFont('Arial', '', '10');
        $pdf->Watermark();
        $pdf->Header(true);
        $pdf->Title('Stock Opname');
        $pdf->SetXY(10, $pdf->GetY() + 10);

        $columns = [
            'Nama Produk' => [
                'width' => 85,
                'align' => 'L'
            ],
            'Pick' => [
                'width' => 15,
                'align' => 'C'
            ],
            'Sisa Stock' => [
                'width' => 25,
                'align' => 'C'
            ],
            'Stock Fisik' => [
                'width' => 25,
                'align' => 'C'
            ],
            'Keterangan' => [
                'width' => 35,
                'align' => 'C'
            ],
        ];

        $data = [];
        foreach ($produk as $value) {
            // dd($value);
            $data[] =[
                [
                    'type' => 'cell',
                    'value' => $value->kode . " - " . $value->nama
                ],
                [
                    'type' => 'cell',
                    'value' => $value->stock_free
                ],
                [
                    'type' => 'cell',
                    'value' => $value->stock_free
                ],
                [
                    'type' => 'cell',
                    'value' => ""
                ],
                [
                    'type' => 'cell',
                    'value' => ''
                ],
            ];
            
        }
        $pdf->SetXY(10, $pdf->GetY() + 1);
        $pdf->Table($columns, $data, 1, 8);
        // $y = $pdf->GetY();

        $pdf->Output('I', 'Stock Opname.pdf');
        exit;

    }


}