@extends('layouts.user')
@section('title', 'User Home')

@section('content')

<div class="row">
    <div class="col-md-10">
        <h1>Assigned Items</h1>
    </div>
    <div class="col-md-2"></div>
</div>

<div class="row">
    <div class='col-md-8'>
        <div class="well well-lg">

            <div class="row">
                <div class="row">
                    <div class="text-center">
                        <h2>List of Items</h2>
                    </div>
                </div>
                <table class="table table-responsive" id='userTable'>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Received</th>
                            <th>Good</th>
                            <th>Reject</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assigns as $assign)
                        <tr data-id="{{$assign->id}}" data-location="{{$assign->branch_id}}" data-itemname="{{App\Item::find($assign->item_id)->item_name}}">
                            <td>{{App\Item::find($assign->item_id)->item_name}}</td>
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

            <legend>Update Item</legend>
            <form class="form-horizontal" method="post" action="{{url('assign/update')}}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >

                <fieldset>

                    @if(session()->has('updateI'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.alert({
                                title: 'Success Update',
                                type: 'green',
                                icon: 'fa fa-check-circle',
                                btnClass: 'btn-green',
                                backgroundDismiss: true,
                                keys: ['enter', 'shift'],
                                content: 'Successfully updated item!'
                            });
                        });
                    </script>   
                    @elseif(session()->has('voidI'))
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

                    <input id="eid" name="id" type="hidden" class="form-control input-md" readonly="">

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="item_name">Item Name</label>  
                        <div class="col-md-8">
                            <input id="ename" name="name" type="text" class="form-control input-md" readonly="">
                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('good') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="good">Good</label>  
                        <div class="col-md-8">
                            <input id="egood" name="good" type="text" placeholder="Enter good quantity" class="form-control input-md" value="{{ old('good') }}"  >
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
                            <input id="ereject" name="reject" type="text" placeholder="Enter reject quantity" class="form-control input-md" value="{{ old('reject') }}">
                            @if ($errors->has('reject'))
                            <span class="help-block">
                                <strong>{{ $errors->first('reject') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <form id="itemClass">
                        <input type="hidden" id="reason" name='reason' />
                    </form>

                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button value="update" id="update" name="submit" disabled="" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update</button>
                        </div>
                    </div>
                    <div id="voidcount">
                    <legend>Void Count </legend>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button value="void" id="void" name="submit" data-toggle="tooltip" disabled="" title="Use this for removing excess good or reject count only." data-placement="bottom" class="btn btn-info"><i class="fa fa-ban" aria-hidden="true"></i> Void</button>
                        </div>
                    </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <!-- end item assignment -->
</div>
@endsection