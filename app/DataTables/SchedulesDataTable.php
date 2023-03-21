<?php

namespace App\DataTables;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SchedulesDataTable extends DataTable
{
    private $user_id = 0;

    public function setUserId($user_id)
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
            ->filterColumn('batch_id', function ($query, $keyword) {
                $sql = 'batches.name like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('teacher', function ($query, $keyword) {
                $sql = "exists(select 1 from presents 
                join users on presents.user_id=users.id
                where presents.schedule_id=schedules.id 
                and presents.type='teacher'
                and users.name like ?)";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->editColumn('scheduled_at', function ($row) {
                return $row->scheduled_at->format('d M Y');
            })
            ->editColumn('start_at', function ($row) {
                return $row->created_at->format('H:i');
            })
            ->editColumn('batch_id', function ($row) {
                return $row->batch->name;
            })
            ->addColumn('teacher', function ($row) {
                $teachers = [];
                foreach ($row->presents->where('type', 'teacher') as $teacher) {
                    $teachers[] = $teacher->user->name;
                }

                return implode(', ', $teachers);
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
                if ($this->user_id > 0) {
                    $buttons .= '<a href="'.route('teacher.schedules.detail', $row->id).'" class="text-primary">Detail</a>';
                } else {
                    $buttons .= '<a href="'.route('schedules.presents.index', $row->id).'" class="text-primary">Detail</a>';
                    $buttons .= '<a href="'.route('schedules.edit', $row->id).'" class="ml-2 text-warning">Ubah</a>';
                    $buttons .= '<a href="javascript:;" class="ml-2 pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Schedule $model): QueryBuilder
    {
        if ($this->user_id > 0) {
            $model = $model->whereRelation('presents', 'user_id', $this->user_id);
        }

        return $model
        ->join('batches', 'batches.id', 'schedules.batch_id')
        ->with('batch', 'presents', 'presents.user')
        ->withCount(['presents' => function ($query) {
            $query->where('type', 'member');
        }])
        ->latest('scheduled_at')
        ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()->responsive(true)
                    ->setTableId('Schedule-table')
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
            Column::make('scheduled_at')->title('Tanggal'),
            Column::make('batch_id')->title('Halaqoh'),
            Column::make('teacher')->title('Pengajar'),
            Column::make('start_at')->title('Mulai'),
            Column::make('place')->title('Tempat'),
            Column::make('presents_count')->title('Peserta')->searchable(false),
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
