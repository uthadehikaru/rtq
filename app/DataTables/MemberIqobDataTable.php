<?php

namespace App\DataTables;

use App\Models\Violation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MemberIqobDataTable extends DataTable
{
    private $user_id = 0;

    public function setUser($user_id)
    {
        $this->user_id = $user_id;
    }

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
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Violation $model): QueryBuilder
    {
        return $model
        ->where('user_id', $this->user_id)
        ->where('type', 'member')
        ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('member-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
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
            Column::make('violated_date')->title('Tanggal Pelanggaran'),
            Column::make('description')->title('Keterangan'),
            Column::make('amount')->title('Nominal'),
            Column::make('paid_at')->title('Dibayar pada'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'MemberPresent_'.date('YmdHis');
    }
}
