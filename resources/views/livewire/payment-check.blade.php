<div>
    <div class="kt-portlet kt-portlet--height-fluid">
        <div class="kt-widget14">
            <div class="kt-widget14__header">
                <h3 class="kt-widget14__title">
                    Pembayaran belum dikonfirmasi
                </h3>
                <span class="kt-widget14__desc">
                    tekan tombol confirm untuk mengkonfirmasi
                </span>
            </div>
            <div class="kt-widget14__body">
            <table class="table">
                <tr>
                    <th>Tanggal</th>
                    <th>Anggota</th>
                    <th>Nominal</th>
                    <th>Action</th>
                </tr>
                @foreach($payments as $payment)
                <tr id="{{ $payment->id }}">
                    <td>{{ $payment->created_at->format('d M Y') }}</td>
                    <td>
                        @foreach ($payment->details as $detail)
                        <p>{{ $detail->member->full_name.' periode '.$detail->period->name }}</p>
                        @endforeach
                    </td>
                    <td>@money($payment->amount)
                        <br/>{{ $payment->payment_method }}
                        @if($payment->attachment)
                        - <a href="{{ asset('storage/'.$payment->attachment) }}" data-lightbox="attachment-{{$payment->id }}">Bukti Transfer</a>
                        @endif
                    </td>
                    <td><button id="payment-{{ $payment->id }}" 
                    class="btn btn-primary btn-sm"
                    wire:click="confirm({{$payment->id}})"
                    wire:loading.attr="disabled" wire:target="confirm({{$payment->id}})"
                    >
                        <div wire:loading.remove wire:target="confirm({{$payment->id}})">
                        <i class="fas fa-check"></i>
                        </div>
                        <div wire:loading wire:target="confirm({{$payment->id}})">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                    </button></td>
                </tr>
                @endforeach
            </table>
            </div>
        </div>
    </div>
</div>
@section('breadcrumbs')
<a href="{{ route('payments.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Payment")</a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
Konfirmasi
</span>
@endsection