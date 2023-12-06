<div class="row">
    <div class="col-md-2">
        <h3 class="mt-2">{{Auth::user()->name}} {{ $present->is_badal?'(Guru Pengganti)':'' }}</h3>
        @if($present->photo)
        <a href="{{ asset('storage/'.$present->photo) }}" target="_BLANK"><img src="{{ asset('storage/'.$present->photo) }}" class="img-fluid" /></a>
        @endif
    </div>
    <div class="col-md-8">
        <div class="row mt-2">
            <div class="col-12 col-md-6">
                <label>Mulai Kelas</label>
                <input type="time" class="form-control"
                name="start_at"  value="{{ $schedule->start_at?->format("H:i") }}"  disabled="disabled" />
            </div>
            <div class="col-12 col-md-6">
                <label>Selesai Kelas</label>
                <input type="time" class="form-control"
                name="end_at"  value="{{ $schedule->end_at?->format("H:i") }}"  disabled="disabled" />
                @if(!$schedule->end_at)
                <span class="text-help">Saat "Tutup Kelas", Jam selesai akan otomatis tercatat</span>
                @endif
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 col-md-6">
                <label>Tempat</label>
                <input type="text" class="form-control"
                wire:blur="updatePlace({{ $schedule->id }}, $event.target.value)"
                name="place"  value="{{ $schedule->place }}" required />
            </div>
            <div class="col-12 col-md-6">
                <label>Status</label>
                <select class="form-control" name="status[{{$present->id}}]" disabled>
                    @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected($status==$present->status)>@lang('app.present.status.'.$status)</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 col-md-6">
                <label>Jam Kehadiran</label>
                <input type="time" class="form-control"
                name="attended_at[{{$present->id}}]"  value="{{ $present->attended_at?->format('H:i') }}"  disabled="disabled" />
            </div>
            <div class="col-12 col-md-6">
                <label>Jam Pulang</label>
                <input type="time" class="form-control"
                name="leave_at[{{$present->id}}]"  value="{{ $present->leave_at?->format('H:i') }}"  disabled="disabled" />
            </div>
            <div class="col-12">
                <label>Keterangan</label>
                <input type="text" class="form-control"
                placeholder="Tidak ada keterangan"
                wire:blur="updateDescription({{ $present->id }}, $event.target.value)"
                name="description[{{$present->id}}]"  value="{{ $present->description }}" />
            </div>
        </div>
    </div>
    <div class="col-md-2">
        @if($present->photo_out)
        <h3 class="mt-2">Absen Keluar</h3>
        <a href="{{ asset('storage/'.$present->photo_out) }}" target="_BLANK"><img src="{{ asset('storage/'.$present->photo_out) }}" class="img-fluid" /></a>
        @endif
    </div>
</div>