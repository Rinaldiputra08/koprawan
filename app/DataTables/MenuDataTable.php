<?php

namespace App\DataTables;

use App\Models\Menu;
use Illuminate\Support\Facades\Gate;
// use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
// use Yajra\DataTables\Html\Editor\Editor;
// use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MenuDataTable extends DataTable
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
            ->addColumn('action', function ($row) {
                $action = '';
                if (Gate::allows('update ' . request()->path())) {
                    $action .= ' <div data-id="' . $row->id . '" class="btn btn-outline-primary btn-sm edit">
                                    <span>Edit</span>
                                </div>&nbsp;';
                }
                if (Gate::allows('delete ' . request()->path())) {
                    $action .= '<div class="btn btn-outline-danger btn-sm trash" data-id="' . $row->id . '">
                                <span>Delete</span>
                            </div>';
                }
                return '<div class="d-flex">' . $action . '</div>';
            })
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Menu $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Menu $model)
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
            ->setTableId('menu-table')
            ->columns($this->getColumns())
            ->languagePaginatePrevious('&larr;')
            ->languagePaginateNext('&rarr;')
            ->minifiedAjax()
            ->orderBy(5);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false),
            Column::make('nama_menu'),
            Column::make('jenis_bisnis'),
            Column::make('url'),
            Column::make('icon'),
            Column::make('no_urut'),
            Column::make('main_menu'),
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
        return 'Menu_' . date('YmdHis');
    }
}
