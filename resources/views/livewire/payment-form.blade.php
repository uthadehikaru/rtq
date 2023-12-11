<div>
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
        <form class="kt-form" wire:submit.prevent="save">
            @csrf
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="form-group">
                        <label>@lang('Nominal')</label>
                        <input type="number" name="amount" class="form-control" placeholder="@lang('Nominal')"
                        wire:model.defer="payment.amount"
                        required>
                    </div>
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_method" 
                            id="transfer" value="transfer"
                            wire:model="payment.payment_method">
                            <label class="form-check-label" for="transfer">transfer</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_method" 
                            id="amplop" value="amplop"
                            wire:model="payment.payment_method">
                            <label class="form-check-label" for="amplop">amplop</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Dikonfirmasi pada</label>
                        <input type="date" name="paid_at" class="form-control"
                        wire:model.defer="payment.paid_at">
                    </div>
                    <table class="table">
                        <tr>
                            <th>Period</th>
                            <th>Member</th>
                            <th>Aksi</th>
                        </tr>
                        @foreach ($payment->details as $i=>$detail)
                            <tr>
                                <td>
                                    <select class="form-control" wire:model="payment.details.{{$i}}.period_id">
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ $detail->member->full_name }}</td>
                                <td><button type="button" class="btn btn-danger btn-sm" wire:click="deleteLine({{ $detail->id }})"
                                wire:loading.class="disabled">Hapus</button></td>
                            </tr>
                        @endforeach
                            <tr>
                                <td>
                                    <select class="form-control" wire:model="period_id">
                                        <option value="">-- Pilih Periode --</option>
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('period_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td>
                                    <div wire:ignore>
                                    <select class="form-control" id="member_id"></select>
                                    </div>
                                    @error('member_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td><button type="button" class="btn btn-info btn-sm"
                                wire:click="add"
                                wire:loading.class="disabled">Tambah</button></td>
                            </tr>
                    </table>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary" wire:loading.class="disabled">@lang('Submit')</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-warning">@lang('Cancel')</a>
                </div>
            </div>
        </form>

        <!--end::Form-->
    </div>

    <!--end::Portlet-->
</div>
@push('scripts')
    <script>
        Livewire.on('line-deleted', function(){
            window.location.reload();
        });
        Livewire.on('line-added', function(){
            window.location.reload();
        });
        $(document).ready(function() {
            $('#member_id').select2({
                placeholder: "@lang('Pilih Peserta')",
                ajax: {
                    url: '{{ route('api.batchmembers') }}',
                    dataType: 'json',
                    processResults: function (data) {
                        return {
                            results: data.items
                        };
                    }
                }
            });     

            $('#member_id').change(function(){
                var member_id = $(this).val();
                @this.set('member_id', member_id);
            });
        });
    </script>
@endpush