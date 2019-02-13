<?php

namespace App\Http\Controllers;

use App\Audit;
use Auth;
use App\Item;
use App\Branch;
use App\ItemAssign;
use App\AdminLog;
use App\User;
use App\UserAssign;
use DB;
use Illuminate\Http\Request;

class ItemController extends Controller {

    public function __construct() {
        $this->middleware('admin_user');
    }

    public function index() {
        $items = Item::where('is_deleted', 0)->get();
        return view('admin/item', compact('items'));
    }

    public function store(Request $req) {
        $this->validate($req, [
            'item_name' => 'required',
            'item_count' => 'required|numeric|min:0',
        ]);
        //stores to item table
        $items = new Item();
        $items->item_name = $req->item_name;
        $items->total_count = $req->item_count;
        $items->remaining += $req->item_count;
        $items->save();

        //stores to audit table
        $ids = Auth::guard('admin_user')->user()->id;
        $names = Auth::guard('admin_user')->user()->name;
        $audit = new Audit();
        $audit->account_id = $ids;

        $audit->action = "$names added an item ($req->item_name)";
        $audit->save();

        return redirect('item')->with('addI', '');
    }

    public function itemAssign() {
        $items = Item::where('is_deleted', 0)->get();
        $branches = Branch::all();
        $assigns = ItemAssign::all();
		$users = User::where('status', 'Active')->get();
        return view('admin/assignitem', compact('items', 'branches', 'assigns', 'users'));
    }

    public function assign(Request $req) {
        $this->validate($req, [
            'item_name' => 'required',
            'branch' => 'required|not_in:0',
            'count' => 'required|numeric|min:0',
        ]);
        $item = Item::find($req->id);

        switch ($req->submit) {
            case 'assign':
                if ($req->count <= $item->remaining) {
                    // stores to assign item table
                    $assign1 = ItemAssign::where("item_id", $req->id)->where('branch_id', $req->branch)->first();
                    if (count($assign1) == 1) {
                        $assign = $assign1;
                        $assign->item_count += $req->count;
                        $assign->balance += $req->count;
                        $assign->update();
                    } else {
                        $assign = new ItemAssign();
                        $assign->branch_id = $req->branch;
                        $assign->item_id = $req->id;
                        $assign->item_count = $req->count;
                        $assign->balance = $req->count;
                        $assign->save();
                    }

                    // stores to branch table
                    $branch = Branch::find($req->branch);
                    $branch->received += $req->count;
                    $branch->save();

                    // stores to item table
                    $item->assigned_count += $req->count;
                    $item->remaining = $item->total_count - $item->assigned_count;
                    $item->save();

                    //stores to admin log table
                    $admin = new AdminLog();
                    $admin->admin_id = Auth::guard('admin_user')->user()->id;
                    $admin->item_id = $item->id;
                    $admin->branch_id = $req->branch;
                    $admin->count = $req->count;
                    $admin->action = 'Assign';
                    $admin->save();

                    // make audit
                    $ids = Auth::guard('admin_user')->user()->id;
                    $names = Auth::guard('admin_user')->user()->name;
                    $audit = new Audit();
                    $audit->account_id = $ids;
                    $countnum = number_format($req->count);
                    $audit->action = "$names assigned $countnum $item->item_name to $branch->name";
                    $audit->save();
                    \Session::flash('assignI', '');
                } else {
                    $count = $item->remaining;
                    $this->validate($req, [
                        'item_name' => 'required',
                        'branch' => 'required|not_in:0',
                        'count' => 'required|numeric|min:0|max:' . $count,
                            ], [
                        'count.max' => 'The count value must be less than remaining count.'
                    ]);
                }
                break;
            case 'void':
                $assign1 = ItemAssign::where("item_id", $req->id)->where('branch_id', $req->branch)->first();
                if (count($assign1) == 1) {
                    if ($req->count <= $item->assigned_count) {
                        $count = $assign1->balance;
                        $this->validate($req, [
                            'item_name' => 'required',
                            'branch' => 'required|not_in:0',
                            'count' => 'required|numeric|min:0|max:' . $count,
                                ], [
                            'count.max' => 'The value must be less than its remaining count.'
                        ]);
                        // stores to assign item table
                        $assign = $assign1;
                        $assign->reason = $req->reason;
                        $assign->item_count -= $req->count;
                        $assign->balance -= $req->count;
                        $assign->update();

                        // stores to branch table
                        $branch = Branch::find($req->branch);
                        $branch->received -= $req->count;
                        $branch->save();

                        // stores to item table
                        $item->assigned_count -= $req->count;
                        $item->remaining = $item->remaining + $req->count;
                        $item->save();

                        //stores to admin log table
                        $admin = new AdminLog();
                        $admin->admin_id = Auth::guard('admin_user')->user()->id;
                        $admin->item_id = $item->id;
                        $admin->branch_id = $req->branch;
                        $admin->count = $req->count;
                        $admin->action = 'Void';
                        $admin->save();

                        // make audit
                        $ids = Auth::guard('admin_user')->user()->id;
                        $names = Auth::guard('admin_user')->user()->name;
                        $audit = new Audit();
                        $audit->account_id = $ids;
                        $countnum = number_format($req->count);
                        $audit->action = "$names restored $countnum $item->item_name to $branch->name with reason '$assign->reason'";
                        $audit->save();
                        \Session::flash('voidA', '');
                    } else {
                        $count = $assign1->balance;
                        $this->validate($req, [
                            'item_name' => 'required',
                            'branch' => 'required|not_in:0',
                            'count' => 'required|numeric|min:0|max:' . $count,
                                ], [
                            'count.max' => 'The count value must be less than assigned count.'
                        ]);
                    }
                } else {
                    \Session::flash('none', '');
                }
                break;
        }
        return redirect('assignment');
    }

