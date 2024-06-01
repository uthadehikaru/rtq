<?php

namespace App\DataTables;

use App\Models\PaymentDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MemberPaymentDataTable extends DataTable
{
    private $period_id = 0;
    private $member_id = 0;

    public function setPeriod($period_id)
    {
        $this->period_id = $period_id;
    }

    public function setMember($member_id)
    {
        $this->member_id = $member_id;
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
            ->editColumn('created_at', function($row){
                return $row->created_at->format('d M Y H:i');
            })
            ->editColumn('member_id', function($row){
                return $row->member->full_name;
            })
            ->editColumn('payment_id', function($row){
                return $row->payment->amount;
            })
            ->editColumn('payment.status', function($row){
                return __('app.payment.status.'.$row->payment->status);
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PaymentDetail $model): QueryBuilder
    {
        $model = $model
        ->with(['payment','period','member'])
        ->newQuery();
        if($this->period_id)
            $model = $model->where('period_id', $this->period_id);
        if($this->member_id)
            $model = $model->where('member_id', $this->member_id);
        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
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
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('created_at')->title('Tanggal'),
            Column::make('period.name')->title('Periode'),
            Column::make('member_id')->title('Member'),
            Column::make('payment_id')->title('Amount'),
            Column::make('payment.status')->title('Status'),
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
