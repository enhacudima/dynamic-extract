@extends('extract-view::vendor.layouts.master')

@section('title','Dynamic Extract | Config Edit Report')

@section('content_header')
    <h1><a class="btn btn-social-icon btn-github"  href="{{url(config('dynamic-extract.prefix').'/report/config/external/api')}}"><i class="fa  fa-arrow-left"></i></a>
    <a class="btn btn-social-icon btn-github"  data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fa  fa-plus"></i></a>
    </h1>
@stop

@section('content')

 <div class="card border-dark">
   <div class="card-header">
              <center><h5 class="card-title"><strong><i class="fa fa-fw fa-folder-open"></i> Schedule  for "{{$data->name}}" </strong></h5></center>

    </div>
    <div class="panel-body">

    <div class="card-body table-responsive no-padding">

     <table id="example" class="table table-striped table-bordered" style="width:100%">

        <thead >
        <tr>
            <th scope="col">ID</th>
            <th scope="col"><center>Method</center></th>
            <th scope="col"><center>Start</center></th>
            <th scope="col"><center>End</center></th>
            <th scope="col"><center>Updated</center></th>
            <th scope="col">Actions</th>
            <th scope="col">Create</th>
            <th scope="col">Time</th>
            <th scope="col">User</th>

        </tr>
        </thead>
        <tbody>
            @if(isset($schedule))
                @foreach($schedule as $value)
                <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->method}}</td>
                <td>{{$value->time_start}}</td>
                <td>{{$value->time_end}}</td>
                <td>{{ \Carbon\Carbon::parse($value->expire_at)->diffForHumans() }}</td>
                <td>
                    <a class="btn btn-danger btn-sm" aria-hidden="true" href="{{url(config('dynamic-extract.prefix').'/report/config/external/api/schedule/delete',$value->id)}}" onclick="return confirm('Are you sure you want to deactivate this item?');" ><i class="fas fa-trash-alt"></i></a>
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


  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create New Schedule</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
          <form method="post" id="list" action="{{url(config('dynamic-extract.prefix').'/report/config/external/api/schedule/add')}}">
          @csrf
            <div class="modal-body">
                <input type="" name="user_id" value="{{Auth::user()->id ?? 0}}" hidden="">
                <input type="" name="report_id" value="{{$id}}" hidden="">
                <label for="method" class="form-label">Method</label>
                <select name="method" id="method" required="" autofocus="" class="form-control">
                  <option value="" disabled="" selected="">Select method..</option>
                  <option value="Allow">Allow</option>
                  <option value="Deny">Deny</option>
                </select>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label for="time_start" class="form-label">Start</label>
                        <input type="time" name="time_start" id="time_start" required autofocus="" class="form-control" placeholder="Name"><br>
                    </div>

                    <div class="col-md-6">
                        <label for="time_end" class="form-label">End</label>
                        <input type="time" name="time_end" id="time_end" required autofocus="" class="form-control" placeholder="Name"><br>
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


@stop
@section('js')
@stop
