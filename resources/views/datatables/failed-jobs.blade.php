@extends('datatables.datatable')
@section('buttons')
        <div class="btn-group" role="group">
            <a href="{{ route('failed-jobs.retry-all') }}" class="btn btn-success" onclick="return confirm('Are you sure you want to retry all failed jobs?');">
                <i class="la la-redo"></i> Retry All
            </a>
            <a href="{{ route('failed-jobs.flush-all') }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to flush all failed jobs? This action cannot be undone.');">
                <i class="la la-trash"></i> Flush All
            </a>
        </div>
@endsection
@push('scripts')
<script>
jQuery(document).ready(function() {
    $(document).on("click", ".retry-job", function(e) {
        if(!confirm("@lang('Are you sure you want to retry this job?')")) {
            e.preventDefault();
            return false;
        }
    });

    $(document).on("click", ".forget-job", function(e) { 
        e.preventDefault();
        if(confirm("@lang('Are you sure you want to forget this job?')")) {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('failed-jobs.forget', ':id') }}".replace(':id', id),
                type: "POST",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(dataResult){
                    if(dataResult.statusCode == 200 || dataResult.statusCode == undefined){
                        alert('@lang('Job has been forgotten')');
                        location.reload(true);
                    }else{
                        alert(dataResult.message || '@lang('Failed to forget job')');
                    }
                },
                error: function(xhr, status, error) {
                    var message = "@lang('Failed to forget job')";
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                }
            });
        }
    });
});
</script>
@endpush

