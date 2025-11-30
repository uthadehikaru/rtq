<?php

namespace App\DataTables;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\QueryDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FailedJobsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): QueryDataTable
    {
        return (new QueryDataTable($query))
            ->editColumn('failed_at', function ($row) {
                return $row->failed_at ? \Carbon\Carbon::parse($row->failed_at)->format('d M Y H:i:s') : '-';
            })
            ->editColumn('queue', function ($row) {
                return $row->queue ?? '-';
            })
            ->editColumn('connection', function ($row) {
                return $row->connection ?? '-';
            })
            ->editColumn('exception', function ($row) {
                $exception = json_decode($row->exception, true);
                $message = $exception['message'] ?? 'No exception message';
                return '<span title="'.htmlspecialchars($message, ENT_QUOTES).'">'.Str::limit($message, 100).'</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '
                <div class="btn-group" role="group">
                    <button id="action-'.$row->id.'" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action-'.$row->id.'">
                        <a href="'.route('failed-jobs.retry', $row->id).'" class="dropdown-item text-success retry-job" data-id="'.$row->id.'">
                            <i class="la la-redo"></i> Retry
                        </a>
                        <a href="javascript:;" class="dropdown-item text-danger forget-job" data-id="'.$row->id.'">
                            <i class="la la-trash"></i> Forget
                        </a>
                    </div>
                </div>';

                return $buttons;
            })
            ->rawColumns(['action', 'exception'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return DB::table('failed_jobs')->orderBy('failed_at', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('failed-jobs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave()
            ->responsive(true)
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
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
            Column::make('id')->title('ID'),
            Column::make('uuid')->title('UUID'),
            Column::make('connection')->title('Connection'),
            Column::make('queue')->title('Queue'),
            Column::make('exception')->title('Exception')->searchable(false),
            Column::make('failed_at')->title('Failed At'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'FailedJobs_'.date('YmdHis');
    }
}

