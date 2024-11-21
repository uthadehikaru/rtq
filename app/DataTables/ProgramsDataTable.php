<?php

namespace App\DataTables;

use App\Models\Program;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProgramsDataTable extends DataTable
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
                return $row->created_at->diffForHumans();
            })
            ->editColumn('published_at', function ($row) {
                return $row->published_at ? 'Ya' : 'Tidak';
            })
            ->addColumn('action', function ($row) {
                $buttons = '
                <div class="btn-group" role="group">
                    <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action">
                        <a href="'.route('programs.show', $row->id).'" class="dropdown-item text-primary">
                            '.($row->published_at ? 'Sembunyikan' : 'Tayangkan').'
                        </a>
                        <a href="'.route('programs.edit', $row->id).'" class="dropdown-item text-warning">
                            <i class="la la-edit"></i> Ubah
                        </a>
                        <a href="javascript:;" class="dropdown-item text-danger delete" data-id="'.$row->id.'">
                            <i class="la la-trash"></i> Hapus
                        </a>
                    </div>
                </div>';

                return $buttons;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Program $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()->responsive(true)
            ->setTableId('Programs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave()
                    //->dom('Bfrtip')
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
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
            Column::make('title'),
            Column::make('amount'),
            Column::make('qty'),
            Column::make('published_at')->title('Ditayangkan'),
            Column::make('action'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Programs_'.date('YmdHis');
    }
}
