<div>
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Scan Peserta
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <input type="hidden" name="schedule_id" value="{{ $schedule_id }}">
            <div id="qr-reader"></div>
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript" src="{{ asset('scan/html5-qrcode.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('scan/html5-qrcode-rtq.js') }}?v=20251107130000"></script>
@endpush