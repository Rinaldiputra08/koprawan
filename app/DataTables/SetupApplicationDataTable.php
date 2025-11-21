<?php

namespace App\DataTables;

use App\Models\SetupApplication;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SetupApplicationDataTable extends DataTable
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
            ->skipTotalRecords()
            ->addColumn('type', function ($row) {
            })
            ->addColumn('action', function ($row) {
                return '<a href=' . route('setup-aplikasi.edit', $row->id) . ' data-method="GET" class="btn btn-sm btn-outline-primary action">Edit</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SetupApplication $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SetupApplication $model)
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
            ->setTableId('setupapplication-table')
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
            Column::make('name'),
            Column::make('value'),
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
        return 'SetupApplication_' . date('YmdHis');
    }
}
