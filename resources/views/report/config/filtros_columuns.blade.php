@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config Columun')

@section('content_header')
    <h1>
      <a class="btn btn-social-icon btn-github"  href="{{ url(config('dynamic-extract.prefix').'/report/config/filtro/filtros') }}"><i class="fa  fa-arrow-left"></i></a>
      <a class="btn btn-social-icon btn-github"  data-bs-toggle="modal" data-bs-target="#exampleModal"  ><i class="fa  fa-plus"></i></a>
    </h1>
@stop

@section('content')

<div class="row">
<div class="col-md-3">
    <div class="list-group">
    <a href="{{url(config('dynamic-extract.prefix').'/report/config')}}" class="list-group-item list-group-item-action " aria-current="true">
        New Report
    </a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro')}}" class="list-group-item list-group-item-action">Group Filter</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/filtros')}}" class="list-group-item list-group-item-action">Filter</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/list')}}" class="list-group-item list-group-item-action">Filter List</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/columuns')}}" class="list-group-item list-group-item-action active">Filter Columuns</a>
    </div>
</div>
 <div class="col-md-9">

 <div class="card card-solid card-default">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> Filter Columuns </strong></h5></center>

    </div>
    <div class="panel-body">

    <div class="card-body table-responsive no-padding">

     <table id="example" class="table table-striped table-bordered" style="width:100%">

        <thead >
        <tr>
            <th scope="col">ID</th>
            <th scope="col"><center>Name</center></th>
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
                <td>
                    <span class="badge bg-primary">{{$value->filtro->name}}</span>
                </td>
                <td>
                      <a class="btn btn-success btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/columuns/edit',$value->id)}}" ><i class="fas fa-edit"></i> Modify</a>
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
            <h5 class="modal-title" id="exampleModalLabel">Create New Columun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/filtro/columuns/store')}}">
          @csrf
            <div class="modal-body">
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">

                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" name="name" required autofocus="" class="form-control" placeholder="Name"><br>
                <label for="exampleFormControlInput1" class="form-label">Filter</label>
                <select required="" name="report_new_filtro_id" required autofocus="" class="form-control">
                  <option value="" disabled="" selected="">Select filter</option>
                  @foreach($filtros as $filtro)
                    <option value="{{$filtro->id}}">{{$filtro->name}}</option>
                  @endforeach
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
