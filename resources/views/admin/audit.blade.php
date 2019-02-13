@extends('layouts.admin')
@section('title', 'Audit')

@section('content')
<!-- Page Content -->
<div class="row">
    <div class="col-md-10">
        <h1>Audit Trail</h1>
    </div>
    <div class="col-md-2">

    </div>
</div>
<div class="row">
    <div class='col-md-6'>
        <div class="well well-lg">
            <div class="row">
                <div class="text-center">
                    <h2>Admin</h2>
                </div>
            </div>
            <table class="table  table-responsive" id="display">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr>
                        <td>{{$admin->created_at}}</td>
                        <td>{{$admin->action}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class='col-md-6'>
        <div class="well well-lg">
            <div class="row">
                <div class="text-center">
                    <h2>User</h2>
                </div>
            </div>
            <table class="table  table-responsive" id="displayUsers">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{$user->created_at}}</td>
                        <td>{{$user->action}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>	
@endsection