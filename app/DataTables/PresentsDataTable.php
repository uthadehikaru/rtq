<?php

namespace App\DataTables;

use App\Models\Present;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\SearchPane;
use Yajra\DataTables\Services\DataTable;

class PresentsDataTable extends DataTable
{

    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function($row){
                return $row->created_at->format('d M Y');
            })
            ->editColumn('schedule_id', function($row){
                return $row->schedule->batch->code.' - '.$row->schedule->batch->name;
            })
            ->editColumn('user_id', function($row){
                return $row->user->name;
            })
            ->editColumn('type', function($row){
                return __($row->type);
            })
            ->editColumn('status', function($row){
                $status = __('app.present.status.'.$row->status);
                if($row->status=='present' && $row->attended_at)
                    $status .= ' pada '.$row->attended_at->format('H:i');
                return $status;
            })
            ->editColumn('description', function($row){
                $description = $row->description;
                if($row->photo)
                    $description .= '<a href="'.asset('storage/'.$row->photo).'" target="_blank">Bukti Foto</a>';
                $description .= $row->type=='teacher' && $row->is_badal?'(Badal)':'';
                $description .= $row->type=='member' && $row->is_transfer?'(Operan)':'';
                return $description;
            })
            ->rawColumns(['description'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Registration $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Present $model): QueryBuilder
    {
        return $model
        ->with(['schedule','schedule.batch'])
        ->latest()
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
                    ->setTableId('Present-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('created_at')->title('Tanggal'),
            Column::make('schedule_id')->title('Halaqoh'),
            Column::make('user_id')->title('Nama'),
            Column::make('type')->title('Tipe'),
            Column::make('status')->title('Status'),
            Column::make('description')->title('Ketrangan'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Registration_' . date('YmdHis');
    }
}
