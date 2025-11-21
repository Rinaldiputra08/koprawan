<?php

namespace App\DataTables;

use App\Models\Pembelian\PemesananProduk;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PemesananProdukDataTable extends DataTable
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
            ->eloquent($query->with('supplier:id,nama')->withCount(['pemesananDetail' => function ($query) {
                $query->selesaiPenerimaan();
            }]))
            ->editColumn('tanggal_pemesanan', function ($row) {
                return $row->tanggal_pemesanan_formatted;
            })
            ->editColumn('total', function ($row) {
                return $row->total_formatted;
            })
            ->editColumn('ppn', function ($row) {
                return $row->ppn_formatted;
            })
            ->addColumn('grand_total', function ($row) {
                return numberFormat($row->total - $row->ppn);
            })
            ->addColumn('status', function ($row) {
                if ($row->tanggal_batal) {
                    return "<span class='text-danger font-small-3'>Dibatalkan oleh $row->user_batal</span>";
                } elseif ($row->penerimaan) {
                    return "<span class='text-success font-small-3'>Penerimaan selesai<br>" . $row->pemesanan_detail_count . " produk diterima</span>";
                } elseif ($row->pemesanan_detail_count > 0) {
                    return "<span class='text-warning font-small-3'>Penerimaan belum selesai<br>" . $row->pemesanan_detail_count . " produk diterima</span>";
                }
                return "<span class='text-danger font-small-3'>Belum penerimaan</span>";
            })
            ->addColumn('action', function ($row) {
                $user = request()->user();
                $action = '<div class="btn-group">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('pembelian.pemesanan-produk.show', $row->id) . '">Detail</a>';

                if ($user->can('cancel ' . request()->path())) {
                    if ($row->pemesanan_detail_count == 0 and !$row->tanggal_batal) {
                        $action .= '<a class="dropdown-item batal" data-method="PUT" href="' . route('pembelian.pemesanan-produk.batal', $row->id) . '">Batal</a>';
                    }
                }

                if ($user->can('print ' . request()->path()) and !$row->tanggal_batal) {
                    $action .= '<a class="dropdown-item" target="_blank" href="' . route('pembelian.pemesanan-produk.cetak', $row->uuid) . '">Cetak</a>';
                }

                return $action .= '</div>';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Pembelian\PemesananProduk $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PemesananProduk $model)
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
            ->setTableId('pemesananproduk-table')
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
            Column::make('tanggal_pemesanan'),
            Column::make('supplier.nama')->name('supplier.nama')->title('supplier'),
            Column::make('total'),
            Column::make('ppn'),
            Column::make('grand_total'),
            Column::make('status')->orderable(false)->searchable(false),
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
        return 'PemesananProduk_' . date('YmdHis');
    }
}
