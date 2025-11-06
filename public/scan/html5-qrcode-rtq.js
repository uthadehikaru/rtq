var lastResult, countResults = 0;
var resultContainer, template;
var scanning = false;
var html5QrcodeScanner;

function docReady(fn) {
    // see if DOM is already available
    if (document.readyState === "complete" || document.readyState === "interactive") {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

function check(decodedText){
    var xhr = new XMLHttpRequest();
    var base_url = document.querySelector('meta[name="description"]').content;
    var schedule_id = document.querySelector('input[name="schedule_id"]').value;
    xhr.open("GET", base_url+"/api/present/"+schedule_id+"?qrcode="+decodedText, true);
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4) {
            if(xhr.status == 200){
                var data = JSON.parse(xhr.responseText);
                alert('Peserta '+data.user.name+' '+' hadir');
            }else{
                alert(xhr.responseText);
            }
            Livewire.emit('member-present-updated');
            html5QrcodeScanner.resume();
        }
    }
    xhr.send();
}

document.addEventListener( 'click', function( e ) {
    if ( e.target.classList.contains('decrease') ) {
        decreaseQuantity(e.target.parentNode.parentNode.parentNode);
    }
    if ( e.target.classList.contains('increase') ) {
        increaseQuantity(e.target.parentNode.parentNode.parentNode);
    }
});

docReady(function() {

    resultContainer = document.getElementById('results');
    template = document.getElementById('row-template');

    const formatsToSupport = [
        Html5QrcodeSupportedFormats.QR_CODE,
        Html5QrcodeSupportedFormats.UPC_A,
    ];

    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { 
            fps: 10,
            qrbox: { "width":300, "height": 200},
            //formatsToSupport: formatsToSupport,
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            },
            rememberLastUsedCamera: true
        });
    
    function onScanSuccess(decodedText, decodedResult) {
        html5QrcodeScanner.pause();
        check(decodedText);
    }
    
    // Optional callback for error, can be ignored.
    function onScanError(qrCodeError) {
        // This callback would be called in case of qr code scan error or setup error.
        // You can avoid this callback completely, as it can be very verbose in nature.
    }
    
    html5QrcodeScanner.render(onScanSuccess, onScanError);
});