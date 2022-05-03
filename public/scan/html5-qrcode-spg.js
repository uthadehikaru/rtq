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
    xhr.open("GET", base_url+"/api/product/"+decodedText, true);
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4) {
            if(xhr.status == 200){
                var product = JSON.parse(xhr.responseText);
                if (confirm("Add "+product.name+"?") == true) {
                    process(product);
                }
            }else{
                alert('Product not found '+decodedText);
            }
            if(html5QrcodeScanner!=null)
                html5QrcodeScanner.resume();
        }
    }
    xhr.send();
}

function process(product){
    ++countResults;
    var clone = template.cloneNode(true);
    clone.id = product.value;
    clone.querySelector('.value').textContent = countResults+ ". " + product.value+" - "+product.name;
    clone.querySelector('.product_code').value = product.value;
    clone.querySelector('.product_name').value = product.name;
    clone.querySelector('.price').value = product.price;
    clone.querySelector('.price').id = 'price-'+countResults;
    clone.classList.remove("d-none");
    resultContainer.append(clone);
    new AutoNumeric('#price-'+countResults, { 
        digitGroupSeparator : '.',
        decimalCharacter : ',',
        decimalPlaces : 0,
      });
}

function increaseQuantity(row)
{
    let quantity = parseInt(row.querySelector('.quantity').value);
    row.querySelector('.quantity').value = quantity+1;
}

function decreaseQuantity(row)
{
    let quantity = parseInt(row.querySelector('.quantity').value);
    if(quantity>0)
        quantity = quantity-1;
    row.querySelector('.quantity').value = quantity;
}

function delete_line(e)
{
    parent = e.parentNode.parentNode.parentNode.parentNode;
    parent.querySelector('.is_deleted').value = 1;
    parent.classList.add('d-none');
}

function delete_row(e)
{
    e.parentNode.parentNode.parentNode.parentNode.remove();
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
    
    document.getElementById('product_code').onchange = function() {
        var decodedText = this.value;
        check(decodedText);
        this.value = "";
    };

    AutoNumeric.multiple('.price', { 
        digitGroupSeparator : '.',
        decimalCharacter : ',',
        decimalPlaces : 0,
    });
    
    document.getElementById('save').onclick = function() {
        document.getElementById("date-ordered").value = document.getElementById('date-input').value;
        document.getElementById("scan-form").submit();
    };

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