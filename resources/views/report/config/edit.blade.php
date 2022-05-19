@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config Edit Report')

@section('content_header')
    <h1><a class="btn btn-social-icon btn-github"  href="{{ url()->previous() }}"><i class="fa  fa-arrow-left"></i></a>
    </h1>
@stop

@section('content')

 <div class="card border-dark">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> Report Edit </strong></h5></center>

    </div>
    <div class="panel-body">

    <div class="card-body table-responsive no-padding">
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/store/edit')}}">
          @csrf
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">
                <input type="" name="id" value="{{$data->id}}" hidden="">

                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" name="name" required autofocus="" class="form-control" placeholder="Name" value="{{$data->name}}"><br>
                <label for="exampleFormControlInput1" class="form-label">Comments</label>
                <input type="text" name="comments" required autofocus="" class="form-control" placeholder="Comments" value="{{$data->comments}}"><br>
                <label for="exampleFormControlInput1" class="form-label">Permissions</label>
                <input type="text" name="can" required autofocus="" class="form-control" placeholder="Type a permission"  value="{{$data->can}}"><br>
                <label for="exampleFormControlInput1" class="form-label">Filter</label>
                <select name="filtro" autofocus="" class="form-control">
                  @if(isset($data->filtro_r->id))
                    <option value="{{$data->filtro_r->id}}" selected="">{{$data->filtro_r->name}}</option>
                    @else
                    <option value="" selected="">Select filter..</option>
                  @endif
                  @foreach($filtros as $filtro)
                  <option value="{{$filtro->id}}">{{$filtro->name}}</option>
                  @endforeach
                  <option value="" >No filter..</option>
                </select>
                <br>
                <label for="exampleFormControlInput1" class="form-label">Table</label>
                <select name="table_name" class="form-control">
                  <option value="{{$data->table->id}}"  selected="">{{$data->table->name}}</option>
                  @foreach($tables as $table)
                  <option value="{{$table->id}}">{{$table->name}}</option>
                  @endforeach
                </select><br>
                <button type="submit" class="btn btn-primary">Save changes</button>
                <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');" href="{{url(config('dynamic-extract.prefix').'/report/config//delete',$data->id)}}">Delete</a>
          </form>

  </div>
</div>
 </div>

@stop
