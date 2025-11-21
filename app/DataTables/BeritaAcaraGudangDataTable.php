<?php

namespace App\DataTables;

use App\Models\BeritaAcaraGudang;
use App\Models\Gudang\BeritaAcaraGudang as GudangBeritaAcaraGudang;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BeritaAcaraGudangDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = request()->user();
                $action = '<div class="btn-group">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('gudang.berita-acara-gudang.show', $row->id) . '">Detail</a>';

                if ($user->can('cancel ' . request()->path())) {
                    if ($row->pemesanan_detail_count == 0 and !$row->tanggal_batal) {
                        $action .= '<a class="dropdown-item batal" data-method="PUT" href="' . route('gudang.berita-acara-gudang.batal', $row->id) . '">Batal</a>';
                    }
                }

                if ($user->can('print ' . request()->path()) and !$row->tanggal_batal) {
                    $action .= '<a class="dropdown-item" target="_blank" href="' . route('gudang.berita-acara-gudang.cetak', $row->nomor) . '">Cetak</a>';
                }

                return $action .= '</div>';
            })
            ->addColumn('batal', function ($row) {
                if ($row->tanggal_batal) {
                    return "<span class='text-danger font-small-3'>Dibatalkan oleh $row->user_batal</span>";
                }
            })
            ->editColumn('tanggal_berita_acara', function($row){
                return $row->tanggal_berita_acara_formatted;
            })
            ->rawColumns(['action', 'batal']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Supplier $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(GudangBeritaAcaraGudang $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->parameters(['searchDelay' => 1000, 'responsive' => ['details' => ['display' => '$.fn.dataTable.Responsive.display.childRowImmediate']]])
            ->setTableId('berita-acara-gudang-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')->title('no')->searchable(false)->orderable(false),
            Column::make('id')->hidden(),
            Column::make('nomor'),
            Column::make('jenis'),
            Column::make('keterangan'),
            Column::make('tanggal_berita_acara'),
            Column::make('batal'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Berita_Acara_Gudang_' . date('YmdHis');
    }
}
