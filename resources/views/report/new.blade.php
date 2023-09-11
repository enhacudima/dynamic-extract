@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Reports')

@section('content_header')
    <a class="btn btn-social-icon btn-github"  href="{{ url()->previous() }}"><i class="fa  fa-arrow-left"></i> Reportes</a>
@stop

@section('content')
    <div class="row">
        @if(!$data_favorite->isEmpty())
            @foreach($data_favorite as $favorite)
                    <div class="col-sm-3 ">
                        <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <p class="card-text" style="height: 3rem;"><small>{{$favorite->report->name}}.</small></p>
                            <div class="text-right">
                                <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$favorite->report->id,'table'])}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$favorite->report->id,'excel'])}}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i></a>
                                <a href="{{url(config('dynamic-extract.prefix').'/report/config/favorite/remove',$favorite->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        </div>
                    </div>
            @endforeach

        @else
            <div class="col-sm-12">
                <center> No favorite report!!</center>
            </div>
        @endif
    </div>
    <hr>
    <div class="row mb-3">
        <div class="col-sm-12">
            <center>
            <form role="form" method="GET" action="{{ url(config('dynamic-extract.prefix'),'search-report')}}" enctype="multipart/form-data" >
                @csrf
                <div class="input-group"  style="width: 70%;">
                    <input type="text" name="search" class="form-control" placeholder="Search..">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            </center>
        </div>
    </div>

    <div class="row">

        @if(!$data->isEmpty())
            @foreach($data as $report)
                <div class="col-sm-3 ">
                    <div class="card text-white bg-secondary mb-3">
                    <div class="card-header" style="height: 4rem;">{{$report->name}}</div>
                    <div class="card-body">
                        <p class="card-text" style="height: 3rem;"><small>{{$report->comments}}.</small></p>
                        <div class="text-right">
                            <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$report->id,'table'])}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="{{url(config('dynamic-extract.prefix').'/report/config/open',[$report->id,'excel'])}}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i></a>
                            @if(!($report->favorite))
                                <a href="{{url(config('dynamic-extract.prefix').'/report/config/favorite',$report->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-thumbtack"></i></a>
                            @endif
                        </div>
                    </div>
                </div>

                </div>
            @endforeach

            <div class="w-100"></div>
            <div class="col-sm-12">
                {{ $data->links() }}
            </div>
        @else
            <div class="col-sm-12">
                <center> No data!!</center>
            </div>
        @endif
    </div>

@stop
