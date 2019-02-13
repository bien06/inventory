@extends('layouts.admin')
@section('title', 'Item Management')

@section('content')

<!-- Page Content -->
<div class="row">
    <div class="col-md-10">
        <h1>Item Management</h1>
    </div>
    <div class="col-md-2"></div>
</div>

<div class="row">
    <div class='col-md-8'>

        <div class="well well-lg">
            <div class="row">
                <div class="col-md-1">
                    <button type="button" class="btn btn-success " data-toggle="modal" data-target="#Add"><span class="glyphicon glyphicon-plus"></span> Add Item</button>
                </div>
            </div>

            <div class="row">
                <div class="row">
                    <div class="text-center">
                        <h2>List of Items</h2>
                    </div>
                </div>
                <table class="table  table-responsive" id='item'>
                    <thead>
                        <tr>
                            <th>Item ID </th>
                            <th>Item Name</th>
                            <th>Item Count</th>
                            <th>Remaining Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr class="item{{$item->id}}" data-id='{{$item->id}}' data-name="{{$item->item_name}}" data-count="{{$item->item_count}}">
                            <td>{{$item->id}}</td>
                            <td>{{$item->item_name}}</td>
                            <td>{{number_format($item->total_count)}}</td>
                            <td>{{number_format($item->remaining)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- update item -->
    <div class="col-md-4">
        <div class="well well-lg">
            <legend>Update Item</legend>

            <form class="form-horizontal" method="post" action="{{url('item/update')}}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >

                <fieldset>

                    @if(session()->has('addI'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                type: 'green',
                                icon: 'fa fa-check-circle',
                                btnClass: 'btn-green',
                                title: 'Success Update',
                                backgroundDismiss: true,
                                content: 'Successfully added item!'
                            });
                        });
                    </script>                            
                    @elseif(session()->has('updateI'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                type: 'green',
                                icon: 'fa fa-check-circle',
                                btnClass: 'btn-green',
                                title: 'Success Update',
                                backgroundDismiss: true,
                                content: 'Successfully updated item!'
                            });
                        });
                    </script>                            
                    @elseif(session()->has('removeI'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                type: 'green',
                                icon: 'fa fa-check-circle',
                                btnClass: 'btn-green',
                                title: 'Success Update',
                                backgroundDismiss: true,
                                content: 'Successfully restored item count!'
                            });
                        });
                    </script>                            
                    @endif

                    <div class="form-group {{ $errors->has('item_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="name">Item ID:</label>  
                        <div class="col-md-8">
                            <input id="eid" name="item_id" type="text" readonly="" class="form-control input-md">
                            @if ($errors->has('item_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('item_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('updated_name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="name">Item Name:</label>  
                        <div class="col-md-8">
                            <input id="ename" name="updated_name" type="text" placeholder="Enter Item Name" class="form-control input-md" value="{{ old('updated_name') }}" >
                            @if ($errors->has('updated_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('updated_name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('add_count') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="count">Item Count:</label>  
                        <div class="col-md-8">
                            <input id="count" name="add_count" type="text" placeholder="Enter Item Count" class="form-control input-md" value="{{ old('add_count') }}" >
                            @if ($errors->has('add_count'))
                            <span class="help-block">
                                <strong>{{ $errors->first('add_count') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <form id="removeItem">
                        <input type="hidden" id="reason" name='reason' />
                    </form>

                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button value="update" id="submit" name="submit" disabled="" class="btn btn-success" onclick="return confirm('Are you sure you want to update this item?')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update</button>
                        </div>
                    </div>

                    <div id="removecount">
                        <legend> Remove Count </legend>
                        <div class="col-md-12 text-center">
                            <button value="remove" name="submit" disabled="" data-toggle="tooltip" title="Use this for removing excess item count only." data-placement="bottom" onclick="return remove()"class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Remove</button>                        
                        </div>
                    </div>
                </fieldset>
            </form>                
        </div>
    </div>
    <!-- end update item -->
</div>

<!-- add modal -->
<div id="Add" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Item</h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{url('item/add')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <fieldset>

                        <div class="form-group {{ $errors->has('item_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label" for="name">Item Name:</label>  
                            <div class="col-md-6">
                                <input id="name" name="item_name" type="text" placeholder="Enter Item Name" autofocus="" class="form-control input-md" value="{{ old('item_name') }}" required>
                                @if ($errors->has('item_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('item_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('item_count') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label" for="count">Item Count:</label>  
                            <div class="col-md-6">
                                <input id="count" name="item_count" type="text" placeholder="Enter Item Count"  class="form-control input-md" value="{{ old('item_count') }}" required>
                                @if ($errors->has('item_count'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('item_count') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </fieldset>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <label class="col-md-4 control-label" for="submit"></label>
                    <div class="col-md-8">
                        <button id="submit" name="submits" class="btn btn-success">Submit</button>
                        <button id="cancel" name="cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end add modal -->

@endsection

@section('js')
<script type="text/javascript">
    function remove() {
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
            document.getElementById("removeItem").submit();
        }
    }
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
    
@endsection