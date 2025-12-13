<div class="row">
    <div class="col">

        <x-validation/>

        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        @lang('Tambah Jadwal')
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('schedules.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            <form class="kt-form" wire:submit.prevent="store">
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Scheduled At')</label>
                            <input type="date" name="scheduled_at" class="form-control"
                            wire:model="scheduled_at"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Batch')</label>
                            <select class="form-control batches" wire:model.live="batch_id" required>
                                <option value="">@lang('Select Batch')</option>
                                @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->course->name }} {{ $batch->name }} ({{ $batch->start_time?->format('H:i') }} @ {{ $batch->place }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Mulai')</label>
                            <input type="time" name="start_at" class="form-control"
                            wire:model="start_at">
                        </div>
                        <div class="form-group">
                            <label>@lang('Tempat')</label>
                            <input type="text" name="place" class="form-control"
                            wire:model="place">
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary" wire:loading.class="disabled">@lang('Submit')</button>
                        <button type="reset" class="btn btn-secondary">@lang('Cancel')</button>
                    </div>
                </div>
            </form>

            <!--end::Form-->
        </div>

        <!--end::Portlet-->
    </div>
</div>
