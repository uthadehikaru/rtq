<div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                {{ $violations->count() }} @lang('Iqob Belum Dibayar')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('iqob.index') }}" class="btn btn-primary btn-sm">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body table-responsive">
        <table class="table table-striped">
            <tr>
                <th>Tgl</th>
                <th>Keterangan</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
            @foreach ($violations as $violation)
                <tr>
                    <td>{{ $violation->violated_date?->format('d/M/Y') }}</td>
                    <td>{{ $violation->description }}</td>
                    <td>{{ $violation->amount }}</td>
                    <td>{{ $violation->paid_at?'Selesai':'Belum dibayar' }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>