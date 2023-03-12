@extends('errors::layout')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Ups, ada kesalahan pada sistem. coba kembali beberapa saat lagi.'))
