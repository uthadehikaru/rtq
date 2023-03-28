<?php

namespace App\DataTables;

use App\Models\Violation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ViolationsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('violated_date', function ($row) {
                return $row->violated_date?->format('d M Y');
            })
            ->editColumn('paid_at', function ($row) {
                return $row->paid_at?->format('d M Y');
            })
            ->addColumn('action', function ($row) {
                return '
                <a href="'.route('violations.edit', $row->id).'" class="text-warning">
                    <i class="la la-edit"></i> Ubah
                </a>
                <a href="javascript:;" class="text-danger delete" data-id="'.$row->id.'">
                    <i class="la la-trash"></i> Hapus
                </a>
                ';
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Violation  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Violation $model): QueryBuilder
    {
        return $model
        ->select('violations.*')
        ->with('user')
        ->latest('violated_date')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('violations-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->responsive(true)
                    //->dom('Bfrtip')
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('violated_date')->title('tgl pelanggaran'),
            Column::make('user.name')->title('Nama'),
            Column::make('description'),
            Column::make('amount')->title('nominal'),
            Column::make('paid_at')->title('diselesaikan pada'),
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
    protected function filename(): string
    {
        return 'Violations_'.date('YmdHis');
    }
}
