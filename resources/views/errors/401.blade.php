@extends('errors::layout')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Ups, anda tidak memiliki akses ke halaman ini.'))
