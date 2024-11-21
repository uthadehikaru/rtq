<?php

namespace App\DataTables;

use App\Models\Member;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MembersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filterColumn('batches', function ($query, $keyword) {
                if ($keyword == 'inaktif') {
                    $query->doesntHave('batches');
                }
            })
            ->editColumn('registration_date', function ($row) {
                return $row->registration_date?->format('d M Y');
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at?->format('d M Y H:i:s');
            })
            ->editColumn('batches', function ($row) {
                return $row->batches->count() ? $row->batches->pluck('name')->join(', ') : 'Inaktif '.$row->leave_at?->format('d M Y');
            })
            ->editColumn('category', function ($row) {
                return $row->course?->type;
            })
            ->editColumn('gender', function ($row) {
                return __($row->gender);
            })
            ->editColumn('birth_date', function ($row) {
                return $row->birth_date?->format('d M Y');
            })
            ->editColumn('member_no', function ($row) {
                return '<a href="'.route('members.cards', $row->member_no).'" target="_blank">'.$row->member_no.'</a>';
            })
            ->editColumn('profile_picture', function ($row) {
                if ($row->profile_picture) {
                    return '<a href="'.asset('storage/'.$row->profile_picture).'?v='.$row->created_at->format('YmdHis').'" data-lightbox="profile-'.$row->id.'"><img src="'.thumbnail($row->profile_picture, 300, 400).'" height="50" /></a>';
                }
            })
            ->editColumn('is_acceleration', function ($row) {
                return $row->is_acceleration ? 'Akselerasi' : 'Non Akselerasi';
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
                $buttons .= '<a href="'.route('members.edit', $row->id).'" class="text-warning">Ubah</a>';

                return $buttons;
            })
            ->rawColumns(['action', 'profile_picture', 'member_no'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     */
    public function query(Member $model): QueryBuilder
    {
        return $model
            ->with('batches', 'batches.course')
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
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('registration_date')->title('Tgl Masuk'),
            Column::make('member_no')->title('No Anggota'),
            Column::make('nik')->title('NIK'),
            Column::make('full_name')->title('Nama'),
            Column::make('gender')->title('Jenis Kelamin'),
            Column::make('batches')->title('Halaqoh')
                ->sortable(false),
            Column::make('category')->title('Kategori')
                ->sortable(false)
                ->searchable(false),
            Column::make('status')->title('Status'),
            Column::make('profile_picture')->title('Foto'),
            Column::make('is_acceleration')->title('Akselerasi'),
            Column::make('updated_at'),
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
