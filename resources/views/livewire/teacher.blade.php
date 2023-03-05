<div class="row">
    <div class="col-md-2">
        <h3 class="mt-2">{{Auth::user()->name}} {{ $present->is_badal?'(Guru Pengganti)':'' }}</h3>
        @if($present->photo)
        <a href="{{ asset('storage/'.$present->photo) }}" target="_BLANK"><img src="{{ asset('storage/'.$present->photo) }}" class="img-fluid" /></a>
        @endif
    </div>
    <div class="col-md-10">
        <div class="row mt-2">
            <div class="col-12 col-md-4">
                <label>Mulai Kelas</label>
                <input type="time" class="form-control"
                name="start_at"  value="{{ $schedule->start_at?->format("H:i") }}"  disabled="disabled" />
            </div>
            <div class="col-12 col-md-4">
                <label>Selesai Kelas</label>
                <input type="time" class="form-control"
                name="end_at"  value="{{ $schedule->end_at?->format("H:i") }}"  disabled="disabled" />
                @if(!$schedule->end_at)
                <span class="text-help">Saat "Tutup Kelas", Jam selesai akan otomatis tercatat</span>
                @endif
            </div>
            <div class="col-12 col-md-4">
                <label>Tempat</label>
                <input type="text" class="form-control"
                wire:blur="updatePlace({{ $schedule->id }}, $event.target.value)"
                name="place"  value="{{ $schedule->place }}" required />
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 col-md-4">
                <label>Status</label>
                <select class="form-control" name="status[{{$present->id}}]" disabled>
                    @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected($status==$present->status)>@lang('app.present.status.'.$status)</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label>Jam Kehadiran</label>
                <input type="time" class="form-control"
                name="attended_at[{{$present->id}}]"  value="{{ $present->attended_at?->format('H:i') }}"  disabled="disabled" />
            </div>
            <div class="col-12 col-md-4">
                <label>Keterangan</label>
                <input type="text" class="form-control"
                placeholder="Tidak ada keterangan"
                wire:blur="updateDescription({{ $present->id }}, $event.target.value)"
                name="description[{{$present->id}}]"  value="{{ $present->description }}" />
            </div>
        </div>
    </div>
</div>