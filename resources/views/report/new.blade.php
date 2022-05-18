@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Reports')

@section('content_header')
    <a class="btn btn-social-icon btn-github"  href="{{ url()->previous() }}"><i class="fa  fa-arrow-left"></i> Reportes</a>
@stop

@section('content')
    <div class="row">
        @if(!$data_favorite->isEmpty())
            @foreach($data_favorite as $favorite)
                @if(config('dynamic-extract.auth') ? Auth::user()->can($favorite->report->can) : true)
                    <div class="col-sm-3 ">
                        <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <p class="card-text" style="height: 3rem;"><small>{{$favorite->report->name}}.</small></p>
                            <div class="text-right">
                                <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$favorite->report->id,'table'])}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$favorite->report->id,'excel'])}}" class="btn btn-primary btn-sm"><i class="fas fa-cloud-download-alt"></i></a>
                                <a href="{{url(config('dynamic-extract.prefix').'/report/config/favorite/remove',$favorite->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        </div>
                    </div>
                @endif
            @endforeach

        @else
            <div class="col-sm-12">
                <center> No favorite report!!</center>
            </div>
        @endif
    </div>
    <hr>
    <div class="row">

    @foreach($data as $report)
        @if(config('dynamic-extract.auth') ? Auth::user()->can($report->can) : true)
        <div class="col-sm-3 ">
            <div class="card text-white bg-secondary mb-3">
            <div class="card-header" style="height: 4rem;">{{$report->name}}</div>
            <div class="card-body">
                <p class="card-text" style="height: 3rem;"><small>{{$report->comments}}.</small></p>
                <div class="text-right">
                    <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$report->id,'table'])}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$report->id,'excel'])}}" class="btn btn-primary btn-sm"><i class="fas fa-cloud-download-alt"></i></a>
                    @if(!($report->favorite))
                        <a href="{{url(config('dynamic-extract.prefix').'/report/config/favorite',$report->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-thumbtack"></i></a>
                    @endif
                </div>
            </div>
         </div>

        </div>
        @endif
    @endforeach

        <div class="w-100"></div>
        <div class="col-sm-12">
            {{ $data->links() }}
        </div>
    </div>

@stop
