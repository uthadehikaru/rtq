@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-6">
    <video id="webcam" autoplay playsinline></video>
    <img id="photo" download="selfie.png" class="d-none" />
    <canvas id="canvas" class="d-none"></canvas>
    <audio id="snapSound" src="{{ asset('assets/snap.wav') }}" preload = "auto"></audio>
    </div>
    <div class="col-6">
    <div class="form-group">
        <label>@lang('Batch')</label>
        <select class="form-control kt-select2" name="batch_id" id="batch" required>
            <option value="">@lang('Select Batch')</option>
            @foreach($batches as $batch)
                <option value="{{ $batch->id }}">{{ $batch->course->name }}
                    {{ $batch->name }} ({{ $batch->start_time?->format('H:i') }} @ {{ $batch->place }})</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="col-3 col-form-label">Guru Pengganti</label>
            <div class="col-9 col-form-label">
                <div class="radio-inline">
                    <label class="radio radio-outline radio-success">
                        <input type="radio" name="badal" value="1" required>
                        <span></span>Ya</label>
                    <label class="radio radio-outline radio-success">
                        <input type="radio" name="badal" checked="checked" value="0" required>
                        <span></span>Tidak</label>
                </div>
            </div>
        </div>
        <p id="locationData"></p>
        <button id="flip" class="d-none btn btn-warning">Ganti Kamera</button>
        <button id="capture" class="btn btn-primary">Absen</button>
        <button id="reset" class="d-none btn btn-danger">Reset</button>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
<script>
    
jQuery(document).ready(function() {
    const webcamElement = document.getElementById('webcam');
    const canvasElement = document.getElementById('canvas');
    const snapSoundElement = document.getElementById('snapSound');
    const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);

    $('.kt-select2').select2({
        placeholder: "@lang('Pilih Halaqoh')"
    });

    webcam.start()
   .then(result =>{
      console.log("webcam started");
      if(webcam.webcamCount>1)
        $('#flip').removeClass('d-none');
   })
   .catch(err => {
       console.log(err);
   });

   function toggle(){
    $('#photo').toggleClass('d-none');
    $('#webcam').toggleClass('d-none');
    $('#flip').toggleClass('d-none');
   }

   $('#flip').click(function(){
        webcam.flip();
        webcam.start();
   });



   $('#capture').click(function(){
    let picture = webcam.snap();
    $("#photo").attr("src",picture);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(locationSuccess);
    } else {
        $("#locationData").html('Your browser does not support location data retrieval.');
    }
    toggle();
    $('#capture').text('Prosessing...');
   });

   function locationSuccess(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        $("#locationData").html("Latitude: " + latitude + "<br>Longitude: " + longitude);
        if(latitude!=0 && longitude!=0)
            sendData(latitude, longitude);
        else
            alert('Gagal mengambil posisi, pastikan akses lokasi diberikan');
    }

    function sendData(lat, long){
        var data = {
            '_token': $('meta[name=csrf-token]').attr('content'),
            photo: $('#photo').attr('src'),
            lat: lat,
            long: long,
            batch_id: $('#batch').val(),
            badal: $('input[name="badal"]:checked').val(),
        };

        $.ajax({
            type: "POST",
            url: "{{ route('teacher.schedules.create') }}",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                if(data.error)
                    alert(data.error);
                else{
                    $('#photo').attr('src', data.path);
                    alert('Berhasil');
                    $('#capture').addClass('d-none');
                }
            },
            error: function(errMsg) {
                alert('Errpr : '+errMsg);
                $('#capture').addClass('d-none');
                $('#reset').removeClass('d-none');
            }
        });
    }
   
   $('#reset').click(function(){
    toggle();
    $('#capture').text('Absen');
    $('#capture').removeClass('d-none');
    $('#reset').addClass('d-none');
   });
});
</script>
@endpush