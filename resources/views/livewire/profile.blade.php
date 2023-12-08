<div class="row justify-content-center">
    <div class="col-md-4">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget14">
                <div class="kt-widget14__header">
                    <h3 class="kt-widget14__title">
                        Ubah Profile
                    </h3>
                    <span class="kt-widget14__desc">
                        data pribadi anda
                    </span>
                </div>
                <div class="kt-widget14__body">
                    <form wire:submit="update">
                        <div class="form-group">
                            <label>@lang('Nama')<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" wire:model.blur="user.name"
                            value="{{ $user->name }}">
                            <span class="text-danger">
                                @error('name') {{ $message }} @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" wire:model.blur="user.email"
                            value="{{ $user->email }}">
                            <span class="text-danger">
                                @error('email') {{ $message }} @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_notify"
                                wire:model.live="user.is_notify" value="1" {{ $user->is_notify?'checked':'' }}>
                                <span class="form-check-label">Kirim Notifikasi</span>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary" wire:loading.class="disabled">Update</button>
                        <a href="{{ route('update-password') }}" class="btn btn-outline">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>

        <!--end:: Widgets/Profit Share-->
    </div>
</div>