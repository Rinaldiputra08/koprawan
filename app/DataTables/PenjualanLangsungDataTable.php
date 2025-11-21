<?php

namespace App\DataTables;

use App\Models\Penjualan\Penjualan;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PenjualanLangsungDataTable extends DataTable
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
            ->eloquent($query->with('karyawan'))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = request()->user();
                $action = '<div class="btn-group">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('penjualan.penjualan-langsung.show', $row->id) . '">Detail</a>';

                if ($user->can('cancel ' . request()->path())) {
                    if (dateFormat($row->tanggal, 'm') == now()->format('m') && !$row->batal) {
                        $action .= '<a class="dropdown-item batal" data-method="PUT" href="' . route('penjualan.penjualan-langsung.batal', $row->id) . '">Batal</a>';
                    }
                }

                return $action .= '</div>';
            })
            ->editColumn('total', function ($row) {
                return $row->total_formatted;
            })
            ->addColumn('grand_total', function ($row) {
                return $row->grand_total_formatted;
            })
            ->addColumn('diskon', function ($row) {
                return $row->diskon_formatted;
            })

            ->addColumn('batal', function ($row) {
                if ($row->tanggal_batal) {
                    return "Dibatalkan oleh $row->user_batal";
                }
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Supplier $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Penjualan $model)
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
            ->setTableId('penjualanlangsung-table')
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
            Column::make('karyawan.nama')->name('karyawan.nama')->title('karyawan'),
            Column::make('total'),
            Column::make('diskon'),
            Column::make('grand_total'),
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
        return 'PenjualanLangsung_' . date('YmdHis');
    }
}
