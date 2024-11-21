<?php

namespace App\DataTables;

use App\Models\Present;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MemberPresentDataTable extends DataTable
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
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($row) {
                return $row->schedule->scheduled_at?->format('d M Y');
            })
            ->editColumn('schedule_id', function ($row) {
                return $row->schedule->start_at?->format('H:i');
            })
            ->addColumn('place', function ($row) {
                return $row->schedule->place;
            })
            ->editColumn('status', function ($row) {
                return __('app.present.status.'.$row->status);
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     */
    public function query(Present $model): QueryBuilder
    {
        return $model
            ->with(['schedule'])
            ->where('user_id', $this->user_id)
            ->where('type', 'member')
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()->responsive(true)
            ->setTableId('member-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave()
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
     */
    public function getColumns(): array
    {
        return [
            Column::make('created_at')->title('Tanggal'),
            Column::make('schedule_id')->title('Jam'),
            Column::make('place')->title('Tempat'),
            Column::make('status')->title('Status'),
            Column::make('description')->title('Keterangan'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'MemberPresent_'.date('YmdHis');
    }
}
