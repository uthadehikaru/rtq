<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget14">
                <div class="kt-widget14__header">
                    <h3 class="kt-widget14__title">
                        Update Password
                    </h3>
                    <span class="kt-widget14__desc">
                        ubah password anda
                    </span>
                </div>
                <div class="kt-widget14__body">
                    <form wire:submit.prevent="submitPassword">
                        <div class="form-group">
                            <label>@lang('Password Lama')<span class="text-danger">*</span></label>
                            <input type="password" name="old_password" class="form-control"
                            wire:model.debounce.500ms="old_password"
                            >
                            <span class="text-danger">
                                @error('old_password') {{ $message }} @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label>@lang('Password Baru')<span class="text-danger">*</span></label>
                            <input type="password" name="new_password_confirmation" class="form-control"
                            wire:model.debounce.500ms="new_password_confirmation"
                            >
                            <span class="text-danger">
                                @error('new_password_confirmation') {{ $message }} @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label>@lang('Ulangi Password Baru')<span class="text-danger">*</span></label>
                            <input type="password" name="new_password" class="form-control"
                            wire:model.debounce.500ms="new_password"
                            >
                            <span class="text-danger">
                                @error('new_password') {{ $message }} @enderror
                            </span>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>

        <!--end:: Widgets/Profit Share-->
    </div>
</div>