    public function update(Request $req) {
        switch ($req->submit) {
            case 'update':
                $this->validate($req, [
                    'item_id' => 'required',
                    'updated_name' => 'required',
                    'add_count' => 'required|numeric|min:0',
                ]);
                $item = Item::find($req->item_id);

                $ids = Auth::guard('admin_user')->user()->id;
                $names = Auth::guard('admin_user')->user()->name;
                $audit = new Audit();
                $audit->account_id = $ids;

                if ($req->add_count == 0) {
                    //stores to audit table
                    $audit->action = "$names updated $item->item_name to $req->updated_name";
                    //stores to admin log table
                    $admin = new AdminLog();
                    $admin->admin_id = Auth::guard('admin_user')->user()->id;
                    $admin->item_id = $item->id;
                    $admin->action = 'Update';
                } else {
                    //stores to audit table       
                    $asd = \number_format($req->add_count);
                    $audit->action = "$names added $asd stock(s) to $req->updated_name";
                    //stores to admin log table
                    $admin = new AdminLog();
                    $admin->admin_id = Auth::guard('admin_user')->user()->id;
                    $admin->item_id = $item->id;
                    $admin->count = $req->add_count;
                    $admin->action = 'Add';
                }

                //stores to item table
                $item->item_name = $req->updated_name;
                $item->total_count += $req->add_count;
                $item->remaining += $req->add_count;

                $item->save();
                $admin->save();
                $audit->save();

                \Session::flash('updateI', '');
                break;
            case 'remove':
                $item = Item::find($req->item_id);

                $count = $item->remaining;

                $this->validate($req, [
                    'item_id' => 'required',
                    'updated_name' => 'required',
                    'add_count' => 'required|numeric|min:0|max:' . $count,
                        ], [
                    'add_count.required' => 'The remove count field is required.',
                    'add_count.numeric' => 'The remove count must be a number.',
                    'add_count.min' => 'The remove count must be at least 0.',
                    'add_count.max' => 'The remove count must be less than the assigned count.'
                ]);

                if (count($item) == 1) {

                    //stores to item table
                    $item->reason = $req->reason;
                    $item->total_count -= $req->add_count;
                    $item->remaining -= $req->add_count;
                    $item->save();

                    //stores to admin log table
                    $admin = new AdminLog();
                    $admin->admin_id = Auth::guard('admin_user')->user()->id;
                    $admin->item_id = $item->id;
                    $admin->count = $req->add_count;
                    $admin->action = 'Remove';
                    $admin->save();

                    //stores to audit table
                    $ids = Auth::guard('admin_user')->user()->id;
                    $names = Auth::guard('admin_user')->user()->name;
                    $audit = new Audit();
                    $audit->account_id = $ids;
                    $countnum = number_format($req->add_count);

                    $audit->action = "$names restored $countnum $item->item_name";
                    $audit->save();
                } else {
                    $this->validate($req, [
                        'item_id' => 'required',
                        'updated_name' => 'required',
                        'add_count' => 'required|numeric|min:0|max:' . $count,
                    ]);
                }
                \Session::flash('removeI', '');
                break;
        }
        return redirect('item');
    }

