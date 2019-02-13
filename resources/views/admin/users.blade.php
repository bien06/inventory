@extends('layouts.admin')
@section('title', 'User Management')

@section('content')

<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<div class="row">
    <div class="col-md-10">
        <h1>User Management</h1>
    </div>
    <div class="col-md-2"></div>
</div>

<div class="panel with-nav-tabs panel-default">
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1default" data-toggle="tab">Users</a></li>
            <li class=""><a href="#tab2default" data-toggle="tab">Branches</a></li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab1default">

                <!-- users -->
                <div class="row">

                    <div class='col-md-8'>

                        <div class="well well-lg">
                            <div class="row">
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-success " data-toggle="modal" data-target="#Add"><i class="fa fa-user-plus" aria-hidden="true"></i> Add User</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="row">
                                    <div class="text-center">
                                        <h2>List of Users</h2>
                                    </div>
                                </div>
                                <table id="userTbl" class="table   table-responsive">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Assigned Branch</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr class="user{{$user->id}}" data-id='{{$user->id}}' data-name="{{$user->name}}" data-email="{{$user->email}}" data-location="{{$user->branch_id}}" data-status="{{$user->status}}" data-password="{{$user->password}}">
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{App\Branch::find($user->branch_id)->name}}</td>
                                            <td>{{$user->email}}</td>
                                            @if($user->status == "Active")
                                            <td><span class="label label-success">{{$user->status}}</span></td>
                                            @else
                                            <td><span class="label label-danger">{{$user->status}}</span></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- update user -->
                    <div class="col-md-4">
                        <div class="well well-lg">

                            <form class="form-horizontal updateUser" id="updateloc" method="post" action="{{url('users/update')}}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >

                                <legend>Update User</legend>
                                <fieldset>

                                    @if(session()->has('updateU'))
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                        $.alert({
                                        type: 'green',
                                                icon: 'fa fa-check-circle',
                                                btnClass: 'btn-green',
                                                title: 'Success Update',
                                                backgroundDismiss: true,
                                                content: 'Successfully updated user!'
                                        });
                                        });
                                    </script>                            
                                    @elseif(session()->has('addU'))
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                        $.alert({
                                        type: 'green',
                                                icon: 'fa fa-check-circle',
                                                btnClass: 'btn-green',
                                                title: 'Success Update',
                                                backgroundDismiss: true,
                                                content: 'Successfully added user!'
                                        });
                                        });
                                    </script>                            
                                    @elseif(session()->has('updateS'))
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                        $.alert({
                                        type: 'green',
                                                icon: 'fa fa-check-circle',
                                                btnClass: 'btn-green',
                                                title: 'Success Update',
                                                backgroundDismiss: true,
                                                content: 'Successfully updated user status!'
                                        });
                                        });
                                    </script>                            

                                    @endif

                                    <div class="form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label" for="name">User ID:</label>  
                                        <div class="col-md-8">
                                            <input id="eid" name="user_id" type="text" readonly=""  class="form-control input-md">
                                            @if ($errors->has('user_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('user_id') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('updated_name') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">Full Name:</label>
                                        <div class="col-md-8">
                                            <input id="ename" type="text" class="form-control" name="updated_name" >
                                            @if ($errors->has('updated_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('updated_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('updated_location') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label" for="location">Assigned Branch:</label>
                                        <div class="col-md-8">
                                            <select id="elocation" name="updated_location" class="form-control">
                                                <option value="0">Select a Branch</option>
                                                @foreach($locs as $loc)
                                                <option value="{{$loc->id}}">{{$loc->name}}</option>  
                                                @endforeach
                                            </select>
                                            @if ($errors->has('updated_location'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('updated_location') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('updated_email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">Email Address:</label>
                                        <div class="col-md-8">
                                            <input id="eemail" type="email" class="form-control" name="updated_email" >
                                            @if ($errors->has('updated_email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('updated_email') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!--                                        <div class="form-group {{ $errors->has('updated_password') ? ' has-error' : '' }}">
                                                                                <label for="password" class="col-md-4 control-label">Password:</label>
                                                                                <div class="col-md-8">
                                                                                    <input id="password" type="password" class="form-control" name="updated_password" >
                                                                                    @if ($errors->has('updated_password'))
                                                                                    <span class="help-block">
                                                                                        <strong>{{ $errors->first('updated_password') }}</strong>
                                                                                    </span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>-->

                                    <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label for="status" class="col-md-4 control-label">Status</label>
                                        <div class="col-md-8">
                                            <input id="estatus" type="text" class="form-control" name="status" readonly="">
                                            @if ($errors->has('status'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button id="update" name="submit" disabled="" class="btn btn-success" value="update" onclick="return confirm('Are you sure you want to update this user?')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update</button>
                                            <button id="changestat" name="submit" disabled="" class="btn btn-primary" value="changestat" onclick="return confirm('Are you sure you want to update this user\'s status?')"><i class="fa fa-refresh" aria-hidden="true"></i> Update Status</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>

                        </div>
                    </div>
                    <!-- end update user -->
                </div>
            </div>

            <div class="tab-pane fade" id="tab2default">

                <!-- location -->
                <div class="row">
                    <div class='col-md-8'>
                        <div class="well well-lg">
                            <div class="row">
                                <form class="form-horizontal" method="post" action="{{ url('users/add_location') }}" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <fieldset>
                                        <div class="form-group">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-7">
                                                <input id="location_name" name="location_name" placeholder="Enter a Branch" required class="form-control input-md" type="text">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-success" >Add Branch</button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="row">
                                <div class="text-center">
                                    <h2>List of Branches</h2>
                                </div>
                            </div>
                            <table id="loc" class="table  table-responsive">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Branch</th>
                                        <th>Received Items</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($locs as $loc)
                                    <tr class="loc{{$loc->id}}" data-id="{{$loc->id}}" data-name="{{$loc->name}}" data-limit="{{$loc->limit}}">
                                        <td>{{$loc->id}}</td>
                                        <td>{{$loc->name}}</td>
                                        <td>
                                            {{number_format($loc->received)}}&nbsp;&nbsp;
                                            <button onclick="myFunction2({{$loc->id}})" id="{{$loc->id}}" class="doo btn btn-default btn-xs"><span class="caret"></span></button> 

                                            <div id="myDIV2{{$loc->id}}" style="display: none; height:50%; overflow-y: auto;">
                                                <table>
                                                    <tr>
                                                        <th>Item Name </th>
                                                        <th>Item Count </th>
                                                    </tr>
                                                    @foreach(App\ItemAssign::where('branch_id', $loc->id)->get() as $item)
                                                    <tr>
                                                        <td>{{App\Item::find($item->item_id)->item_name}}</td>
                                                        <td>{{number_format($item->item_count)}}</td>
                                                    </tr>
                                                    @endforeach                        
                                                </table> 
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="well well-lg">
                            <form class="form-horizontal" method="post" action="{{url('users/update_location')}}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <legend>Update Branch</legend>

                                <fieldset>

                                    @if(session()->has('addL'))
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                        $.alert({
                                        type: 'green',
                                                icon: 'fa fa-check-circle',
                                                btnClass: 'btn-green',
                                                title: 'Success Update',
                                                backgroundDismiss: true,
                                                content: 'Successfully added branch!'
                                        });
                                        });
                                    </script>                            
                                    @elseif(session()->has('updateL'))
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                        $.alert({
                                        type: 'green',
                                                icon: 'fa fa-check-circle',
                                                btnClass: 'btn-green',
                                                title: 'Success Update',
                                                backgroundDismiss: true,
                                                content: 'Successfully updated branch!'
                                        });
                                        });
                                    </script>                            
                                    @endif                    

                                    <div class="form-group {{ $errors->has('branch_id') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">Branch ID:</label>
                                        <div class="col-md-8">
                                            <input id="elid" name='branch_id' type="text" class="form-control" readonly="">
                                            @if ($errors->has('branch_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('branch_id') }}</strong>
                                            </span>
                                            @endif                            
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('new_location') ? ' has-error' : '' }}">
                                        <label for="new_location" class="col-md-4 control-label">Branch Name:</label>
                                        <div class="col-md-8">
                                            <input id="elocation_name" type="text" class="form-control" name="new_location" >
                                            @if ($errors->has('new_location'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('new_location') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-group">
                                    <div class="col-md-12 text-center">
                                        <button id="submit" name="submit" disabled=""class="btn btn-success" onclick="return confirm('Are you sure you want to update this branch?')"><i class="fa fa-pencil-square-o " aria-hidden="true"></i> Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add user modal -->
<div id="Add" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <form class="form-horizontal" method="post" action="{{url('users/add')}}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Add User</h4>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <fieldset>

                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Full Name:</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  required autofocus>
                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('location') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label" for="chooseitem">Assigned Branch:</label>
                            <div class="col-md-6">
                                <select id="location" name="location" class="form-control" required>
                                    <option value="0">Select a Branch</option>
                                    @foreach($locs as $loc)
                                    <option value="{{$loc->id}}">{{$loc->name}}</option>  
                                    @endforeach
                                </select>
                                @if ($errors->has('location'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email Address:</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password:</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" required>
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Confirm Password:</label>
                            <div class="col-md-6">
                                <input id="confirm_password" type="password" class="form-control" name="confirm_password" value="{{ old('confirm_password') }}" required>
                                @if ($errors->has('confirm_password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('confirm_password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="modal-footer">
                    <label class="col-md-4 control-label" for="submit"></label>
                    <div class="col-md-8">
                        <button id="submit" name="submits" class="btn btn-success">Submit</button>
                        <button id="cancel" name="cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- end add user modal -->

@endsection

<!-- script for location  -->
@section('js')
<script type="text/javascript">
    $(document).ready(function () {
    var table = $('#loc').DataTable();
    $('#loc').on('click', 'tr', function () {
    if ($(this).hasClass('selected')) {
    $('#elid').val('');
    $('#elocation_name').val('');
    $('#elimit').val('');
    $("button[name='submit']").attr('disabled', 'disabled');
    $(this).removeClass('selected');
    } else {
    $('#elid').val($(this).data('id'));
    $('#elocation_name').val($(this).data('name'));
    $('#elimit').val($(this).data('limit'));
    $("button[name='submit']").removeAttr('disabled');
    table.$('tr.selected').removeClass('selected');
    $(this).addClass('selected');
    }
    });
    });</script>

<script type="text/javascript">
    function myFunction(id) {
    var x = document.getElementById('myDIV' + id);
    if (x.style.display === 'none') {
    x.style.display = 'block';
    }
    else {
    x.style.display = 'none';
    }
    }

    function myFunction2(id) {
    var y = document.getElementById('myDIV2' + id);
    if (y.style.display === 'none') {
    y.style.display = 'block';
    }
    else {
    y.style.display = 'none';
    }
    }

</script> 

<script type="text/javascript">
    $(function () {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    localStorage.setItem('lastTab', $(this).attr('href'));
    });
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
    $('[href="' + lastTab + '"]').tab('show');
    }
    });
</script>    

@endsection
