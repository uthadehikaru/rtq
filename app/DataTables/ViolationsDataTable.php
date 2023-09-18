<?php

namespace App\DataTables;

use App\Models\Violation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ViolationsDataTable extends DataTable
{
    private $params = [];
    
    public function filter($params)
    {
        $this->params = $params;
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
            ->editColumn('violated_date', function ($row) {
                return $row->violated_date?->format('d M Y');
            })
            ->editColumn('paid_at', function ($row) {
                return $row->paid_at?->format('d M Y');
            })
            ->editColumn('user_id', function ($row) {
                return $row->user? $row->user->name : 'noname';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="btn-group" role="group">
                    <button id="action" type="button" class="btn btn-primary btn-sm dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action">
                        <a href="'.route('violations.edit', $row->id).'" class="dropdown-item text-warning">
                            <i class="la la-edit"></i> Ubah
                        </a>
                        <a href="javascript:;" class="dropdown-item text-danger delete" data-id="'.$row->id.'">
                            <i class="la la-trash"></i> Hapus
                        </a>
                    </div>
                </div>
                ';
            })
            ->filterColumn('user_id', function ($query, $keyword) {
                if($keyword=='noname')
                    return $query->whereNull('user_id');
                
                $query->whereRelation('user','name','like','%'.$keyword.'%');
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Violation  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Violation $model): QueryBuilder
    {
        $model = $model
        ->select('violations.*')
        ->with('user')
        ->latest('violated_date')->newQuery();

        if(isset($this->params['status'])){
            if($this->params['status']=='unpaid')
                $model->whereNull('paid_at');
                
            if($this->params['status']=='paid')
                $model->whereNotNull('paid_at');
        }

        if(isset($this->params['type'])){
            $model->where('type',$this->params['type']);
        }

        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('violations-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->stateSave()
                    ->responsive(true)
                    ->dom('Bfrtip')
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('reset'),
                        Button::make('reload'),
                    ]);
    }

    public function wait()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('violated_date')->title('tgl pelanggaran'),
            Column::make('type'),
            Column::make('user_id')->title('Nama')->responsivePriority(1),
            Column::make('description'),
            Column::make('amount')->title('nominal'),
            Column::make('paid_at')->title('diselesaikan pada')->responsivePriority(2),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
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
        return 'Violations_'.date('YmdHis');
    }
}
