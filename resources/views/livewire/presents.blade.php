<div class="row">
    <div class="col-12 text-center">
        <h4>PESERTA</h4>
    </div>
    @foreach ($presents as $index => $present)
    @if($present->type=='teacher')
    @continue
    @endif
    <div class="col-12 col-md-4" wire:key="present-field-{{ $present->id }}">
        <div class="card mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-4 text-center">
                        @if($present->user->member->profile_picture)
                        <img 
                        src="{{ thumbnail($present->user->member->profile_picture, 300, 400) }}"
                        class="img-fluid" />
                        @else
                        <img 
                        src="{{ asset('assets/images/default.jpg') }}"
                        class="img-fluid" />
                        @endif
                        @if($present->is_transfer)
                        <a href="{{ route('teacher.schedules.presents.remove', [$present->schedule_id, $present->id]) }}" 
                        onclick="return confirm('Yakin ingin menghapus?')"
                        class="text-danger mt-2">(hapus)</a>
                        @endif
                    </div>
                    <div class="col-8">
                        <label class="form-control">{{ $present->name() }}</label>
                        <select class="form-control mt-2" 
                        wire:change="updateStatus({{ $present->id }}, $event.target.value)">
                            @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected($status==$present->status)>@lang('app.present.status.'.$status)</option>
                            @endforeach
                        </select>
                        <input type="text" class="form-control mt-2"
                        placeholder="Tidak ada keterangan"
                         value="{{ $present->description }}" wire:blur="updateDescription({{ $present->id }}, $event.target.value)" />
                        <div wire:loading>
                            Memperbaharui...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
