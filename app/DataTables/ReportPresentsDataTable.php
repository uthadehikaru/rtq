<?php

namespace App\DataTables;

use App\Models\Present;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Query\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReportPresentsDataTable extends DataTable
{
    private $type = null;

    private $status = null;

    private $start_date = null;

    private $end_date = null;

    public function filterType($type)
    {
        $this->type = $type;
    }

    public function filterStatus($status)
    {
        $this->status = $status;
    }

    public function filterDate($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filterColumn('description', function ($query, $keyword) {
                if ($keyword == 'operan') {
                    $query->where('is_transfer', true);
                }
                if ($keyword == 'badal') {
                    $query->where('is_badal', true);
                }
            })
            ->filterColumn('user_id', function ($query, $keyword) {
                $query->whereExists(function ($query) use ($keyword) {
                    $query->selectRaw('1')
                        ->from('users')
                        ->whereColumn('presents.user_id', 'users.id')
                        ->where('users.name', 'like', '%'.$keyword.'%');
                });
            })
            ->filterColumn('batch_name', function ($query, $keyword) {
                $query->where('batches.name', 'like', '%'.$keyword.'%');
            })
            ->editColumn('schedule_id', function ($row) {
                return $row->schedule->batch->code.' - '.$row->schedule->batch->name;
            })
            ->editColumn('user_id', function ($row) {
                return $row->user->name;
            })
            ->editColumn('type', function ($row) {
                return __($row->type);
            })
            ->editColumn('status', function ($row) {
                $status = __('app.present.status.'.$row->status);
                if ($row->status == 'present' && $row->attended_at) {
                    $status .= ' '.$row->attended_at->format('H:i');
                    if ($row->leave_at) {
                        $status .= ' - '.$row->leave_at->format('H:i');
                    }
                }

                return $status;
            })
            ->editColumn('description', function ($row) {
                $description = '';
                if ($row->photo) {
                    $description .= '<p><a href="'.asset('storage/'.$row->photo).'"  data-lightbox="present-'.$row->id.'"><img src="'.asset('storage/'.$row->photo).'" width="200" class="img-fluid" /></a></p>';
                }
                if ($row->photo_out) {
                    $description .= '<p><a href="'.asset('storage/'.$row->photo_out).'"  data-lightbox="present_out-'.$row->id.'"><img src="'.asset('storage/'.$row->photo_out).'" width="200" class="img-fluid" /></a></p>';
                }
                $description .= $row->description;
                $description .= ' '.($row->type == 'teacher' && $row->is_badal ? '(Badal)' : '');
                $description .= ' '.($row->type == 'member' && $row->is_transfer ? '(Operan)' : '');

                return $description;
            })
            ->editColumn('scheduled_at', function ($row) {
                return Carbon::parse($row->scheduled_at)->format('d M Y H:i');
            })
            ->editColumn('batch_name', function ($row) {
                return $row->batch_code.' - '.$row->batch_name;
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
                $buttons .= '<a href="'.route('schedules.presents.edit', ['schedule' => $row->schedule_id, 'present' => $row->id, 'redirect' => url()->current()]).'" class="ml-2 text-warning">Ubah</a>';

                return $buttons;
            })
            ->order(function ($query) {
                if (request()->has('scheduled_at')) {
                    $query->orderBy('name', 'asc');
                }
            })
            ->rawColumns(['action', 'description'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     */
    public function query(Present $model): QueryBuilder
    {
        $model = $model
            ->selectRaw('presents.*,schedules.scheduled_at,batches.code as batch_code, batches.name as batch_name')
            ->join('schedules', 'presents.schedule_id', 'schedules.id')
            ->join('batches', 'schedules.batch_id', 'batches.id')
            ->with(['schedule', 'schedule.batch', 'user'])
            ->latest('scheduled_at');

        if ($this->type) {
            $model = $model->where('type', $this->type);
        }

        if ($this->status) {
            $model = $model->where('status', $this->status);
        }

        $model = $model->whereHas('schedule', function ($query) {
            if ($this->start_date) {
                $query->whereDate('scheduled_at', '>=', $this->start_date);
            }

            if ($this->end_date) {
                $query->whereDate('scheduled_at', '<=', $this->end_date);
            }
        });

        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()->responsive(true)
            ->setTableId('Present-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([]);
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
            Column::make('scheduled_at')->title('jadwal')->searchable(false),
            Column::make('batch_name')->title('Halaqoh'),
            Column::make('user_id')->title('Nama'),
            Column::make('type')->title('Tipe'),
            Column::make('status')->title('Status'),
            Column::make('description')->title('Keterangan'),
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
