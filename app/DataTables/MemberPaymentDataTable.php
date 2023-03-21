<?php

namespace App\DataTables;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Period;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MemberPaymentDataTable extends DataTable
{
    public $periods;

    public $inactive = false;

    public function __construct()
    {
        $periods = Period::orderBy('start_date')
        ->get();

        $this->periods[] = $periods->where('name', 'Registrasi')->first();

        foreach ($periods as $period) {
            if ($period->name == 'Registrasi') {
                continue;
            }

            $this->periods[] = $period;
        }
    }

    public function inactive()
    {
        $this->inactive = true;
    }

    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $datatable = (new EloquentDataTable($query))
            ->addColumn('batches', function ($row) {
                $batch = $row->batches->first();
                $value = 'inaktif';
                if ($batch) {
                    $value = $batch->name;
                } else {
                    $value .= ' '.$row->leave_at?->format('d M Y');
                }

                return $value;
            })
            ->editColumn('registration_date', function ($row) {
                return $row->registration_date?->format('d M Y');
            })
            ->setRowId('id');

        $rawColumns = [];
        foreach ($this->periods as $period) {
            $datatable->addColumn('period_'.$period->id, function ($row) use ($period) {
                $status = '';

                $payment = Payment::wherehas('details', function ($query) use ($period, $row) {
                    $query->where('member_id', $row->id)
                    ->where('period_id', $period->id);
                })->first();
                if ($payment) {
                    $status = $payment->status;
                } elseif ($row->status) {
                    $status = 'free';
                } else {
                    return '-';
                }

                $badges = [
                    'paid' => 'success',
                    'new' => 'warning',
                    'free' => 'info',
                ];

                return '<span class="badge badge-'.$badges[$status].'">'.__('app.payment.status.'.$status).'</span>';
            });
            $rawColumns[] = 'period_'.$period->id;
        }
        $datatable->rawColumns($rawColumns);

        return $datatable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\MemberPayment  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Member $model): QueryBuilder
    {
        $model = $model
        ->with(['batches'])
        ->newQuery();

        if ($this->inactive) {
            $model->doesntHave('batches');
        } else {
            $model->has('batches');
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
        return $this->builder()->responsive(true)
                    ->setTableId('memberpayment-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1, 'asc')
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
        $columns = [
            Column::make('registration_date')->title('Tgl Gabung'),
            Column::make('full_name')->title('Nama'),
            Column::make('batches')->title('Halaqoh'),
        ];

        foreach ($this->periods as $period) {
            $columns[] = Column::make('period_'.$period->id)->title($period->name);
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'MemberPayment_'.date('YmdHis');
    }
}
