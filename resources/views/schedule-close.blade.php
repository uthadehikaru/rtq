@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="alert alert-primary" role="alert" id="alert">
            <ul>
                <li class="list-unstyled"><h3>PERHATIAN!</h3></li>
                <li>Pastikan telah memberikan akses untuk mendapatkan lokasi dan kamera</li>
                <li>Diwajibkan mengambil foto ruang kelas saat tutup kelas</li>
                <li>Sistem akan mencatat lokasi, tanggal, dan jam tutup kelas ke dalam foto</li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <p>Mulia Kelas : {{ $schedule->start_at->format('H:i') }}</p>
        <p>Absen Masuk : {{ $present->attended_at->format('H:i') }}</p>
        <p>Guru Pengganti : {{ $present->is_badal?'Ya':'Tidak' }}</p>
        <p id="locationData"></p>
    </div>
    <div class="col-md-6">
        <video id="webcam" autoplay playsinline width="300" height="400"></video>
        <img id="photo" download="selfie.png" class="d-none" />
        <canvas id="canvas" class="d-none"></canvas>
        <audio id="snapSound" src="{{ asset('assets/snap.wav') }}" preload = "auto"></audio>
        <button id="flip" class="d-none btn btn-warning">Ganti Kamera</button>
        <button id="capture" class="btn btn-primary">Absen Keluar</button>
        <button id="reset" class="d-none btn btn-danger">Reset</button>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/webcam-easy.min.js') }}"></script>
<script>
     
jQuery(document).ready(function() {
    const webcamElement = document.getElementById('webcam');
    const canvasElement = document.getElementById('canvas');
    const snapSoundElement = document.getElementById('snapSound');
    const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(locationSuccess);
    } else {
        $("#locationData").html('Your browser does not support location data retrieval.');
    }

    webcam.start()
   .then(result =>{
      console.log("webcam started");
    checkCamera();
      if(webcam.webcamCount>1)
        $('#flip').removeClass('d-none');
   })
   .catch(err => {
       console.log(err);
        $('#capture').addClass('d-none');
        $('#alert').removeClass('alert-primary');
        $('#alert').addClass('alert-danger');
        $('#alert').text('Gagal mengakses kamera. pastikan anda memberikan akses ke kamera untuk melakukan absensi. silahkan refresh kembali dan pastikan akses kamera diberikan');
   });

   function toggle(){
    $('#photo').toggleClass('d-none');
    $('#webcam').toggleClass('d-none');
    $('#flip').toggleClass('d-none');
   }

   $('#flip').click(function(){
        webcam.flip();
        webcam.start();
        checkCamera();
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
    sendData();
   });

   var latitude = 0;
   var longitude = 0;

   function locationSuccess(position) {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        $("#locationData").html("Latitude: " + latitude + "<br>Longitude: " + longitude);
    }

    function checkCamera()
    {
        console.log(webcam.facingMode);
        if(webcam.facingMode=="user")
            $('#flip').text('Kamera Belakang');
        else
            $('#flip').text('Kamera Depan');
    }

    function sendData(){
        var data = {
            '_token': $('meta[name=csrf-token]').attr('content'),
            photo: $('#photo').attr('src'),
            lat: latitude,
            long: longitude,
        };

        $.ajax({
            type: "POST",
            url: "{{ route('teacher.schedules.close', $schedule->id) }}",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                if(data.error)
                    alert(data.error);
                else{
                    window.location.href = '{{ url('teacher/schedule') }}/'+data.schedule_id;
                }
            },
            error: function(errMsg) {
                $('#capture').addClass('d-none');
                $('#reset').removeClass('d-none');
                alert('Error : '+errMsg);
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