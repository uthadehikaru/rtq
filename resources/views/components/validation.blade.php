@if ($errors->any())
    <x-alert type="warning">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
@if(session()->has('error'))
<x-alert type="danger">{{ session('error') }}</x-alert>
@endif