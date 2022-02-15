@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Files')

@section('content_header')
    @if (config('dynamic-extract.auth') ? Auth::user()->can(config('dynamic-extract.middleware.view_all')) : false)
     <a class="btn btn-social-icon btn-github" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/meusficheiros/all/deletefile')}}" onclick="return confirm('Are you sure you want to delete all items?');"><i class=" fas fa-trash-alt " style="color: red"></i> Delete All Files</a>
    @endif
@stop

@section('content')

 <div class="card">
   <div class="card-header border-0">
    <div class="d-flex justify-content-between">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-bookmark"></i> Files </strong></h5></center>
    </div>

    </div>
    <div class="card-body table-responsive no-padding ">

    <div class="position-relative mb-4">

     <table id="example" class="table table-striped table-bordered" style="width:100%">

        <thead >
        <tr>
            <th scope="col">ID</th>
            <th scope="col"><center>User</center></th>
            <th scope="col"><center>File</center></th>
            <th scope="col"><center>Details</center></th>
            <th scope="col"><center>Start</center></th>
            <th scope="col"><center>Time</center></th>
            <th scope="col"><center>Update</center></th>
            <th scope="col"><center>Actions</center></th>
        </tr>
        </thead>
        <tbody>
            @if($data)
                @foreach($data as $value)
                @if(config('dynamic-extract.auth') ? Auth::user()->can(config('dynamic-extract.middleware.view_all')) || (Auth::user()->can($value->can) && isset($value->can)) || Auth::user()->id == $value->user_id : true)
                    <tr>
                    <td>{{$value->id}}</td>
                    <td>{{$value->user->name ?? ''}} </td>
                    <td>{{$value->filename}}</td>
                    <td>
                        @if(isset($value->filterData))
                            @foreach($value->filterData as $key => $items )
                                <span class="badge bg-info"> <div class="text-uppercase">{{$key}}:</div>
                                @if(is_array($items))
                                    @foreach($items as $keyItem =>$item)
                                        {!! $item !!},
                                    @endforeach
                                @else
                                    {!! $items !!}
                                @endif
                                </span>
                            @endforeach
                        @endif
                    </td>
                    <td>{{$value->created_at}}</td>
                    <td>{{$value->created_at->diffInMinutes($value->updated_at)}} Minutes</td>
                    <td>{{$value->updated_at->diffForHumans()}}</td>
                    <td>
                        @if($value->status)
                        <a class="btn btn-default btn-sm" aria-hidden="true" onclick="return confirm('Are you sure you want to delete this item?');" href="{{url(config('dynamic-extract.prefix').'/meusficheiros/deletefile',$value->filename)}}" ><i class="fa fa-spinner fa-spin " style="color: red"></i> <i class="far fa-stop-circle"></i></a>
                        @else
                        <a class="btn btn-default btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/file/download',$value->filename)}}" ><i class="fas fa-download" style="color: green"></i> </a>
                        <a class="btn btn-default btn-sm" aria-hidden="true" onclick="return confirm('Are you sure you want to delete this item?');" href="{{url(config('dynamic-extract.prefix').'/meusficheiros/deletefile',$value->filename)}}" ><i class="fas fa-trash-alt" style="color: red"></i> </a>
                        @endif
                    </td>
                    </tr>
                @endif
                @endforeach
            @endif
        </tbody>
    </table>
  </div>
</div>
 </div>
@stop

@section('js')
<script type="text/javascript">
  setTimeout(function() {
    location.reload();
  }, {{config('dynamic-extract.interval')}});//30seg
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

</style>

@stop
