<?php

namespace App\DataTables;

use App\Models\Member;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MembersBiodataDataTable extends DataTable
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
                return $row->created_at->format('d M Y H:i');
            })
            ->addColumn('name', function($row){
                $member = Member::find($row->name);
                return $member?->full_name;
            })
            ->addColumn('nik', function($row){
                return $row->payload['nik'];
            })
            ->addColumn('birth_date', function($row){
                return Carbon::createFromFormat('Y-m-d',$row->payload['birth_date'])->format('d M Y');
            })
            ->addColumn('profile_picture', function($row){
                if($row->payload['profile_picture'])
                return '<a href="'.asset('storage/'.$row->payload['profile_picture']).'" target="_blank"><img src="'.asset('storage/'.$row->payload['profile_picture']).'" width="200" /></a>';
            })
            ->addColumn('action', function($row){
                $buttons = "";
                if(!$row->payload['verified']){
                    $buttons .= '<a href="'.route('biodata.edit', $row->id).'" 
                    onclick="return confirm(\'Apakah data sudah sesuai?\')" class="text-primary">Confirm</a>';
                }else{
                    $buttons .= '<a href="#" class="text-success">Verified</a>';
                }
                $buttons .= '<a href="javascript:;" class="ml-2 pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';
                return $buttons;
            })
            ->rawColumns(['action','profile_picture'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Registration $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Setting $model): QueryBuilder
    {
        return $model
        ->where('group','biodata')
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
                    ->setTableId('member-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
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
            Column::make('created_at')->title('Tanggal'),
            Column::make('name')->title('Nama'),
            Column::make('nik')->title('NIK'),
            Column::make('birth_date')->title('Tanggal Lahir'),
            Column::make('profile_picture')->title('Foto'),
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
