<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionsDataTable extends DataTable
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
            ->editColumn('debit', function ($row) {
                return money($row->debit);
            })
            ->editColumn('credit', function ($row) {
                return money($row->credit);
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
                $buttons .= '<a href="'.route('transactions.edit', $row->id).'" class="text-warning">Edit</a>';
                $buttons .= '<a href="javascript:;" class="ml-2 pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';

                return $buttons;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Transaction  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()->responsive(true)
                    ->setTableId('transactions-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->stateSave()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
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
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('transaction_date')->title('Tanggal')->addClass('text-center'),
            Column::make('description')->title('Deskripsi'),
            Column::make('debit')->title('Masuk')->addClass('text-right'),
            Column::make('credit')->title('Keluar')->addClass('text-right'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Transactions_'.date('YmdHis');
    }
}
