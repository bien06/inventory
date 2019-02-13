@extends('layouts.admin')
@section('title', 'Queries')

@section('content')

<div class="row">
    <div class="col-md-10">
        <h1>Queries</h1>
    </div>
    <div class="col-md-2"></div>
</div>

<div class="well well-lg" id='content'>
    <div class="row">
        <div class="panel with-nav-tabs panel-default">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Date Summary</a></li>
                    <li><a href="#tab2" data-toggle="tab">Stock Summary</a></li>
                    @if(Auth::guard('admin_user')->user()->role == 1)                    
                    <li><a href="#tab3" data-toggle="tab">Admin Queries</a></li>
                    @endif
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <!-- date range summary -->
                    <div class="tab-pane fade in active" id="tab1">
                        <div class="row">
                            <form class="form-horizontal" method="post" action="{{url('query')}}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <div class="col-md-12 text-center">
                                    From: <input type="text" id="dateFrom" name="fromdate" required> &nbsp;&nbsp;&nbsp;
                                    To: <input type="text" id="dateTo" name="todate" required> &nbsp;&nbsp;&nbsp;
                                    <button id="submits" name="submits" class="btn btn-success">Search</button>
                                </div>
                            </form>
                        </div>

                        <div class="row">
                            <div class="text-center">
                                <h2>Summary 
                                    @if($fromdate == NULL && $todate == NULL)
                                    for  {{Carbon\Carbon::now()->today()->format('jS \o\f F, Y')}}</h2>
                                @else
                                from {{$fromdate}} to {{$todate}}
                                @endif
                            </div>
                        </div>
                        <table class="table table-responsive" id='dailyqueries' cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Assigned Branch</th>
                                    <th>User </th>
                                    <th>Date</th>
                                    <th>Good</th>
                                    <th>Reject</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daily as $day)
                                <tr data-id='{{$day->id}}' data-item='{{$day->item_assign}}' data-branch='{{$day->branch}}' data-count='{{$day->item_count}}' data-good='{{$day->good}}' data-rejected='{{$day->rejected}}' data-balance='{{$day->balance}}'>
                                    <td>{{App\Item::find(App\ItemAssign::find($day->item_assign)->item_id)->item_name}}</td>
                                    <td>{{App\Branch::find(App\User::find($day->user_id)->branch_id)->name}}</td>
                                    <td>{{App\User::find($day->user_id)->name}}</td>
                                    <td>{{$day->updated_at->toDateString()}}</td>
                                    <td>{{number_format($day->good)}}</td>
                                    <td>{{$day->rejected}}</td>
                                    @if($day->reason == NULL)
                                    <td><h5><i>N/A</i></h5></td>
                                    @else
                                    <td>{{$day->reason}}</td>
                                    @endif
                                    @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>{{number_format($totalgood2)}}</th>
                                    <th>{{number_format($totalrejected2)}}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- stock summary -->
                    <div class="tab-pane fade" id="tab2">
                        <div class="row">
                            <div class="text-center">
                                <h2>As of {{Carbon\Carbon::now()->today()->format('jS \o\f F, Y')}}</h2>
                            </div>
                        </div>
                        <table class="table table-responsive" cellspacing="0" width="100%" id='queries'>
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Assigned Branch</th>
                                    <th>Received Items</th>
                                    <th>Balance</th>
                                    <th>Good</th>
                                    <th>Reject</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assigns as $assign)
                                <tr data-id='{{$assign->id}}' data-item='{{$assign->item_id}}' data-branch='{{$assign->branch_id}}' data-count='{{$assign->item_count}}' data-good='{{$assign->good}}' data-rejected='{{$assign->rejected}}' data-balance='{{$assign->balance}}'>
                                    <td>{{App\Item::find($assign->item_id)->item_name}}</td>
                                    <td>{{App\Branch::find($assign->branch_id)->name}}</td>
                                    <td>{{number_format($assign->item_count)}}</td>
                                    <td>{{number_format($assign->balance)}}</td>
                                    <td>{{number_format($assign->good)}}</td>
                                    @if($assign->rejected > 0)
                                    <td>{{$assign->rejected}}</td>
                                    <td>
                                        <button onclick="myFunction({{$assign->id}})" id="{{$assign->id}}" class="view-modal btn btn-link" data-toggle="modal" data-target="#viewmodal" data-id="{{$assign->id}}" >View</button>
                                        <div id="myDIV{{$assign->id}}" style="display: none; height:50%; overflow-y: auto;">
                                            <table cellspacing="0" width="100%" >
                                                <tr>
                                                    <th>Rejected by </th>
                                                    <th>Reject Count </th>
                                                    <th>Reason</th>
                                                    <th>Action</th>
                                                </tr>
                                                @foreach(App\UserAssign::where('item_assign', $assign->id)->where('rejected', '<>', 0)->get() as $mehe)
                                                <tr>
                                                    <td>{{App\User::find($mehe->user_id)->name}}</td>
                                                    <td>{{number_format($mehe->rejected)}}</td>
                                                    <td>{{$mehe->reason}}</td>
                                                    <td>{{$mehe->action}}</td>
                                                </tr>
                                                @endforeach                        
                                            </table> 
                                        </div>
                                    </td>                                    
                                    @else
                                    <td>{{number_format($assign->rejected)}}</td>
                                    <td></td>                                    
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- admin query -->
                    @if(Auth::guard('admin_user')->user()->role == 1)
                    <div class="tab-pane fade" id="tab3">
                        <div class="row">
                            <form class="form-horizontal" method="post" action="{{url('query/search')}}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <div class="col-md-12 text-center">
                                    From: <input type="text" id="dateFrom2" name="fromdate2" required> &nbsp;&nbsp;&nbsp;
                                    To: <input type="text" id="dateTo2" name="todate2" required> &nbsp;&nbsp;&nbsp;
                                    <button id="submit" name="submit" class="btn btn-success">Search</button>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="text-center">
                                <h2>Admin Summary 
                                    @if($fromdate2 == NULL && $todate2 == NULL)
                                    for  {{Carbon\Carbon::now()->today()->format('jS \o\f F, Y')}}</h2>
                                @else
                                from {{$fromdate2}} to {{$todate2}}
                                @endif
                            </div>
                        </div>
                        <table class="table table-responsive" id='adminqueries' cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Branch</th>
                                    <th>Admin Name </th>
                                    <th>Assign Count</th>
                                    <th>Action</th>
                                    <th>Date</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $admin)
                                <tr>
                                    <td>
                                        @if($admin->item_id == NULL)
                                        @else                                        
                                        {{App\Item::find($admin->item_id)->item_name}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($admin->branch_id == NULL)
                                        @else                                                                                
                                        {{App\Branch::find($admin->branch_id)->name}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($admin->admin_id == NULL)
                                        @else
                                        {{App\AdminUser::find($admin->admin_id)->name}}</td>
                                    @endif
                                    <td>{{number_format($admin->count)}}</td>
                                    <td>{{$admin->action}}</td>
                                    <td>{{$admin->created_at}}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
//for daily queries
$(document).ready(function () {
$("#dateFrom").datepicker({
maxDate: '0',
        dateFormat: "yy-mm-dd",
        onSelect: function () {
        var dt1 = $('#dateFrom');
        var dt2 = $('#dateTo');
        var minDate = $(this).datepicker('getDate');
        //minDate of dt2 datepicker = dt1 selected day
        dt2.datepicker('setDate', minDate);
        //first day which can be selected in dt2 is selected date in dt1
        dt2.datepicker('option', 'minDate', minDate);
        }

});
$("#dateTo").datepicker({
maxDate: '0',
        dateFormat: "yy-mm-dd"
});
});
//for admin queries
$(document).ready(function () {
$("#dateFrom2").datepicker({
maxDate: '0',
        dateFormat: "yy-mm-dd",
        onSelect: function () {
        var dt1 = $('#dateFrom2');
        var dt2 = $('#dateTo2');
        var minDate = $(this).datepicker('getDate');
        //minDate of dt2 datepicker = dt1 selected day
        dt2.datepicker('setDate', minDate);
        //first day which can be selected in dt2 is selected date in dt1
        dt2.datepicker('option', 'minDate', minDate);
        }

});
$("#dateTo2").datepicker({
maxDate: '0',
        dateFormat: "yy-mm-dd"
});
});
$(document).ready(function () {
$('#submits').on('click', function () {

});
});</script>

<script type="text/javascript">
    var from = document.getElementById('dateFrom').value;
    var to = document.getElementById('dateTo').value;
    var today = new Date();
    var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    //for stock summary table
    $('#queries').DataTable({
    dom: '<B>frtip',
            buttons: [{
            extend: 'excelHtml5',
                    title: 'Stock Summary as of ' + date,
                    footer: true,
                    text: 'Export to Excel',
                    exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                    }

            }]
            ,
            "lengthMenu": [[ - 1], ["All"]],
            footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var intVal2 = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
            var intVal3 = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
            var intVal4 = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
            var intVal5 = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
            // Total over this page
            pageTotal2 = api.column(2, {page: 'current'}).data().reduce(function (a, b) {
            return intVal2(a) + intVal2(b);
            }, 0);
            pageTotal3 = api.column(3, {page: 'current'}).data().reduce(function (a, b) {
            return intVal3(a) + intVal3(b);
            }, 0);
            pageTotal4 = api.column(4, {page: 'current'}).data().reduce(function (a, b) {
            return intVal4(a) + intVal4(b);
            }, 0);
            pageTotal5 = api.column(5, {page: 'current'}).data().reduce(function (a, b) {
            return intVal5(a) + intVal5(b);
            }, 0);
            // Update footer
            $(api.column(2).footer()).html(pageTotal2);
            $(api.column(3).footer()).html(pageTotal3);
            $(api.column(4).footer()).html(pageTotal4);
            $(api.column(5).footer()).html(pageTotal5);
            }
    });
    //for daily or date range summary table
    $('#dailyqueries').DataTable({
    dom: '<B>frtip',
            buttons: [{
            extend: 'excelHtml5',
                    title: 'Stock Summary',
                    footer: true,
                    text: 'Export to Excel',
                    exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                    }
            }]
            ,
            "order": [[3, "desc"]],
            "lengthMenu": [[ - 1], ["All"]],
            footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var intVal4 = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
            var intVal5 = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
            // Total over this page
            pageTotal4 = api.column(4, {page: 'current'}).data().reduce(function (a, b) {
            return intVal4(a) + intVal4(b);
            }, 0);
            pageTotal5 = api.column(5, {page: 'current'}).data().reduce(function (a, b) {
            return intVal5(a) + intVal5(b);
            }, 0);
            // Update footer
            $(api.column(4).footer()).html(pageTotal4);
            $(api.column(5).footer()).html(pageTotal5);
            }

    });
    //for admin queries table    
    $('#adminqueries').DataTable({
    "order": [[5, "desc"]]
    });</script>

<script type="text/javascript">
    $(function () {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    localStorage.setItem('lastTab', $(this).attr('href'));
    });
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
    $('[href="' + lastTab + '"]').tab('show');
    }
    });</script>  
<script type="text/javascript">
    function myFunction(id) {
    var x = document.getElementById('myDIV' + id);
    if (x.style.display === 'none') {
    x.style.display = 'block';
    } else {
    x.style.display = 'none';
    }
    }
</script> 

@endsection