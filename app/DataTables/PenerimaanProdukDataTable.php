<?php

namespace App\DataTables;

use App\Models\Pembelian\PenerimaanProduk;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PenerimaanProdukDataTable extends DataTable
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
            ->eloquent($query->with('supplier:id,nama'))
            ->skipTotalRecords()
            ->editColumn('tanggal_penerimaan', function ($row) {
                return $row->tanggal_penerimaan_formatted;
            })
            ->editColumn('nomor_tagihan', function ($row) {
                return $row->nomor_tagihan ?? '<span class="text-danger font-small-3">Belum penerimaan tagihan</span>';
            })
            ->editColumn('total', function ($row) {
                return $row->total_formatted;
            })
            ->addColumn('grand_total', function ($row) {
                return $row->grand_total;
            })
            ->addColumn('batal', function ($row) {
                if ($row->tanggal_batal) {
                    return "<span class='text-danger font-small-3'>Dibatalkan oleh $row->user_batal</span>";
                }
            })
            ->addColumn('action', function ($row) {
                $user = request()->user();
                $action = '<div class="btn-group">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('pembelian.penerimaan-produk.show', $row->id) . '">Detail</a>';
                if ($user->can('update ' . request()->path())) {
                    if (!$row->tanggal_batal and !$row->nomor_tagihan) {
                        $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('pembelian.penerimaan-produk.edit', $row->id) . '">Penerimaan Tagihan</a>';
                    }
                }

                if ($user->can('cancel ' . request()->path())) {
                    if (!$row->tanggal_batal) {
                        $action .= '<a class="dropdown-item batal" data-method="PUT" href="' . route('pembelian.penerimaan-produk.batal', $row->id) . '">Batal</a>';
                    }
                }

                if ($user->can('print ' . request()->path()) and !$row->tanggal_batal) {
                    $action .= '<a class="dropdown-item" target="_blank" href="' . route('pembelian.penerimaan-produk.cetak', $row->uuid) . '">Cetak</a>';
                }

                return $action .= '</div>';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'nomor_tagihan', 'batal']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Pembelian\PenerimaanProduk $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PenerimaanProduk $model)
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
            ->setTableId('penerimaanproduk-table')
            ->parameters(['searchDelay' => 1000, 'responsive' => ['details' => ['display' => '$.fn.dataTable.Responsive.display.childRowImmediate']]])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->languagePaginatePrevious('&larr;')
            ->languagePaginateNext('&rarr;')
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
            Column::make('DT_RowIndex')->width(20)->title('no')->orderable(false)->searchable(false),
            Column::make('nomor'),
            Column::make('supplier.nama')->name('supplier.nama')->title('supplier'),
            Column::make('tanggal_penerimaan')->title('tanggal'),
            Column::make('nomor_tagihan'),
            Column::make('total'),
            Column::make('ppn'),
            Column::make('grand_total')->orderable(false)->searchable(false),
            Column::make('batal')->orderable(false)->searchable(false),
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
        return 'PenerimaanProduk_' . date('YmdHis');
    }
}
