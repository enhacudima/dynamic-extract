@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config API Report')

@section('content_header')
      <a class="btn btn-social-icon btn-github"  href="{{ url(config('dynamic-extract.prefix').'/report/new') }}"><i class="fa  fa-arrow-left"></i></a>
      <a class="btn btn-social-icon btn-github"  data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fa  fa-plus"></i></a>
      <a class="btn btn-social-icon btn-github " href="{{ url(config('dynamic-extract.prefix').'/report/config/filtro') }}"><i class="fa  fa-cog"></i></a>

@stop

@section('content')
<div class="row">
<div class="col-md-3">
    <div class="list-group">
    <a href="{{url(config('dynamic-extract.prefix').'/report/config')}}" class="list-group-item list-group-item-action" > New Report</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro')}}" class="list-group-item list-group-item-action">Group Filter</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/filtros')}}" class="list-group-item list-group-item-action">Filter</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/list')}}" class="list-group-item list-group-item-action">Filter List</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/columuns')}}" class="list-group-item list-group-item-action">Filter Columuns</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/external/api')}}" class="list-group-item list-group-item-action active" aria-current="true">External API</a>
    </div>
</div>

 <div class="col-md-9">
 <div class="card border-dark">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> API Configuration </strong></h5></center>

    </div>
    <div class="panel-body">

    <div class="card-body table-responsive no-padding">

     <table id="example" class="table table-striped table-bordered" style="width:100%">

        <thead >
        <tr>
            <th scope="col">ID</th>
            <th scope="col"><center>Name</center></th>
            <th scope="col"><center>Expiration</center></th>
            <th scope="col"><center>Advanced</center></th>
            <th scope="col"><center>Query</center></th>
            <th scope="col"><center>Paginate</center></th>
            <th scope="col">Table</th>
            <th scope="col"><center>Filter</center></th>
            <th scope="col">Actions</th>
            <th scope="col">Create</th>
            <th scope="col">Time</th>
            <th scope="col">User</th>

        </tr>
        </thead>
        <tbody>
            @if(isset($data))
                @foreach($data as $value)
                <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->name}}</td>
                <td>{{ \Carbon\Carbon::parse($value->expire_at)->diffForHumans() }}</td>
                <td>{{$value->advance_query ? "True" : "False"}}</td>
                <td>{{$value->text_query}}</td>
                <td>{{$value->paginate ? "True" : "False"}}</td>
                <td>{{isset($value->table) ? $value->table->name : ""}}</td>
                <td>
                @if(isset($value->access_link))
                  <a class="" target="_blank" aria-hidden="true" href="{{URL(config('dynamic-extract.prefix')."/api/v1/data/".$value->access_link)}}" >{{URL(config('dynamic-extract.prefix')."/api/v1/data/".$value->access_link)}} <i class="fas fa-external-link-alt"></i></a>
                @endif
                </td>
                <td>
                    @if(!$value->status)
                    <a class="btn btn-danger btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/external/api/delete',$value->id)}}" onclick="return confirm('Are you sure you want to active this item?');" > <i class="fas fa-lock-open"></i></a>
                    @else
                      <a class=" btn btn-success btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/external/api/edit',$value->id)}}" ><i class="fas fa-edit"></i></a>
                      <a class=" btn btn-success btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/external/api/schedule',$value->id)}}" ><i class="fas fa-calendar-week"></i></a>
                      <a class="btn btn-danger btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/external/api/delete',$value->id)}}" onclick="return confirm('Are you sure you want to deactivate this item?');" ><i class="fas fa-lock"></i></a>
                    @endif
                </td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at->diffForHumans()}}</td>
                <td> {{$value->user->name ?? ''}} </td>
                </tr>

                @endforeach
            @endif
        </tbody>
    </table>
  </div>
</div>
</div>
</div>
</div>


  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create New API</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/external/api/store/new')}}">
          @csrf
            <div class="modal-body">
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">

                <input type="text" name="name" required autofocus="" class="form-control" placeholder="Name"><br>
                <select name="advance_query" id="advance_query" class="form-control" required autofocus="">
                  <option value="" disabled="" selected="">Select Query type..</option>
                  <option value="0" >Basic</option>
                  <option value="1" >Advanced</option>
                </select><br>
                <select name="table_name" id="table_name"  class="form-control" style="display: none, ma" >
                  <option value="" disabled="" selected="">Select table..</option>
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
                <textarea type="text" name="text_query" id="text_query"  class="form-control" placeholder="SQL"  style="display: none"></textarea><br>
                <label for="expire_at" class="form-label">Expiry date</label>
                <input type="date" name="expire_at" id="expire_at" required autofocus="" class="form-control" placeholder="Expiry date"><br>
                <label for="paginate" class="form-label">Paginate</label>
                <select name="paginate" class="form-control" required autofocus="">
                  <option value="" disabled="" selected="">Use paginate..</option>
                  <option value="0" >False</option>
                  <option value="1" >True</option>
                </select>

              </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
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
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
            dom: 'Blfrtip',
            "aLengthMenu": [[25, 50, 75,,100,250,500, -1], [25, 50, 75,100,250,500, "All"]],
            "iDisplayLength": 25,
            "order": [[0, "desc"]],
                    buttons: [
            {
              extend: 'copy',
              text: '<i class="fas fa-copy"></i>',
              exportOptions: {
                columns: ':visible'

              }
            },
            {
              extend: 'excel',
              text: '<i class="fas fa-file-excel"></i>',
              exportOptions: {
                columns: ':visible'

              }
            },
            {
              extend: 'csv',
              text: '<i class="fas fa-file-alt"></i>',
              exportOptions: {
                columns: ':visible'

              }
            },
            {
              extend: 'pdf',
              text: '<i class="fas fa-file-pdf"></i>',
              exportOptions: {
                columns: ':visible'

              }
            },
            {
              extend: 'print',
              text: '<i class="fa fa-fw fa-print"></i>',
              exportOptions: {
                columns: ':visible'

              }
            },
            {
              extend: 'colvis',
              text: '<i class="fa fa-fw fa-eye-slash"></i>',
              exportOptions: {
                columns: ':visible'

              }
            },
        ]
    } );
} );

</script>
@stop

@section('css')
<style type="text/css">
  <style>
     .content-wrapper {
          background-color : white;
      }
  </style>

  <style type="text/css">
    .table{
        font-size: 10.7px;
      }
  </style>

  <style type="text/css">
      .dataTables_wrapper .dt-buttons {
    float:none;
    text-align:center;
    margin-bottom: 30px;
  }
  </style>


@stop
