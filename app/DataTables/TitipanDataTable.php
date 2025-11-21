<?php

namespace App\DataTables;

use App\Models\Titipan\Titipan;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TitipanDataTable extends DataTable
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
            ->eloquent($query->with('fotoThumbnail:referensi_id,nama_file'))
            ->editColumn('harga_jual', function ($row) {
                return $row->harga_jual_formatted;
            })
            ->editColumn('approval', function ($row) {
                if (is_null($row->approval)) {
                    return 'Menunggu persetujuan';
                } elseif ($row->approval) {
                    return 'Disetujui';
                }
                return 'Ditolak';
            })
            ->addColumn('foto', function ($row) {
                if ($row->fotoThumbnail) {
                    return '<img class="rounded" style="width:90px; height:90px;" src="' . asset('storage/images/produk-titipan/small_' . $row->fotoThumbnail->nama_file) . '" />';
                }
            })
            ->addColumn('action', function ($row) {
                $user = request()->user();
                $action = '<div class="btn-group">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';


                $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('titipan.produk-titipan.show', $row->id) . '">Detail</a>';

                if (is_null($row->approval) && $user->can('approve ' . request()->path())) {
                    $action .= '<a class="dropdown-item action" data-method="GET" href="' . route('titipan.approve', $row->uuid) . '">Approve</a>';
                }
                return $action .= '</div>';
            })
            ->rawColumns(['action', 'foto'])
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MasterData\Produk $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Titipan $model)
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
            ->setTableId('titipan-table')
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
            Column::make('foto'),
            Column::make('judul'),
            Column::make('harga_jual'),
            Column::make('stock_free')->title('Stock'),
            Column::make('approval')->title('Status'),
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
        return 'Titipan_' . date('YmdHis');
    }
}
