<?php

namespace App\DataTables;

use App\Models\Promo\Diskon;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DiskonDataTable extends DataTable
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
            ->eloquent($query->with('produk:id,kode,nama'))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = request()->user();
                $action = '<div class="btn-group">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                if ($user->can('update ' . request()->path())) {
                    $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('promo.diskon.edit', $row->id) . '">Edit</a>';
                }

                return $action .= '</div>';
            })
            ->editColumn('tanggal_awal', function($row){
                return $row->tanggal_awal_formatted;
            })
            ->editColumn('tanggal_akhir', function($row){
                return $row->tanggal_akhir_formatted;
            })
            ->editColumn('nominal', function($row){
                return $row->nominal_formatted;
            })
            ->addColumn('nama_produk', function($row){
                return $row->produk->kode. ' - '.$row->produk->nama;
            })
            ->filter(function(Builder $query){
                if(request()->filter == 'berlaku'){
                    return $query->where('tanggal_awal', '<=', now())->where('tanggal_akhir', '>=', now());
                }
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Diskon $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Diskon $model)
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
            ->setTableId('diskon-table')
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
            Column::make('DT_RowIndex')->title('no')->orderable(false)->searchable(false),
            Column::make('id')->hidden(),
            Column::make('nama_produk'),
            Column::make('nominal'),
            Column::make('tanggal_awal'),
            Column::make('tanggal_akhir'),
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
        return 'Diskon_' . date('YmdHis');
    }
}
