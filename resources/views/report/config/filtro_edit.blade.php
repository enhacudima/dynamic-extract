@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config Edit Group ')

@section('content_header')
    <h1><a class="btn btn-social-icon btn-github"  href="{{ url(config('dynamic-extract.prefix').'/report/config/filtro') }}"><i class="fa  fa-arrow-left"></i></a>
    </h1>
@stop

@section('content')

 <div class="card card-solid card-default">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> Edit Group Filter </strong></h5></center>

    </div>
    <div class="card-body">

    <div class="card-body table-responsive no-padding">
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/filtro/edit/store')}}">
          @csrf
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">
                <input type="" name="id" value="{{$data->id}}" hidden="">

                <input type="text" name="name" required autofocus="" class="form-control" placeholder="Name" value="{{$data->name}}"><br>

                <div class="">
                    <div class="form-group">
                        <strong>Filters</strong>
                        <br/>

                        @if($filtros)
                        <div class="flexCheckLists" id="flexCheckLists" >
                            <div class="form-group">
                                @foreach($filtros as $value)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filtros[]" value="{{ $value->id }}" id=" {{ $value->id }}"
                                    @if(in_array($value->id, $filtros_selected))
                                        checked
                                    @endif
                                    >
                                    <label class="form-check-label" for=" {{ $value->id }}">
                                        {{ $value->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
              <br>
                <button type="submit" class="btn btn-primary">Save changes</button>
                <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');" href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/delete',$data->id)}}">Delete</a>
          </form>

  </div>
</div>
 </div>


@stop
