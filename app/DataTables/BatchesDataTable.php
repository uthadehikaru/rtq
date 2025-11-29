<?php

namespace App\DataTables;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BatchesDataTable extends DataTable
{
    private $course_id = 0;

    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                $buttons = '
                <div class="btn-group" role="group">
                    <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action">
                        <a href="'.route('courses.batches.batchmembers.index', [$row->course_id, $row->id]).'" class="dropdown-item text-primary">
                            <i class="la la-list"></i> Detail
                        </a>
                        <a href="'.route('courses.batches.edit', [$row->course_id, $row->id]).'" class="dropdown-item text-warning">
                            <i class="la la-edit"></i> Ubah
                        </a>
                        <a href="javascript:;" class="dropdown-item text-danger delete" data-id="'.$row->id.'">
                            <i class="la la-trash"></i> Hapus
                        </a>
                    </div>
                </div>';

                return $buttons;
            })
            ->addColumn('teachers', function ($row) {
                return $row->teachers->filter(fn ($value, $key) => ! $value->pivot->is_member)->implode('name', ' , ');
            })
            ->editColumn('start_time', function ($row) {
                return $row->start_time?->format('H:i');
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? 'Ya' : 'Tidak';
            })
            ->editColumn('members_count', function ($row) {
                if ($row->course->type == 'Talaqqi Pengajar') {
                    return $row->teachers->filter(fn ($value, $key) => $value->pivot->is_member)->count();
                }

                return $row->members_count;
            })
            ->addColumn('tipe', function ($row) {
                return $row->size_type;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Batch $model): QueryBuilder
    {
        return $model
            ->active()
            ->withCount('members')
            ->with(['teachers', 'course'])
            ->where('course_id', $this->course_id)
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('batches-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave()
            ->responsive(true)
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
            Column::make('code')->title('Kode'),
            Column::make('name')->title('Nama'),
            Column::make('description')->title('Jadwal'),
            Column::make('start_time')->title('Waktu'),
            Column::make('place')->title('Tempat'),
            Column::make('is_active')->title('Aktif'),
            Column::make('teachers')->title('Pengajar')->searchable(false),
            Column::make('members_count')->title('Peserta')->searchable(false),
            Column::make('tipe')->title('Tipe')->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    public function setCourseId($course_id)
    {
        $this->course_id = $course_id;
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Batches_'.date('YmdHis');
    }
}
