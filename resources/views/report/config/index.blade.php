@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config Report')

@section('content_header')
      <a class="btn btn-social-icon btn-github"  href="{{ url(config('dynamic-extract.prefix').'/report/new') }}"><i class="fa  fa-arrow-left"></i></a>
      <a class="btn btn-social-icon btn-github"  data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fa  fa-plus"></i></a>
      <a class="btn btn-social-icon btn-github " href="{{ url(config('dynamic-extract.prefix').'/report/config/filtro') }}"><i class="fa  fa-cog"></i></a>

@stop

@section('content')
<div class="row">
<div class="col-md-3">
    <div class="list-group">
    <a href="{{url(config('dynamic-extract.prefix').'/report/config')}}" class="list-group-item list-group-item-action active" aria-current="true">
        New Report
    </a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro')}}" class="list-group-item list-group-item-action">Group Filter</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/filtros')}}" class="list-group-item list-group-item-action">Filter</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/list')}}" class="list-group-item list-group-item-action">Filter List</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/columuns')}}" class="list-group-item list-group-item-action">Filter Columuns</a>
    </div>
</div>
 <div class="card col-md-9">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> Configuration </strong></h5></center>

    </div>
    <div class="panel-body">

    <div class="card-body table-responsive no-padding">

     <table id="example" class="table table-striped table-bordered" style="width:100%">

        <thead >
        <tr>
            <th scope="col">ID</th>
            <th scope="col"><center>Name</center></th>
            <th scope="col"><center>Comments</center></th>
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
                <td>{{$value->comments}}</td>
                <td>{{$value->table->name}}</td>
                <td>
                @if(isset($value->filtro))
                  <span class="badge bg-primary">{{$value->filtro_r->name}}</span>
                @endif
                </td>
                <td>
                    @if(!$value->status)
                    <a class="btn btn-danger btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/delete',$value->id)}}" onclick="return confirm('Are you sure you want to active this item?');" > <i class="fas fa-lock-open"></i></a>
                    @else
                      <a class=" btn btn-success btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/edit',$value->id)}}" ><i class="fas fa-edit"></i></a>
                      <a class="btn btn-danger btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/delete',$value->id)}}" onclick="return confirm('Are you sure you want to deactivate this item?');" ><i class="fas fa-lock"></i></a>
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


  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create New Report</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/store/new')}}">
          @csrf
            <div class="modal-body">
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">

                <input type="text" name="name" required autofocus="" class="form-control" placeholder="Name"><br>
                <input type="text" name="comments" required autofocus="" class="form-control" placeholder="Comments"><br>
                <input type="text" name="can" required autofocus="" class="form-control" placeholder="Permissions"><br>
                <select name="filtro" class="form-control">
                  <option value="" disabled="" selected="">Select Group filter..</option>
                  @foreach($filtros as $filtro)
                  <option value="{{$filtro->id}}">{{$filtro->name}}</option>
                  @endforeach
                  <option value="" >No filter..</option>
                </select>
                <br>
                <select name="table_name" required="" autofocus="" class="form-control">
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
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        "order": [[ 0, "desc" ]],
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "columnDefs": [
                        { "type": "date-eu", "targets": 4 }
                      ],
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

</style>

@stop
