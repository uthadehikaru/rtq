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
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y H:i');
            })
            ->editColumn('batches', function ($row) {
                return $row->batches->count() ? $row->batches->pluck('name')->join(', ') : 'Inaktif';
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
                    return '<a href="'.asset('storage/'.$row->profile_picture).'" target="_blank"><img src="'.asset('storage/'.$row->profile_picture).'" height="50" /></a>';
                }
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
                $buttons .= '<a href="'.route('members.edit', $row->id).'" class="text-warning">Ubah</a>';
                $buttons .= '<a href="javascript:;" class="ml-2 pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';

                return $buttons;
            })
            ->rawColumns(['action', 'profile_picture', 'member_no'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Member $model): QueryBuilder
    {
        return $model
        ->with('batches', 'batches.course')
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
            Column::make('created_at')->title('Tgl Masuk'),
            Column::make('member_no')->title('No Anggota'),
            Column::make('nik')->title('NIK'),
            Column::make('full_name')->title('Nama'),
            Column::make('gender')->title('Jenis Kelamin'),
            Column::make('birth_date')->title('Tgl Lahir'),
            Column::make('school')->title('Sekolah'),
            Column::make('level')->title('Level'),
            Column::make('batches')->title('Halaqoh')->searchable(false),
            Column::make('status')->title('Status'),
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
        return 'Registration_'.date('YmdHis');
    }
}
