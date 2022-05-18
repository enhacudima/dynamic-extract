@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config Report')

@section('content_header')
      <a class="btn btn-social-icon btn-github"  href="{{ url(config('dynamic-extract.prefix').'/report/config/filtro') }}"><i class="fa  fa-arrow-left"></i></a>
      <a class="btn btn-social-icon btn-github"  data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fa  fa-plus"></i></a>
@stop

@section('content')
<div class="row">
<div class="col-md-3">
    <div class="list-group">
    <a href="{{url(config('dynamic-extract.prefix').'/report/config')}}" class="list-group-item list-group-item-action " aria-current="true">
        New Report
    </a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro')}}" class="list-group-item list-group-item-action">Group Filter</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/filtros')}}" class="list-group-item list-group-item-action active">Filter</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/list')}}" class="list-group-item list-group-item-action">Filter List</a>
    <a href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/columuns')}}" class="list-group-item list-group-item-action">Filter Columuns</a>
    </div>
</div>
 <div class="col-md-9">

 <div class="card card-solid card-default">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> Filter </strong></h5></center>

    </div>
    <div class="panel-body">

    <div class="card-body table-responsive no-padding">

     <table id="example" class="table table-striped table-bordered" style="width:100%">

        <thead >
        <tr>
            <th scope="col">ID</th>
            <th scope="col"><center>Name</center></th>
            <th scope="col">Columun/Table</th>
            <th scope="col">Type</th>
            <th scope="col"><center><i class="fa  fa-database"></i> Columuns</center></th>
            <th scope="col"><center><i class="fa  fa-bars"></i> lists</center></th>
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
                <td>{{$value->value}}</td>
                <td>{{$value->type}}</td>
                <td>
                    @foreach($value->columuns as $columun)
                        <span class="badge bg-primary">{{$columun->name}}</span>
                    @endforeach
                </td>
                <td>
                    @foreach($value->lists as $lists)
                        <span class="badge  badge-info">{{$lists->name}}</span>
                    @endforeach
                </td>
                <td>
                      <a class="btn btn-success btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/filtro/filtros/edit',$value->id)}}" ><i class="fas fa-edit"></i></a>
                </td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at->diffForHumans()}}</td>
                <td>{{$value->user->name ?? ''}}</td>
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
            <h5 class="modal-title" id="exampleModalLabel">Create New Filter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/filtro/filtros/new/store')}}">
          @csrf
            <div class="modal-body">
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">

                <input type="text" name="name" required autofocus="" class="form-control" placeholder="Name"><br>
                <input type="text" name="value" required autofocus="" class="form-control" placeholder="Columun/Table">
                <small>Table name for filter type "columuns"</small>
                <br>
                <br>
                <select required="" name="type" id="type" required autofocus="" class="form-control type">
                  <option value="" disabled="" selected="">Select type</option>
                  <option value="date">date</option>
                  <option value="pesquisa">search</option>
                  <option value="list">list</option>
                  <option value="group">group by</option>
                  <option value="columuns">columuns</option>
                  <option value="<=">less than "<="</option>
                  <option value=">=">greater than ">="</option>
                </select>
                <div class="flexCheckLists" id="flexCheckLists" style="display: none;">
                    <div class="form-group">
                        <br/>
                        @if(config('dynamic-extract.lists'))
                            @foreach(config('dynamic-extract.lists') as $groups)

                            <strong>{{$groups['group_name']}}</strong>
                               @foreach($groups['options'] as $value)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="list_columuns[]" value="{{ $value }}" id=" {{ $value }}" >
                                    <label class="form-check-label" for=" {{ $value }}">
                                        {{ $value }}
                                    </label>
                                </div>
                               @endforeach
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="flexCheckColumuns" id="flexCheckColumuns" style="display: none;">
                    <div class="form-group">
                        <br/>
                        @if(config('dynamic-extract.columuns'))
                            @foreach(config('dynamic-extract.columuns') as $groups)

                            <strong>{{$groups['group_name']}}</strong>
                               @foreach($groups['options'] as $value)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="list_columuns[]" value="{{ $value }}" id=" {{ $value }}">
                                    <label class="form-check-label" for=" {{ $value }}">
                                        {{ $value }}
                                    </label>
                                </div>
                               @endforeach
                            @endforeach
                        @endif
                    </div>
                </div>
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
    $('#type').on('change', function() {
        //console.log(this.value)
        if (this.value=="columuns"){
        $('#flexCheckLists').css('display', 'none');
        $('#flexCheckColumuns').css('display', 'block');
        }
        else if(this.value=="list"){
        $('#flexCheckLists').css('display', 'block');
        $('#flexCheckColumuns').css('display', 'none');
        }
        else{
        $('#flexCheckLists').css('display', 'none');
        $('#flexCheckColumuns').css('display', 'none');
        }
    });

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

</style>

@stop
