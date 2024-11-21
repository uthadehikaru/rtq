<?php

namespace App\DataTables;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RegistrationsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y H:i');
            })
            ->editColumn('birth_date', function ($row) {
                return $row->birth_date?->format('d M Y');
            })
            ->editColumn('gender', function ($row) {
                return __($row->gender);
            })
            ->editColumn('full_name', function ($row) {
                $value = '<a href="'.route('registrations.show', $row->id).'" class="text-primary">';
                $value .= $row->full_name;
                if ($row->user?->member) {
                    $value .= ' - '.$row->user->member->member_no;
                }
                $value .= '</a>';

                return $value;
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
                if ($row->user?->member->leave_at) {
                    $buttons .= '<a href="'.(route('members.edit', $row->user->member->id)).'" class="ml-2 pointer text-default">Keluar pada '.$row->user->member->leave_at->format('d M Y').'</a>';
                } elseif ($row->user?->member) {
                    $buttons .= '<a href="'.(route('members.edit', $row->user->member->id)).'" class="ml-2 pointer text-success">Terdaftar</a>';
                } else {
                    $buttons .= '<a href="javascript:;" class="ml-2 pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';
                }

                return $buttons;
            })
            ->rawColumns(['action', 'full_name'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Registration $model): QueryBuilder
    {
        return $model
            ->with(['user', 'user.member'])
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()->responsive(true)
            ->setTableId('registration-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave()
            ->responsive(true)
            ->stateSave(true)
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
            Column::make('created_at'),
            Column::make('full_name'),
            Column::make('type'),
            Column::make('nik'),
            Column::make('gender'),
            Column::make('birth_date'),
            Column::make('phone'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Registration_'.date('YmdHis');
    }
}
