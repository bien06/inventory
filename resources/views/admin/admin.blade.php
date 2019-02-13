@extends('layouts.admin')
@section('title', 'Admin Management')

@section('content')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<div class="row">
    <div class="col-md-10">
        <h1>Admin Management</h1>
    </div>
    <div class="col-md-2"></div>
</div>

<div class="row">
    <div class='col-md-8'>

        <div class="well well-lg">
            <div class="row">
                <div class="col-md-1">
                    <button type="button" class="btn btn-success " data-toggle="modal" data-target="#Add"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Admin</button>
                </div>
            </div>

            <div class="row">
                <div class="row">
                    <div class="text-center">
                        <h2>List of Administrators</h2>
                    </div>
                </div>
                <table id="userTbl" class="table   table-responsive">
                    <thead>
                        <tr>
                            <th>Admin ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr class="admin{{$admin->id}}" data-id='{{$admin->id}}' data-name="{{$admin->name}}" data-email="{{$admin->email}}" data-location="{{$admin->branch_id}}" @if($admin->is_active == 1) data-status="Active" @else data-status="Inactive" @endif data-password="{{$admin->password}}">
                            <td>{{$admin->id}}</td>
                            <td>{{$admin->name}}</td>
                            <td>{{$admin->email}}</td>
                            @if($admin->role == '1')
                            <td>Super Admin</td>
                            @else
                            <td>Admin</td>
                            @endif
                            
                            @if($admin->is_active == "1")
                            <td><span class="label label-success">Active</span></td>
                            @else
                            <td><span class="label label-danger">Inactive</span></td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- update admin -->
    <div class="col-md-4">
        <div class="well well-lg">

            <form class="form-horizontal updateUser" id="updateloc" method="post" action="{{url('admin/update')}}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >

                <legend>Update Administrator</legend>
                <fieldset>

                    @if(session()->has('updateA'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                type: 'green',
                                icon: 'fa fa-check-circle',
                                btnClass: 'btn-green',
                                title: 'Success Update',
                                backgroundDismiss: true,
                                content: 'Successfully updated administrator!'
                            });
                        });
                    </script>                            
                    @elseif(session()->has('addA'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                type: 'green',
                                icon: 'fa fa-check-circle',
                                btnClass: 'btn-green',
                                title: 'Success Update',
                                backgroundDismiss: true,
                                content: 'Successfully added administrator!'
                            });
                        });
                    </script>                            
                    @elseif(session()->has('updateSA'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                type: 'green',
                                icon: 'fa fa-check-circle',
                                btnClass: 'btn-green',
                                title: 'Success Update',
                                backgroundDismiss: true,
                                content: 'Successfully updated administrator\'s status!'
                            });
                        });
                    </script>                            

                    @endif

                    <div class="form-group {{ $errors->has('admin_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="name">Administrator ID:</label>  
                        <div class="col-md-8">
                            <input id="eid" name="admin_id" type="text" readonly=""  class="form-control input-md">
                            @if ($errors->has('admin_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('admin_id') }}</strong>
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
                            <button id="update" name="submit" disabled="" class="btn btn-success" value="update" onclick="return confirm('Are you sure you want to update this admin?')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update</button>
                            <button id="changestat" name="submit" disabled="" class="btn btn-primary" value="changestat" onclick="return confirm('Are you sure you want to update this admin\'s status?')"><i class="fa fa-refresh" aria-hidden="true"></i> Update Status</button>
                        </div>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
    <!-- end update user -->
</div>

<!-- add user modal -->
<div id="Add" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <form class="form-horizontal" method="post" action="{{url('admin/add')}}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Add Administrator</h4>
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
                                @if ($errors->has('password'))
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