    public function limits(Request $req) {
        $this->validate($req, [
            'branch_name' => 'required',
            'item' => 'required',
            'limits' => 'required|numeric|min:0',
        ]);
        //stores to item assignment table
        $item = ItemAssign::find($req->id);
        $item->limits = $req->limits;
        $item->save();

        $itemdata = DB::table('item_assignments')->select('item_id')->where('id', $req->id)->first();
        $itemid = $itemdata->item_id;
        $itemn = Item::where('id', $itemid)->first();
        $itemname = $itemn->item_name;

        //stores to admin log table
        $admin = new AdminLog();
        $admin->admin_id = Auth::guard('admin_user')->user()->id;
        $admin->item_id = $req->item_id;
        $admin->branch_id = $req->branch_id;
        $admin->count = $req->limits;
        $admin->action = 'Set Limit';
        $admin->save();

        //stores to audit table
        $ids = Auth::guard('admin_user')->user()->id;
        $names = Auth::guard('admin_user')->user()->name;
        $audit = new Audit();
        $audit->account_id = $ids;
        $req->limits = number_format($req->limits);
        $audit->action = "$names set a limit ($req->limits) for $itemname on $req->branch_name";
        $audit->save();

        return redirect('assignment')->with('addLim', '');
    }
	
    public function getuser($id)
    {
        $users = DB::table("users")->where("branch_id",$id)->pluck('name','id');
        return json_encode($users);        
    }
    	
	public function voidItem(Request $req){
		$data = ItemAssign::where('id', $req->assigned_id)->first();
		$this->validate($req, [
			'assigned_branch' => 'required',
			'assigned_id' => 'required',
			'assigned_item' => 'required',
			'assigned_user' => 'required:not_in:0',
			'good' => 'required|numeric|min:0|max:'.$data->good,
			'reject' => 'required|numeric|min:0|max:'.$data->rejected,
			'reason' => 'required'
		]);
		// stores to User Assign table
		$assign = new UserAssign();
		$assign->user_id = $req->assigned_user;
		$assign->item_assign = $req->assigned_id;
		if ($req->good != 0) {
			$assign->good = -($req->good);
		} else {
			$assign->good = $req->good;
		}

		if ($req->reject != 0) {
			$assign->rejected = -($req->reject);
		} else {
			$assign->rejected = $req->reject;
		}
		
		$assign->reason = $req->reason;
		$assign->action = 'Void';
		$assign->save();
		
		// stores to Item Assigns table
		$data->good -= $req->good;
        $data->rejected -= $req->reject;
        $data->balance = $data->balance + ($req->good + $req->reject);
        $data->save();

        //stores to audit table
		$assigneditem = $req->assigned_item;
        $ids = Auth::guard('admin_user')->user()->id;
        $names = Auth::guard('admin_user')->user()->name;
        $audit = new Audit();
        $audit->account_id = $ids;
		if(($req->good != 0) && ($req->reject != 0)){
        $audit->action = "$names restored $req->good good, $req->reject reject for $assigneditem on $req->assigned_branch";
        }		
		else if($req->good != 0){
        $audit->action = "$names restored $req->good good for $assigneditem on $req->assigned_branch";		
		}
		else if($req->reject != 0){
        $audit->action = "$names restored $req->reject reject for $assigneditem on $req->assigned_branch";				
		}
		$audit->save();
		
        \Session::flash('voidI', '');
		
		return redirect('assignment');
	}

}
