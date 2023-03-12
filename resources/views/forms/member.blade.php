@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('members.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Members") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $title }}
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
                        {{ $title }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('members.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($member)
            <form class="kt-form" method="POST" action="{{ route('members.update', $member->id) }}" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('No Anggota')</label>
                            <input type="text" name="member_no" class="form-control"
                            value="{{ old('member_no', $member?$member->member_no:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Tanggal Masuk')</label>
                            <input type="date" name="registration_date" class="form-control"
                            value="{{ old('registration_date', $member?$member->registration_date?->format('Y-m-d'):'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Halaqoh')</label>
                            <select name="batch_id[]" class="form-control kt-select2" multiple>
                                <option value="">-- Inaktif --</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}" @selected($member && $member->batch() && $member->batch()->id==$batch->id)>{{ $batch->course->name }} - {{ $batch->name }} ({{ $batch->members_count }} anggota)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('NIK')<span class="text-danger">*</span></label>
                            <input type="text" name="nik" class="form-control" placeholder="Nomor Induk Kependudukan"
                            value="{{ old('nik', $member?$member->nik:'') }}"
                            required
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Full Name')<span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" placeholder="@lang('Enter') @lang('Full Name')"
                            value="{{ old('full_name', $member?$member->full_name:'') }}"
                            required
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Short Name')</label>
                            <input type="text" name="short_name" class="form-control" 
                            placeholder="@lang('Enter') @lang('Short Name')"
                            value="{{ old('short_name', $member?$member->short_name:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" 
                            placeholder="@lang('Enter') @lang('Email')"
                            value="{{ old('email', $member && $member->email?$member->email:(Str::random(8).'@rtqmaisuro.id')) }}"
                            required
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Phone')</label>
                            <input type="text" name="phone" class="form-control" placeholder="@lang('Enter') @lang('Phone')"
                            value="{{ old('phone', $member?$member->phone:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Gender')<span class="text-danger">*</span></label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" value="male" {{ $member && $member->gender=='male'?'checked="checked"':'' }} name="gender"
                                    required> @lang('Male')
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" value="female" {{ $member && $member->gender=='female'?'checked="checked"':'' }} name="gender"
                                    required> @lang('Female')
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Tanggal Lahir')</label>
                            <input type="date" name="birth_date" class="form-control" placeholder="Tanggal Lahir"
                            value="{{ old('birth_date', $member?$member->birth_date?->format('Y-m-d'):'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('School')</label>
                            <input type="text" name="school" class="form-control" placeholder="@lang('Enter') @lang('School')"
                            value="{{ old('school', $member?$member->school:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Class')</label>
                            <input type="text" name="class" class="form-control" placeholder="@lang('Enter') @lang('Class')"
                            value="{{ old('class', $member?$member->class:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Level')<span class="text-danger">*</span></label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" value="Iqro" {{ $member && $member->level=='Iqro'?'checked="checked"':'' }} name="level"
                                    required> Iqro'
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" value="Quran" {{ $member && $member->level=='Quran'?'checked="checked"':'' }} name="level"
                                    required> Qur'an
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status Bebas SPP')</label>
                            <input type="text" name="status" class="form-control"
                            value="{{ old('status', $member?$member->status:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Address')</label>
                            <input type="text" name="address" class="form-control" placeholder="@lang('Enter') @lang('Address')"
                            value="{{ old('address', $member?$member->address:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Post Code')</label>
                            <input type="text" name="postcode" class="form-control" placeholder="@lang('Enter') @lang('Post Code')"
                            value="{{ old('postcode', $member?$member->postcode:'') }}"
                            >
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>@lang('Foto')</label>
                                    <input type="file" name="profile_picture" class="form-control"
                                    >
                            </div>
                            <div class="col-md-6">
                                <livewire:member-profile :member="$member" />
                            </div>
                        </div>
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
        placeholder: "@lang('Pilih Halaqoh')"
    });
});
</script>
@endpush