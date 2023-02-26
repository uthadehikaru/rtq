<div style='max-width:500px;margin:50px auto;' class='search-form-outer'>
    <form method='get' action='{{route("binshopsblog.search", app('request')->get('locale'))}}' class='text-center'>
        <h4>Cari artikel:</h4>
        <input type='text' name='s' placeholder='Cari...' class='form-control' value='{{\Request::get("s")}}'>
        <input type='submit' value='Cari' class='btn btn-primary m-2'>
    </form>
</div>