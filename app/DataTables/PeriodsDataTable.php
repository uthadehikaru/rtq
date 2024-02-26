<?php

namespace App\DataTables;

use App\Models\Period;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PeriodsDataTable extends DataTable
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
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group" role="group">
                <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Aksi
                </button>
                <div class="dropdown-menu" aria-labelledby="action">';
                $buttons .= '<a href="javascript:;" class="dropdown-item pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';
                $buttons .= '</div></div>';

                return $buttons;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Period $model): QueryBuilder
    {
        return $model
        ->withCount('paymentDetails')
        ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()->responsive(true)
                    ->setTableId('Period-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->stateSave()
                    //->dom('Bfrtip')
                    ->orderBy(1)
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
            Column::make('name')->title('Nama'),
            Column::make('start_date')->title('Awal'),
            Column::make('end_date')->title('Akhir'),
            Column::make('payment_details_count')->title('Pembayaran'),
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
        return 'Period_'.date('YmdHis');
    }
}
