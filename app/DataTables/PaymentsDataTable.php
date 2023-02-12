<?php

namespace App\DataTables;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaymentsDataTable extends DataTable
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
            ->filterColumn('status', function ($query, $keyword) {
                if (Str::lower($keyword) == 'baru') {
                    $query->where('status', 'new');
                } elseif (Str::lower($keyword) == 'lunas') {
                    $query->where('status', 'paid');
                }
            })
            ->filterColumn('member', function ($query, $keyword) {
                $query->whereExists(function ($query) use ($keyword) {
                    $query->select(DB::raw(1))
                    ->from('payment_details')
                    ->join('members', 'members.id', 'payment_details.member_id')
                    ->whereColumn('payments.id', 'payment_details.payment_id')
                    ->where('members.full_name', 'LIKE', '%'.$keyword.'%');
                });
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y H:i');
            })
            ->editColumn('status', function ($row) {
                return '<span class="kt-badge '.($row->status == 'new' ? 'kt-badge--info' : 'kt-badge--success').' kt-badge--inline kt-badge--pill">'.($row->status == 'new' ? 'Baru' : 'Lunas').'</span>';
            })
            ->addColumn('member', function ($row) {
                $val = '';
                foreach ($row->details as $detail) {
                    $val .= '<p>'.$detail->member->full_name.' periode '.$detail->period->name.'</p>';
                }

                return $val;
            })
            ->editColumn('attachment', function ($row) {
                if ($row->attachment) {
                    return '<a href="'.asset('storage/'.$row->attachment).'" target="_blank">Lampiran</a>';
                }
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
                if ($row->status == 'new') {
                    $buttons .= '<a href="'.route('payments.confirm', $row->id).'" class="text-primary">Confirm</a>';
                }
                $buttons .= '<a href="'.route('payments.edit', $row->id).'" class="ml-2 text-warning">Ubah</a>';
                $buttons .= '<a href="javascript:;" class="ml-2 pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';

                return $buttons;
            })
            ->rawColumns(['action', 'attachment', 'status', 'member'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Registration  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Payment $model): QueryBuilder
    {
        return $model
        ->with(['details.member', 'details.batch', 'details.period'])
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
                    ->setTableId('Payment-table')
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
            Column::make('created_at')->title('Tanggal'),
            Column::make('member')->title('Anggota'),
            Column::make('amount')->title('Nominal'),
            Column::make('status'),
            Column::make('paid_at')->title('Dibayar pada'),
            Column::make('attachment')->title('Lampiran')->searchable(false),
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
