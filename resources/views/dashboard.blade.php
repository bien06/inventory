@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

<!-- CONTAINER FLUID -->
<br><br>
<div class="wrapper">
    <!-- PANELS -->
    <div class="row">
        <!-- stocks -->
        <div class="col-lg-4">
            @if($itemcount>0)
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><h1>{{$itemcount}}</h1></div>
                            <div><h4>New Stock(s)</h4></div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><h1>{{$itemcount}}</h1></div>
                            <div><h4>New Stock(s)</h4></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- users -->
        <div class="col-lg-4">
            @if($usercount > 0)
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><h1>{{$usercount}}</h1></div>
                            <div><h4>New User(s)</h4></div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><h1>{{$usercount}}</h1></div>
                            <div><h4>New User(s)</h4></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- assignments -->
        <div class="col-lg-4">
            @if($assigncount)
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><h1>{{$assigncount}}</h1></div>
                            <div><h4>New Assignment(s)</h4></div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div><h1>{{$assigncount}}</h1></div>
                            <div><h4>New Assignment(s)</h4></div>
                        </div>
                    </div>
                </div>                
            </div>
            @endif
        </div>
        <!-- BALANCE -->
        <!-- TABLES -->
        <div class="col-lg-12">
            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tabdefault" data-toggle="tab">Stock Overview</a></li>
                        <li><a href="#tab1default" data-toggle="tab">New Stock(s)</a></li>
                        <li><a href="#tab2default" data-toggle="tab">New User(s)</a></li>
                        <li><a href="#tab3default" data-toggle="tab">New Assignment(s)</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <!-- stock overview -->
                        <div class="tab-pane fade in active" id="tabdefault">
                            <table class="table table-hover table-responsive" id="stock">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Branch</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $data)
                                    <tr>
                                        @if($data->balance <= $data->limits)
                                        <td style="background-color: #cc0000; color:white;">{{App\Item::find($data->item_id)->item_name}} </td>
                                        <td style="background-color: #cc0000; color:white;">{{App\Branch::find($data->branch_id)->name}}</td>
                                        <td style="background-color: #cc0000; color:white;">{{number_format($data->balance)}}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @foreach($datas as $data)
                                    <tr>
                                        @if($data->balance > $data->limits)
                                        <td>{{App\Item::find($data->item_id)->item_name}}</td>
                                        <td>{{App\Branch::find($data->branch_id)->name}}</td>
                                        <td>{{number_format($data->balance)}}</td>
                                        @endif
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="text-right">{{ $datas->links() }}</div>                                
                        </div>

                        <!-- new stock -->
                        <div class="tab-pane fade" id="tab1default">
                            <table class="table table-hover table-responsive" id='newstock'>
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Item Count</th>
                                        <th>Assigned Count</th>
                                        <th>Remaining Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->item_name}}</td>
                                        <td>{{number_format($item->total_count)}}</td>
                                        <td>{{number_format($item->assigned_count)}}</td>
                                        <td>{{number_format($item->remaining)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- new users -->
                        <div class="tab-pane fade" id="tab2default">
                            <table class="table table-hover table-responsive" id='newuser'>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Assigned Branch</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{(App\Branch::find($user->branch_id)->name)}}</td>
                                        <td>{{$user->email}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <!-- new assignments -->
                        <div class="tab-pane fade" id="tab3default">
                            <table class="table table-hover table-responsive" id='assigns'>
                                <thead>
                                    <tr>
                                        <th>Branch</th>
                                        <th>Item Name</th>
                                        <th>Limit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assigns as $mat)
                                    <tr>
                                        <td>{{App\Branch::find($mat->branch_id)->name}}</td>
                                        <td>{{App\Item::find($mat->item_id)->item_name}}</td>
                                        <td>{{number_format($mat->limits)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF TABLES -->
    </div>
    <!-- END OF BALANCE -->
</div>
<!-- END OF CONTAINNER FLUID -->
@endsection

