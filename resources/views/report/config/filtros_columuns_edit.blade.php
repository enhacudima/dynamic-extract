@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config Edit Columun')

@section('content_header')
    <h1><a class="btn btn-social-icon btn-github"  href="{{ url(config('dynamic-extract.prefix').'/report/config/filtro/columuns') }}"><i class="fa  fa-arrow-left"></i></a>
    </h1>
@stop

@section('content')

 <div class="card card-solid card-default">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> Edit Columun </strong></h5></center>

    </div>
    <div class="panel-body">

    <div class="card-body table-responsive no-padding">
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/filtro/columuns/edit/store')}}">
          @csrf
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">
                <input type="" name="id" value="{{$data->id}}" hidden="">

                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" name="name" required autofocus="" class="form-control" placeholder="Name" value="{{$data->name}}"><br>
                <label for="exampleFormControlInput1" class="form-label">Filter</label>
                <select required="" name="report_new_filtro_id" required autofocus="" class="form-control">
                  <option value="{{$data->report_new_filtro_id}}"selected="">{{$data->filtro->name}}</option>
                  @foreach($filtros as $filtro)
                    <option value="{{$filtro->id}}">{{$filtro->name}}</option>
                  @endforeach
                </select>
              <br>
                <button type="submit" class="btn btn-primary">Save changes</button>
          </form>

  </div>
</div>
 </div>

@stop
