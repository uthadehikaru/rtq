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
            ->editColumn('paid_at', function ($row) {
                return $row->paid_at?->format('d M Y');
            })
            ->editColumn('amount', function ($row) {
                return $row->formattedAmount();
            })
            ->editColumn('status', function ($row) {
                return '<span class="kt-badge '.($row->status == 'new' ? 'kt-badge--info' : 'kt-badge--success').' kt-badge--inline kt-badge--pill">'.($row->status == 'new' ? 'Baru' : 'Lunas').'</span>';
            })
            ->addColumn('member', function ($row) {
                $keyword = $this->request->keyword();
                $val = '';
                foreach ($row->details as $detail) {
                    $class = '';
                    if (Str::contains($detail->member->full_name, $keyword, true)) {
                        $class = 'text-danger';
                    }

                    $val .= '<p class="'.$class.'">'.$detail->member->full_name.' periode '.$detail->period->name.'</p>';
                }

                return $val;
            })
            ->editColumn('attachment', function ($row) {
                if ($row->attachment) {
                    return '<a href="'.asset('storage/'.$row->attachment).'" data-lightbox="attachment-'.$row->id.'">Lampiran</a>';
                }
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group" role="group">
                <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Aksi
                </button>
                <div class="dropdown-menu" aria-labelledby="action">';
                if ($row->status == 'new') {
                    $buttons .= '<a href="'.route('payments.confirm', $row->id).'" class="dropdown-item text-primary">Confirm</a>';
                }
                $buttons .= '<a href="'.route('payments.edit', $row->id).'" class="dropdown-item text-warning">Ubah</a>';
                $buttons .= '<a href="javascript:;" class="dropdown-item pointer text-danger delete" data-id="'.$row->id.'">Hapus</a>';
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
        return $this->builder()->responsive(true)
                    ->setTableId('Payment-table')
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
            Column::make('member')->title('Anggota'),
            Column::make('amount')->title('Nominal'),
            Column::make('payment_method')->title('via'),
            Column::make('status'),
            Column::make('paid_at')->title('Dikonfirmasi pada'),
            Column::make('attachment')->title('Lampiran')->searchable(false),
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
        return 'Registration_'.date('YmdHis');
    }
}
