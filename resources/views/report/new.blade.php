@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Reports')

@section('content_header')
    <a class="btn btn-social-icon btn-github"  href="{{ url()->previous() }}"><i class="fa  fa-arrow-left"></i></a>
@stop

@section('content')
    <div class="row">
    @foreach($data as $report)
        @if(config('dynamic-extract.auth') ? Auth::user()->can($report->can) : true)
        <div class="col-sm-4 pb-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h6 class="card-title">{{$report->name}}</h6>
                    <p class="card-text"><small>{{$report->comments}}.</small></p>
                    <div class="text-end">
                        <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$report->id,'table'])}}" class="btn btn-primary btn-sm"><i class="fas fa-table"></i></a>
                        <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$report->id,'excel'])}}" class="btn btn-primary btn-sm"><i class="fas fa-cloud-download-alt"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
    {{ $data->links() }}
    </div>

@stop
