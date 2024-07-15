@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="alert alert-primary" role="alert" id="alert">
            <ul>
                <li class="list-unstyled"><h3>PERHATIAN!</h3></li>
                <li>Pastikan telah memberikan akses untuk mendapatkan lokasi dan kamera</li>
                <li>Diwajibkan mengambil foto ruang kelas beserta peserta tahsin yang sudah hadir saat kelas dimulai</li>
                <li>Sistem akan mencatat lokasi, tanggal, dan jam absensi ke dalam foto</li>
                <li>Absen hanya bisa dilakukan saat jam kelas dimulai.</li>
                <li>Toleransi telat maksimal {{ setting('maks_waktu_telat') }} menit setelah kelas dimulai kecuali sudah konfirmasi</li>
                <li>Jam mulai kelas mengikuti jadwal yang sudah ditentukan.</li>
                <li>Jika kelas tidak sesuai jadwal, mohon konfirmasi ke admin untuk perubahan jam mulai sebelum memulai kelas.</li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('Batch')</label>
            <select class="form-control kt-select2" name="batch_id" id="batch" required>
                <option value="">@lang('Select Batch')</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->course->name }}
                        {{ $batch->name }} ({{ $batch->start_time?->format('H:i') }} @ {{ $batch->place }} {{ $batch->teachers?->pluck('name')->join(',') }})</option>
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
    </div>
    <div class="col-md-6">
        <video id="webcam" autoplay playsinline width="300" height="400"></video>
        <img id="photo" download="selfie.png" class="d-none" />
        <canvas id="canvas" class="d-none"></canvas>
        <audio id="snapSound" src="{{ asset('assets/snap.wav') }}" preload = "auto"></audio>
        <button id="flip" class="d-none btn btn-warning">Ganti Kamera</button>
        <button id="capture" class="btn btn-primary">Absen</button>
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
    
    $('.kt-select2').select2({
        placeholder: "@lang('Pilih Halaqoh')"
    });

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
    let batch_id = $('#batch').val();
    if(batch_id=="")
        return alert('Halaqoh harus diisi');
    
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