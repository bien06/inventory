@extends('layouts.admin')
@section('title', 'Item Assignment')

@section('content')

<!-- Page Content -->
<div class="row">
    <div class="col-md-10">
        <h1>Item Assignment</h1>
    </div>
    <div class="col-md-2"></div>
</div>

<div class="panel with-nav-tabs panel-default">
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1default" data-toggle="tab">Item Assignments</a></li>
            <li><a href="#tab2default" data-toggle="tab">Limits</a></li>
			<li><a href="#tab3default" data-toggle="tab">Manual Void</a></li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab1default">

                <!-- item assignments -->
                <div class="row">
                    <div class='col-md-8'>

                        <div class="well well-lg">
                            <div class="row">
                                <div class="row">
                                    <div class="text-center">
                                        <h2>List of Items</h2>
                                    </div>
                                </div>
                                <table class="table  table-responsive" id='itemAssign'>
                                    <thead>
                                        <tr>
                                            <th>Item ID </th>
                                            <th>Item Name</th>
                                            <th>Item Count</th>
                                            <th>Assigned Count</th>
                                            <th>Remaining Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr data-id='{{$item->id}}' data-name="{{$item->item_name}}">
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->item_name}}</td>
                                            <td>{{number_format($item->total_count)}}</td>
                                            <td>{{number_format($item->assigned_count)}}</td>
                                            <td>{{number_format($item->remaining)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="well well-lg">
                            <form class="form-horizontal" method="post" action="{{url('item/assign')}}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <legend>Assign Item</legend>

                                <fieldset>

                                    @if(session()->has('assignI'))
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                        $.alert({
                                        type: 'green',
                                                icon: 'fa fa-check-circle',
                                                title: 'Success Update',
                                                btnClass: 'btn-green',
                                                backgroundDismiss: true,
                                                content: 'Successfully assigned item!'
                                        });
                                        });
                                    </script>       
                                    @elseif(session()->has('voidA'))
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                        $.alert({
                                        type: 'green',
                                                icon: 'fa fa-check-circle',
                                                title: 'Success Update',
                                                btnClass: 'btn-green',
                                                backgroundDismiss: true,
                                                content: 'Successfully restored assigned count!'
                                        });
                                        });
                                    </script>                                           
                                    @elseif(session()->has('none'))
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                        $.alert({
                                        type: 'red',
                                                icon: 'fa fa-exclamation-triangle',
                                                title: 'Error',
                                                btnClass: 'btn-red',
                                                backgroundDismiss: true,
                                                content: 'No assignment found for this branch!'
                                        });
                                        });
                                    </script>       

                                    @endif

                                    <input id="eid" name="id" type="hidden">

                                    <div class="form-group{{ $errors->has('item_name') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label" for="name">Item Name:</label>  
                                        <div class="col-md-8">
                                            <input id="ename" name="item_name" type="text" class="form-control input-md" readonly>
                                            @if ($errors->has('item_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('item_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('branch') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label" for="user">Assign to:</label>
                                        <div class="col-md-8">
                                            <select id="branch" name="branch" class="form-control">
                                                <option value="0">Select a Branch</option>
                                                @foreach($branches as $branch)
                                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('branch'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('branch') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('count') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label" for="count">Assign Count:</label>  
                                        <div class="col-md-8">
                                            <input id="ecount" name="count" type="text" placeholder="Enter Item Count" class="form-control input-md">
                                            @if ($errors->has('count'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('count') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <form id="voidAssign">
                                        <input type="hidden" id="reason" name='reason' />
                                    </form>

                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button value="assign" id="submit" name="submit" disabled="" class="btn btn-success" onclick="return confirm('Are you sure you want to assign this item?')"><i class="fa fa-check-square-o" aria-hidden="true"></i> Assign</button>
                                        </div>
                                    </div>
                                    
                                    <div id="voidcount">
                                        <legend> Void Assignment </legend>
                                        <div class="col-md-12 text-center">
                                            <button value="void" name="submit" disabled="" data-toggle="tooltip" title="Use this for removing count to misassigned branch only." data-placement="bottom" onclick="return voidAssign()"class="btn btn-primary"><i class="fa fa-times" aria-hidden="true"></i> Void</button>                        
                                        </div>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab2default">

                <!--limits -->
                <div class="row">
                    <div class='col-md-8'>

                        <div class="well well-lg">
                            <div class="row">
                                <div class="row">
                                    <div class="text-center">
                                        <h2>List of Limits</h2>
                                    </div>
                                </div>
                                <table class="table  table-responsive" id='limit'>
                                    <thead>
                                        <tr>
                                            <th>Branch</th>
                                            <th>Item</th>
                                            <th>Limit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assigns as $assign)
                                        <tr class="assign{{$assign->id}}" 
                                            data-id='{{$assign->id}}'
                                            data-location='{{App\Branch::find($assign->branch_id)->name}}' data-branch="{{$assign->branch_id}}" data-item="{{$assign->item_id}}" data-name="{{App\Item::find($assign->item_id)->item_name}}" data-limits="{{$assign->limits}}">
                                            <td>{{App\Branch::find($assign->branch_id)->name}}</td>
                                            <td>{{App\Item::find($assign->item_id)->item_name}}</td>
                                            <td>{{number_format($assign->limits)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- set limit -->
                    <div class="col-md-4">
                        <div class="well well-lg">
                            <div class="row">
                                <legend>Set Limit</legend>
                                <form class="form-horizontal" method="post" action="{{url('item/limits')}}" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" id="lid" name="id">
                                    <fieldset>

                                        @if(session()->has('addLim'))
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                            $.alert({
                                            type: 'green',
                                                    icon: 'fa fa-check-circle',
                                                    title: 'Success Update',
                                                    backgroundDismiss: true,
                                                    keys: ['enter', 'shift'],
                                                    content: 'Successfully set limit!'
                                            });
                                            });
                                        </script>   
                                        @endif

                                        <div class="form-group {{ $errors->has('branch_name') ? ' has-error' : '' }}">
                                            <label class="col-md-4 control-label" for="name">Branch:</label>  
                                            <div class="col-md-7">
                                                <input id="llocation" name="branch_name" type="text" readonly="" class="form-control input-md">
                                                @if ($errors->has('branch_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('branch_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <input type="hidden" id="litemid" name="item_id">
                                        <input type="hidden" id="lbranchid" name="branch_id">
                                        
                                        <div class="form-group {{ $errors->has('item') ? ' has-error' : '' }}">
                                            <label class="col-md-4 control-label" for="name">Item:</label>  
                                            <div class="col-md-7">
                                                <input id="litem" name="item" type="text" readonly="" class="form-control input-md">
                                                @if ($errors->has('item'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('item') }}</strong>
                                                </span>
                                                @endif                                
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('limits') ? ' has-error' : '' }}">
                                            <label class="col-md-4 control-label" for="count">Limit:</label>  
                                            <div class="col-md-7">
                                                <input id="limits" name="limits" type="text" placeholder="Enter Limit" class="form-control input-md" value="{{ old('limits') }}" >
                                                @if ($errors->has('limits'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('limits') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-12 text-center">
                                                <button id="submit" name="submit" disabled="" class="btn btn-success" onclick="return confirm('Are you sure you want to set limit for this item?')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Set Limit</button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end update item -->
                </div>
                <!-- end limits -->
            </div>
			<div class="tab-pane fade" id="tab3default">
			<!-- manual void -->
			<div class="row">
    <div class='col-md-8'>
        <div class="well well-lg">

            <div class="row">
                <div class="row">
                    <div class="text-center">
                        <h2>List of Items</h2>
                    </div>
                </div>
                <table class="table table-responsive" id='assignments'>
                    <thead>
                        <tr>
                            <th>Item Name</th>
							<th>Branch</th>
                            <th>Received</th>
                            <th>Good</th>
                            <th>Reject</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assigns as $assign)
                        <tr data-id="{{$assign->id}}" data-branch="{{$assign->branch_id}}" data-itemid="{{$assign->item_id}}"data-location="{{App\Branch::find($assign->branch_id)->name}}" data-item="{{App\Item::find($assign->item_id)->item_name}}">
                            <td>{{App\Item::find($assign->item_id)->item_name}}</td>
							<td>{{App\Branch::find($assign->branch_id)->name}}</td>
                            <td>{{number_format($assign->item_count)}}</td>
                            <td>{{number_format($assign->good)}}</td>
                            <td>{{number_format($assign->rejected)}}</td>
                            <td>{{number_format($assign->balance)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- update item assignment -->
    <div class="col-md-4">
        <div class="well well-lg">

            <legend>Void Item</legend>
            <form class="form-horizontal" method="post" action="{{url('item/void')}}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >

                <fieldset>

                    @if(session()->has('voidI'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                title: 'Success Update',
                                type: 'green',
                                icon: 'fa fa-check-circle',
                                btnClass: 'btn-green',
                                backgroundDismiss: true,
                                keys: ['enter', 'shift'],
                                content: 'Successfully restored item count!'
                            });
                        });
                    </script>   
                    @elseif(session()->has('errorM'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                title: 'Error',
                                type: 'red',
                                icon: 'fa fa-exclamation-triangle',
                                btnClass: 'btn-red',
                                backgroundDismiss: true,
                                keys: ['enter', 'shift'],
                                content: 'Error voiding item!'
                            });
                        });
                    </script>   
                    @endif

                    <input id="aid" name="assigned_id" type="hidden" class="form-control input-md" readonly="">

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="assigned_item">Item Name</label>  
                        <div class="col-md-8">
                            <input id="aitem" name="assigned_item" type="text" class="form-control input-md" readonly="">
                            @if ($errors->has('assigned_item'))
                            <span class="help-block">
                                <strong>{{ $errors->first('assigned_item') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

					<input id="abranch_id" name="assigned_branch_id" type="hidden">
					<input id="aitem_id" name="assigned_item_id" type="hidden">
					
                    <div class="form-group{{ $errors->has('branch') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="item_name">Branch</label>  
                        <div class="col-md-8">
                            <input id="abranch" name="assigned_branch" type="text" class="form-control input-md" readonly="">
                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
					
                                    <div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label" for="user">User:</label>
                                        <div class="col-md-8">
                                            <select id="auser" disabled="" name="assigned_user" class="form-control">
                                                <option value="0">Select User</option>
                                            </select>
                                            @if ($errors->has('user'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('user') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
					
                    <div class="form-group{{ $errors->has('good') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="good">Good</label>  
                        <div class="col-md-8">
                            <input id="agood" name="good" type="text" placeholder="Enter good quantity" class="form-control input-md" value="{{ old('good') }}"  >
                            @if ($errors->has('good'))
                            <span class="help-block">
                                <strong>{{ $errors->first('good') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('reject') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="reject">Reject</label>  
                        <div class="col-md-8">
                            <input id="areject" name="reject" type="text" placeholder="Enter reject quantity" class="form-control input-md" value="{{ old('reject') }}">
                            @if ($errors->has('reject'))
                            <span class="help-block">
                                <strong>{{ $errors->first('reject') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="reason">Reason</label>  
                        <div class="col-md-8">
                            <input id="areason" name="reason" type="text" placeholder="Enter reason" class="form-control input-md" value="{{ old('reason') }}">
                            @if ($errors->has('reason'))
                            <span class="help-block">
                                <strong>{{ $errors->first('reason') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button value="void" id="void" name="submit" onclick="return confirm('Are you sure you want to void this item?')" data-toggle="tooltip" disabled="" title="Use this for removing excess good or reject count only." data-placement="bottom" class="btn btn-info"><i class="fa fa-ban" aria-hidden="true"></i> Void</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <!-- end item assignment -->
</div>

			</div>
		</div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function () {
var table = $('#assignments').DataTable();
$('#assignments').on('click', 'tr', function () {
	if ($(this).hasClass('selected')) {
		$('#aid').val('');
		$('#abranch').val('');
		$('#aitem').val('');
		$('#auser').val(0);
		$('#agood').val('');
		$('#abranch_id').val('');					
		$('#areject').val('');
		$('#areason').val('');	
		$('#aitem_id').val('');			
		$("select[name='assigned_user']").attr('disabled', 'disabled');
		$("button[name='submit']").attr('disabled', 'disabled');
		$(this).removeClass('selected');
	} else {
		$('#aid').val($(this).data('id'));
		$('#abranch').val($(this).data('location'));
		$('#abranch_id').val($(this).data('branch'));
		$('#aitem').val($(this).data('item'));
		$('#aitem_id').val($(this).data('itemid'));
		var branchID = $("input[name='assigned_branch_id']").val();            
		if (branchID) {
			$.ajax({
				url: '/item/assign/' + branchID,
				type: "GET",
				dataType: "json",
				success: function (data) {
					$('select[name="assigned_user"]').empty();
					$.each(data, function (key, value) {
						$('select[name="assigned_user"]').append('<option value="' + key + '">' + value + '</option>');
					});
				}
			});
		} else {
			$('select[name="assigned_user"]').empty();
		}	
		$("select[name='assigned_user']").removeAttr('disabled');
		$("button[name='submit']").removeAttr('disabled');
		table.$('tr.selected').removeClass('selected');
		$(this).addClass('selected');
	}
});

});
</script>
<script type="text/javascript">
    function voidAssign() {
    var reason = prompt("Please enter a reason:");
    if (reason === null || reason === '') {
    $.alert({
    type: 'red',
            icon: 'fa fa-warning',
            btnClass: 'btn-red',
            title: 'Error',
            backgroundDismiss: true,
            content: 'Please enter a reason!'
    });
    event.preventDefault();
    } else {
    document.getElementById("reason").value = reason;
    document.getElementById("voidAssign").submit();
    }
    }
</script>

<script>
    $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    });
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