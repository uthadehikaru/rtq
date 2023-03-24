<?php

namespace App\DataTables;

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
    private $status = null;

    public function setStatus($status)
    {
        $this->status = $status;
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
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->where('members.full_name', 'like', '%'.$keyword.'%');
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y H:i');
            })
            ->addColumn('nik', function ($row) {
                return $row->payload['nik'];
            })
            ->addColumn('birth_date', function ($row) {
                return Carbon::createFromFormat('Y-m-d', $row->payload['birth_date'])->format('d M Y');
            })
            ->addColumn('profile_picture', function ($row) {
                if ($row->payload['profile_picture']) {
                    return '<a href="'.asset('storage/'.$row->payload['profile_picture']).'" data-lightbox="present-'.$row->id.'"><img src="'.thumbnail($row->payload['profile_picture'], 300, 400).'" width="100" /></a>';
                }
            })
            ->addColumn('status', function ($row) {
                if ($row->payload['verified']) {
                    return '<span class="text-success">Verified</span>';
                }

                return '<span class="text-danger">Not Verified</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
                if (! $row->payload['verified']) {
                    $buttons .= '<a href="'.route('biodata.edit', $row->id).'" 
                    onclick="return confirm(\'Apakah data sudah sesuai?\')" class="text-primary">Confirm</a>';
                }
                $buttons .= '<a href="javascript:;" class="ml-2 pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';

                return $buttons;
            })
            ->rawColumns(['action', 'profile_picture', 'status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Setting $model): QueryBuilder
    {
        $model = $model
        ->selectRaw('settings.*, members.full_name')
        ->join('members', 'members.id', 'settings.name')
        ->where('group', 'biodata')
        ->latest('settings.created_at');

        switch($this->status) {
            case 'verified':
                $model = $model->where('payload->verified', true);
                break;
            case 'unverified':
                $model = $model->where('payload->verified', false);
                break;
        }

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
                    ->setTableId('member-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
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
            Column::make('created_at')->title('Tanggal')->searchable(false),
            Column::make('full_name')->title('Nama'),
            Column::make('nik')->title('NIK'),
            Column::make('birth_date')->title('Tanggal Lahir'),
            Column::make('status'),
            Column::make('profile_picture')->title('Foto'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->dom('Bfrtip')
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
        return 'Biodata_'.date('YmdHis');
    }
}
