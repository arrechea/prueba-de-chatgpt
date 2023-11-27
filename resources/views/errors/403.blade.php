@extends('errors::layout')

@section('title', __('errors.AdminNotActive.title'))

@section('message')
    <p>{{__('errors.adminBlocked')}}</p>
    <p>{{ $exception->getMessage() }}</p>
@stop
