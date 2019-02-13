<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Audit;
use App\Branch;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('admin_user');
    }

    public function index() {
        $users = User::all();
        $locs = Branch::all();
        return view('admin/users', compact('users', 'locs'));
    }

    public function add(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'location' => 'required|not_in:0',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->branch_id = $request->location;
        $user->password = bcrypt($request->password);
        $user->save();

        $ids = Auth::guard('admin_user')->user()->id;
        $names = Auth::guard('admin_user')->user()->name;
        $audit = new Audit();
        $audit->account_id = $ids;

        $audit->action = "$names added a user ($request->name)";
        $audit->save();

        return redirect('users')->with('addU', 'add');
    }

    public function update(Request $request) {
        switch ($request->submit) {
            case 'update':
                $this->validate($request, [
                    'user_id' => 'required',
                    'updated_name' => 'required',
                    'updated_location' => 'required|not_in:0',
                    'updated_email' => 'required|email|unique:users,email,'.$request->user_id,
//                    'updated_password' => 'required|min:8',
                ]);
                $user = User::find($request->user_id);
                $user->name = $request->updated_name;
                $user->branch_id = $request->updated_location;
                $user->email = $request->updated_email;
//                $user->password = bcrypt($request->updated_password);
                $user->save();

                $ids = Auth::guard('admin_user')->user()->id;
                $names = Auth::guard('admin_user')->user()->name;
                $audit = new Audit();
                $audit->account_id = $ids;

                $audit->action = "$names updated a user ($user->name)";
                $audit->save();

                \Session::flash('updateU', '');
                
                break;
            case 'changestat':
                $user = User::find($request->user_id);
                if (count($user) == 1) {
                    if ($request->status == "Inactive") {
                        $ids = Auth::guard('admin_user')->user()->id;
                        $names = Auth::guard('admin_user')->user()->name;
                        $audit = new Audit();
                        $audit->account_id = $ids;

                        $audit->action = "$names changed status of a user ($user->name)";
                        $audit->save();

                        $user->status = "Active";
                        } else {
                        $ids = Auth::guard('admin_user')->user()->id;
                        $names = Auth::guard('admin_user')->user()->name;
                        $audit = new Audit();
                        $audit->account_id = $ids;

                        $audit->action = "$names changed status of a user ($user->name)";
                        $audit->save();

                        $user->status = "Inactive";
                    }
                    $user->save();
                } else {
                    $this->validate($request, [
                        'user_id' => 'required',
                        'status' => 'required',
                    ]);
                }
                \Session::flash('updateS', '');

                break;
        }
        return redirect('users');
    }

    public function addLoc(Request $req) {
        $location = new Branch();
        $location->name = $req->location_name;
        $location->save();

        $ids = Auth::guard('admin_user')->user()->id;
        $names = Auth::guard('admin_user')->user()->name;
        $audit = new Audit();
        $audit->account_id = $ids;

        $audit->action = "$names added a branch ($req->location_name)";
        $audit->save();

        return redirect('users')->with('addL', '');
    }

    public function updateLoc(Request $req) {
        $this->validate($req, [
            'branch_id' => 'required',
            'new_location' => 'required',
        ]);
        $ids = Auth::guard('admin_user')->user()->id;
        $names = Auth::guard('admin_user')->user()->name;
        $audit = new Audit();
        $audit->account_id = $ids;
        
        $loc = Branch::find($req->branch_id);
        $audit->action = "$names updated a branch ($loc->name) to $req->new_location";
        $loc->name = $req->new_location;
        
        
        $loc->save();
        $audit->save();
        
        return redirect('users')->with('updateL', '');
    }

}
