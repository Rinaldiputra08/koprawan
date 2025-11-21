<?php

namespace App\DataTables;

use App\Models\MasterData\Kategori;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KategoriDataTable extends DataTable
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
            ->eloquent($query->with('foto'))
            ->addColumn('action', function ($row) {
                $user = request()->user();
                $action = '<div class="btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                if ($user->can('update ' . request()->path())) {
                    $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('master-data.kategori.edit', $row->id) . '">Edit</a>';
                }

                return $action .= '</div>';
            })
            ->editColumn('aktif', function($row){
                return $row->aktif ? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check text-success"><polyline points="20 6 9 17 4 12"></polyline></svg>' : 
                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x text-danger"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
            })
            ->addColumn('foto', function($row){
                if($row->foto){
                    return '<img class="rounded" style="width:90px; height:90px;" src="'.asset('storage/images/kategori-produk/small_'.$row->foto->nama_file).'" />';
                }
            })
            ->rawColumns(['foto','action','aktif'])
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MasterData\Kategori $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Kategori $model)
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
            ->setTableId('kategori-table')
            ->parameters(['searchDelay' => 1000, 'responsive' => ['details' => ['display' => '$.fn.dataTable.Responsive.display.childRowImmediate']]])
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
            Column::make('foto'),
            Column::make('nama'),
            Column::make('aktif'),
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
        return 'Kategori_' . date('YmdHis');
    }
}
