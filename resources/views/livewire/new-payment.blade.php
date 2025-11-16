<div>
    <div class="modal fade" id="paymentModal" aria-labelledby="paymentModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">
                    {{ $is_member ? 'Konfirmasi Pembayaran Peserta' : 'Input Pembayaran' }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="paymentMessage"></div>
                <form id="paymentForm" wire:submit.prevent="savePayment">
                    <input type="hidden" name="is_member" value="{{ $is_member }}">
                    <div class="form-group">
                        <label class="col-form-label">@lang('Period')</label>
                        <div wire:ignore>
                            <select class="form-control kt-select-period" name="period_ids[]" multiple>
                                @foreach($periods as $period)
                                <option value="{{ $period->id }}">{{ $period->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('period_ids') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Daftar Peserta</label>
                        <div wire:ignore>
                            <select class="form-control kt-select-member" name="members[]" multiple>
                                <option></option>
                            </select>
                        </div>
                        @error('members') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Total Transfer</label>
                        <div>
                            <input class="form-control" id="total" type="number" name="total" wire:model.lazy="total">
                        </div>
                        @error('total') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if(!$is_member)
                    <div class="form-group">
                        <label class="col-form-label">Keterangan</label>
                        <div>
                            <input class="form-control" id="description" type="text" name="description" wire:model="description">
                        </div>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Metode Pembayaran</label>
                        <div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_method" 
                            id="transfer" value="transfer" wire:model.defer="payment_method">
                            <label class="form-check-label" for="transfer">transfer</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_method" 
                            id="amplop" value="amplop" wire:model.defer="payment_method">
                            <label class="form-check-label" for="amplop">amplop</label>
                            </div>
                        </div>
                        @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Tanggal Konfirmasi</label>
                        <div>
                            <input class="form-control" id="paid_at" type="date" name="paid_at" wire:model.lazy="paid_at">
                        </div>
                        @error('paid_at') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="col-form-label">Bukti Transfer</label>
                        <div>
                            <input class="form-control" type="file" name="attachment" 
                            wire:model="attachment" accept="image/*">
                        </div>
                        @error('attachment') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="alert alert-warning">
                        <p>
                            <i class="la la-info-circle"></i>
                            Dapatkan notifikasi pembayaran melalui WhatsApp dengan melengkapi nomor telepon di profil anda. <a href="{{ route('dashboard') }}" class="text-primary">klik disini</a>
                        </p>
                    </div>
                    <button type="submit" class="btn btn-primary" wire:target="savePayment" wire:loading.attr="disabled">Simpan
                    </button>
                    @if($is_member)
                    <a href="{{ route('home') }}" class="btn btn-secondary">Kembali</a>
                    @endif
                </form>
            </div>
        </div>
    </div>
    </div>
</div>
@push('scripts')
    <script>
	$(document).ready(function(){
        $.fn.modal.Constructor.prototype._enforceFocus = function() {};

        $('.kt-select-period').select2({
			placeholder: "Pilih Periode",
            width: '100%',
        });

        $('.kt-select-member').select2({
			placeholder: "Pilih Peserta",
            width: '100%',
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
        
        $('.kt-select-period').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('period_ids', data);
        });

        $('.kt-select-member').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('members', data);
        });

        Livewire.on('paymentCreated', function(event){
            $('form#paymentForm').trigger("reset");
            if(!@this.is_member){
                $('form#paymentForm select').trigger("change");
                $('#paymentModal').modal('hide');
                $('#Payment-table').DataTable().ajax.reload();
            }
        })

        @if($is_member)
        $('#paymentModal').on('hidden.bs.modal', function () {
            window.location.href = '{{ route('home') }}';
        })
        @endif
    });
    </script>
@endpush