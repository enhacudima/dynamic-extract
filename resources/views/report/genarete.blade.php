@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Genarete')

@section('content_header')
    <a class="btn btn-social-icon btn-github"  href="{{ url()->previous() }}"><i class="fa  fa-arrow-left"></i></a>
@stop

@section('content')
    <div class="row justify-content-md-center">
    @if($report)
        @if(config('dynamic-extract.auth') ? Auth::user()->can($report->can) : true)
        <div class="col-md-6">
            <div class="card border-dark mb-3" >
            <div class="card-header">
                <h5 class="card-title">{{$report->name}}</h5>
            </div>
            <div class="card-body text-dark">
                    <form role="form" method="POST" action="{{ url(config('dynamic-extract.prefix').$process_url)}}" enctype="multipart/form-data">
                        @csrf
                        <input name="can" value="{{$report->can}}" type="hidden">
                        <input name="type" value="{{$report->table->table_name}}" type="hidden">
                        <input name="report_id" value="{{$report->id}}" type="hidden">
                        <input name="report_name" value="{{$report->name}}" type="hidden">
                        @if(isset($report->filtro))
                        <!--date-->
                        <div class="form-group">
                            <label for="data_filter">Date filter</label>
                        <select class="form-control form-control-sm" name="filtro" id="data_filter">
                            @foreach($report->sync_filtros as $filtro)
                                @if($filtro->filtros->type=='date')
                                <option value="{{$filtro->filtros->value}}">{{$filtro->filtros->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        </div>

                        <div class="row" >
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_start">Start date</label>
                                        <input class="form-control form-control-sm"  type="date" name="start" id="data_start" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_end">End date</label>
                                        <input class="form-control form-control-sm"  type="date" name="end" id="data_end">
                                </div>
                            </div>
                        </div>
                        <!-- end date-->
                        <!--other filter-->
                            @foreach($report->sync_filtros as $key => $filtro)
                                @if($filtro->filtros->type=='<=' or $filtro->filtros->type=='>=')
                                <div class="">
                                    <input type="hidden" name="comparisonColumun[]" value="{{$filtro->filtros->value}}">
                                    <input type="hidden" name="typeColumun[]" value="{{$filtro->filtros->type}}">
                                    <div class="form-group">
                                        <label for="comparisonValue[]">{{$filtro->filtros->name}}</label>
                                        <input class="form-control form-control-sm" type="text" name="comparisonValue[]"  placeholder="{{$filtro->filtros->name}}">
                                    </div>
                                </div>
                                @endif
                                @if($filtro->filtros->type=='pesquisa')
                                <div class="">
                                    <input type="hidden" name="pesquisaColumun[]" value="{{$filtro->filtros->value}}">
                                    <div  class="form-group">
                                        <label for="pesquisaValue{{$key}}">{{$filtro->filtros->name}}</label>
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" id="pesquisaValue{{$key}}" type="text" name="pesquisaValue[]" placeholder="{{$filtro->filtros->name}} ..">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="pesquisaValue{{$key}}"><i class="fas fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($filtro->filtros->type=='group')
                                    <input type="hidden" name="groupColumun[]" value="{{$filtro->filtros->value}}">
                                @endif
                                @if($filtro->filtros->type=='list')
                                <div class="form-group">
                                    <input type="hidden" name="listColumun[]" value="{{$filtro->filtros->value}}">
                                    <select class="form-control form-control-sm" name="listValue[]">
                                        <option value="no_filter" selected="" >Select {{$filtro->filtros->name}} ..</option>
                                        @foreach($filtro->filtros->lists as $list)
                                            <option value="{{$list->name}}">{{$list->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                @endif
                            @endforeach
                        <!--end other filter-->
                        @else
                            <div class="callout callout-info">
                                <b>Filters</b>
                                <p>Filters are not available for this report</p>
                            </div>
                            <input name="filtro" value="no_filter" type="hidden">
                        @endif
                        <hr/>
                    <span class="input-group-btn">
                        <button type="submit" class="btn  btn-dark ">{!!$process_icon!!} </button>
                    </span>
                    </form>
                <div class="text-right">
                    @if(isset($report->filtro))
                        <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/edit',$report->filtro_r->id)}}" class="btn btn-tool btn-sm"><i class="fas fa-filter"></i></a>
                    @endif
                    <a href="{{url(config('dynamic-extract.prefix').'/report/config/edit',$report->id)}}" class="btn btn-tool btn-sm"><i class="fas fa-pencil-alt"></i></a>
                </div>
            </div>
            </div>
        </div>
        @endif
    @endif
    </div>

@stop
