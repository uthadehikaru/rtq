@if(session()->has('message'))
<x-alert type="success">{{ session('message') }}</x-alert>
@endif
<x-validation />