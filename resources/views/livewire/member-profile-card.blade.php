<div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                @lang('Biodata Peserta') - {{ $member->full_name }}
            </h3>
            <div class="spinner-border" role="status" wire:loading.delay>
            <span class="sr-only">Loading...</span>
            </div>
        </div>
        
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('update-password') }}"
                    class="btn btn-primary">Ubah Password</a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="row">
            <div class="col-4 text-center">
                <img src="{{ $image }}" alt="profile picture"
                    class="img-fluid" />
            </div>
            <div class="col-8">
                <form wire:submit.prevent="simpan">
                    <input type="hidden" name="_method" value="PUT" />
                    <div class="form-group">
                        <label>@lang('Nama Panggilan')<span class="text-danger">*</span></label>
                        <input type="text" name="short_name" class="form-control"
                        wire:model.lazy="member.short_name"
                        value="{{ old('short_name', $member?$member->short_name:'') }}"
                        >
                        <span class="text-danger">
                            @error('member.short_name') {{ $message }} @enderror
                        </span>
                    </div>
                    <div class="form-group">
                        <label>@lang('Email')<span class="text-danger">*</span></label>
                        <input type="text" name="email" class="form-control"
                        wire:model.lazy="member.email"
                        value="{{ old('email', $member?$member->email:'') }}"
                        >
                        <span class="text-danger">
                            @error('member.email') {{ $message }} @enderror
                        </span>
                    </div>
                    <div class="form-group">
                        <label>@lang('No Telp')<span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control"
                        wire:model.lazy="member.phone"
                        value="{{ old('phone', $member?$member->phone:'') }}"
                        >
                        <span class="text-danger">
                            @error('member.phone') {{ $message }} @enderror
                        </span>
                    </div>
                    <div class="form-group">
                        <label>@lang('Alamat')</label>
                        <textarea name="address" class="form-control"
                        wire:model.lazy="member.address"
                        >{{ old('address', $member?$member->address:'') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>@lang('Kodepos')</label>
                        <input type="text" name="postcode" class="form-control"
                        wire:model.lazy="member.postcode"
                        value="{{ old('postcode', $member?$member->postcode:'') }}"
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>