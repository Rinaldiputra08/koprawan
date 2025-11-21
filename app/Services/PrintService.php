<?php

namespace App\Services;

class PrintService extends FpdfHtmlService
{
    public
        $page_width,
        $page_height;

    public function __construct()
    {
        parent::__construct();
        $this->page_width = $this->GetPageWidth();
        $this->page_height = $this->GetPageHeight();
    }

    /**
     * Generate watermark
     * 
     * @param string $image string path image
     * @return void
     */
    public function Watermark($image = null)
    {
        $image = $image ?? public_path('assets/images/logo/lambang.png');

        $img_width = 125;
        $img_height = 120;

        $x = ($this->page_width / 2) - ($img_width / 2);
        $y = ($this->page_height / 2) - ($img_height / 2);

        $this->Image($image, $x, $y, $img_width, $img_height);
    }

    /**
     * Generate header
     * 
     * @return void
     */
    public function Header($show = false)
    {
        if ($show) {
            $this->SetFont('Arial', 'B', 12);
            $this->Image(public_path('assets/images/logo/lambang.png'), 10, 10, 25, 25);

            $width = $this->page_width - 48;
            $x = 39;
            $this->SetX($x);
            $this->Cell($width, 10, 'HONDA BINTARO', 0, 0, 'L');
            $this->SetFont('Arial', '', 10);

            $alamat = 'CBD 03 dan 05, Blok A2, Kota Taman Bintaro Jaya Sektor VII, Pondok Aren, Pd. Jaya, Kec. Tangerang, Tangerang Selatan, Banten 15224';
            $this->Ln();
            $this->SetX($x);
            $this->MultiCell($width, 5, $alamat, 0, 'L');
            $this->SetY($this->GetY() + 2.5);
            $this->SetX($x);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell($width, 0, 'No Telp. (021) 7457231');

            // line
            $page_width = $this->page_width;
            $x = 10;
            $y = $this->GetY() + 5;
            $this->SetLineWidth(0.75);
            $this->Line($x, $y, $page_width - $x, $y);
        }
    }

    /**
     * Generate centered title
     * 
     * @param string $text
     * @param int $font_size
     * @return void
     */
    public function Title($text, $font_size = 12)
    {
        $this->SetFont('Arial', 'B', $font_size);
        $this->Ln();
        $this->SetX(0);
        $this->SetY($this->GetY() + 10, false);
        $this->Cell($this->page_width, 10, $text, 0, 0, 'C');
    }

    /** 
     * Generate Table
     * 
     * @param array $columns array associative with value as width of each column
     */
    public function Table($columns, $data, $border = 1, $font_size = 9)
    {
        $this->SetFont('Arial', 'B', $font_size);
        $this->SetY($this->GetY() + 5, false);
        // generate header
        $this->SetFillColor(212, 212, 212);
        $this->Cell(10, 10, 'NO', $border, 0, 'C', true);
        foreach ($columns as $title => $column) {
            $this->Cell($column['width'], 10, strtoupper($title), $border, 0, $column['align'], true);
        }

        $y = $this->GetY() + 10;
        $this->SetLineWidth(0.25);
        $this->Line(10, $y, $this->page_width - 10, $y);

        // generate data
        $this->SetFont('Arial', '', $font_size);
        $iteration = 0;
        $columns = array_values($columns);
        $this->SetX(10);
        $this->Ln();
        foreach ($data as $items) {
            $index = 0;
            $this->Cell(10, 10, ++$iteration, $border, 0, 'C');

            foreach ($items as $item) {
                $width = $columns[$index]['width'];
                $align = $columns[$index]['align'];
                if ($item['type'] == 'cell') {
                    $this->Cell($width, 10, $item['value'], $border, 0, $align);
                } else {
                    $this->SetY($this->GetY(), false);
                    $this->MultiCell($width, 10, $item['value'], $border, 'L');
                    $this->SetXY($this->page_width - 100, $this->GetY() - 10);
                }
                $index++;
            }
            $this->Ln();
            $y = $this->GetY();
            $this->Line(10, $y, $this->page_width - 10, $y);
        }
    }

    /**
     * Generate Footer
     * 
     * @param boolean $show
     * 
     * @return void
     */
    public function Footer($show = true)
    {
        if ($show) {
            $this->SetFont('Courier', '', 8);
            $this->SetXY(10, $this->page_height - 10);
            $this->Cell($this->page_width - 20, 0, 'Dicetak tanggal ' . now(), 0, 0, 'R');
        }
    }
}
