@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('payments.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Pembayaran") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $payment?'Ubah':'Tambah' }}
</span>
@endsection
@section('content')
<div class="row">
    <div class="col">

        <x-validation/>

        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Form Pembayaran
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('payments.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($payment)
            <form class="kt-form" method="POST" action="{{ route('payments.update', $payment->id) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('payments.store') }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Nominal')</label>
                            <input type="number" name="amount" class="form-control" placeholder="@lang('Nominal')"
                            value="{{ old('amount', $payment?$payment->amount:0) }}"
                            required>
                        </div>
                        <table class="table">
                            <tr>
                                <th>Period</th>
                                <th>Member</th>
                                <th>Aksi</th>
                            </tr>
                            @foreach ($payment->details as $detail)
                                <tr>
                                    <td>{{ $detail->period->name }}</td>
                                    <td>{{ $detail->member->full_name }}</td>
                                    <td>
                                        <a href="javascript:;" class="text-danger delete" data-id="{{ $detail->id }}">
                                            <i class="la la-trash"></i> @lang('Delete')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                        <button type="reset" class="btn btn-secondary">@lang('Cancel')</button>
                    </div>
                </div>
            </form>

            <!--end::Form-->
        </div>

        <!--end::Portlet-->
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
jQuery(document).ready(function () {    
    $('.kt-select2').select2({
        placeholder: "@lang('Pilih Anggota')"
    });

    $(document).on("click", ".delete", function() { 
        if(confirm("@lang('Are you sure?')")) {
            var id= $(this).data('id');
            var url = "{{ route('payments.details.index', $payment->id) }}";
            var dltUrl = url+"/"+id;
            $.ajax({
                url: dltUrl,
                type: "DELETE",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}'
                },
                success: function(dataResult){
                    if(dataResult.statusCode==200){
                        alert('@lang('Deleted Successfully')');
                        location.reload(true);
                    }
                }
            });
        }
	});
});
</script>
@endpush