<?php

namespace App\DataTables;

use App\Models\PaymentDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaymentDetailsDataTable extends DataTable
{
    private $period_id = 0;

    public function setPeriod($period_id)
    {
        $this->period_id = $period_id;
    }

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
            ->editColumn('member_id', function ($row) {
                return $row->member->full_name;
            })
            ->editColumn('payment_id', function ($row) {
                return $row->payment->amount;
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group" role="group">
                <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Aksi
                </button>
                <div class="dropdown-menu" aria-labelledby="action">';
                $buttons .= '<a href="'.route('payments.edit', $row->payment_id).'" class="dropdown-item text-warning">Ubah</a>';
                $buttons .= '</div></div>';

                return $buttons;
            })
            ->rawColumns(['action', 'attachment', 'status', 'member'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     */
    public function query(PaymentDetail $model): QueryBuilder
    {
        $model = $model
            ->with(['payment', 'period', 'member'])
            ->newQuery();
        if ($this->period_id) {
            $model = $model->where('period_id', $this->period_id);
        }

        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()->responsive(true)
            ->setTableId('PaymentDetail-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave()
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
            Column::make('created_at')->title('Tanggal'),
            Column::make('member_id')->title('Member'),
            Column::make('payment_id')->title('Amount'),
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
