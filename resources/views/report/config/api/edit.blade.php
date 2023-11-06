@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config Edit Report')

@section('content_header')
    <h1><a class="btn btn-social-icon btn-github"  href="{{url(config('dynamic-extract.prefix').'/report/config/external/api')}}"><i class="fa  fa-arrow-left"></i></a>
    </h1>
@stop

@section('content')

 <div class="card border-dark">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> Report API  Edit "{{$data->name}}" </strong></h5></center>

    </div>
    <div class="panel-body">

    <div class="card-body table-responsive no-padding">
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/external/api/store/edit')}}">
          @csrf
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">
                <input type="" name="id" value="{{$data->id}}" hidden="">

                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" name="name" required autofocus="" class="form-control" placeholder="Name" value="{{$data->name}}"><br>
                <label for="advance_query" class="form-label">Advance query</label>
                 <select name="advance_query" id="advance_query" class="form-control" required autofocus="">
                  <option value="$data->advance_query" disabled="" selected="" > {{$data->advance_query == 1 ? "Advanced" : "Basic" }}</option>
                  <option value="0" >Basic</option>
                  <option value="1" >Advanced</option>
                </select><br>
                 <select name="table_name" id="table_name"  class="form-control" style="display: none" >
                   @if(isset($data->table->id))
                        <option value="{{$data->table->id}}" selected="">{{$data->table->name}}</option>
                        @else
                        <option value="" selected="">Select filter..</option>
                    @endif
                  @if($tables)
                  @foreach($tables as $table)
                    @if(config('dynamic-extract.auth'))
                        @if(Auth::user()->can($table->can))
                        <option value="{{$table->id}}">{{$table->name}}</option>
                        @endif
                    @else
                        <option value="{{$table->id}}">{{$table->name}}</option>
                    @endif
                  @endforeach
                  @endif
                </select>

                <textarea type="text" name="text_query" id="text_query"  value="{{$data->text_query}}" class="form-control" placeholder="SQL"  style="display: none"></textarea>

                <label for="expire_at" class="form-label">Expiry date</label>
                <label for="expire_at" class="form-label"  >Expiry date  {{ \Carbon\Carbon::parse($data->expire_at)->format('Y-m-d')}}</label>
                <input type="date" name="expire_at" id="expire_at" required autofocus="" class="form-control" placeholder="Expiry date" value="{{\Carbon\Carbon::parse($data->expire_at)->format('Y-m-d')}}"><br>
                <label for="paginate" class="form-label">Paginate</label>
                <select name="paginate" id="paginate" class="form-control" required autofocus="">
                  <option value="{{$table->paginate}}"  disabled="" selected="">{{$data->paginate == 1 ? "True" : "False" }}</option>
                  <option value="0" >False</option>
                  <option value="1" >True</option>
                </select><br>
                <button type="submit" class="btn btn-primary">Save changes</button>
                <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');" href="{{url(config('dynamic-extract.prefix').'/report/config/external/api/delete',$data->id)}}">Delete</a>
          </form>

  </div>
</div>
 </div>

@stop
@section('js')
 <script type="text/javascript">
       $(document).ready(function(){
          $("#advance_query").on('change', function(e) {
            $value=$(this).val();
            if ($value=="1"){

              $('#text_query').css('display','block');
              $('#table_name').css('display','none');
            }else{

             $('#text_query').css('display','none');
             $('#table_name').css('display','block');
           }

          })
       })
    </script>
   @stop
