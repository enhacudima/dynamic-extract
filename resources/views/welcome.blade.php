@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Welcome')

@section('content_header')
@stop

@section('content')
    @if( Cookie::get('access_user_token'))
    <center> Let's start!!</center>
    @else
    <center> You must start a session ...</center>
    @endauth
@stop
