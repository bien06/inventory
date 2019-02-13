<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdminUser;
use Auth;
use App\Audit;

class AdminController extends Controller {

    //
    public function __construct() {
        $this->middleware('super_admin');
    }

    public function index() {
        $admins = AdminUser::where('role', 2)->get();
        return view('admin.admin', compact('admins'));
    }

    public function add(Request $req) {
        $this->validate($req, [
            'name' => 'required',
            'email' => 'email|required|unique:admin_users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
//                    'updated_password' => 'required|min:8',
        ]);
        $admin = new AdminUser;
        $admin->name = $req->name;
        $admin->email = $req->email;
        $admin->password = bcrypt($req->password);
//                $admin->password = bcrypt($request->updated_password);
        $admin->save();

        $ids = Auth::guard('admin_user')->user()->id;
        $names = Auth::guard('admin_user')->user()->name;
        $audit = new Audit();
        $audit->account_id = $ids;

        $audit->action = "$names added an Admin ($req->name)";
        $audit->save();

        return redirect('admin')->with('addA', '');
    }

    public function update(Request $req) {
        switch ($req->submit) {
            case 'update':
                $this->validate($req, [
                    'updated_name' => 'required',
                    'updated_email' => 'email|required|unique:admin_users,email,'.$req->admin_id,
                ]);
                $admin = AdminUser::find($req->admin_id);
                $admin->name = $req->updated_name;
                $admin->email = $req->updated_email;
                $admin->save();

                $ids = Auth::guard('admin_user')->user()->id;
                $names = Auth::guard('admin_user')->user()->name;
                $audit = new Audit();
                $audit->account_id = $ids;

                $audit->action = "$names updated an Admin ($admin->name)";
                $audit->save();

                \Session::flash('updateA', '');
                break;
            case 'changestat':
                $admin = AdminUser::find($req->admin_id);
                if (count($admin) > 0) {
                    if ($admin->is_active == 0) {
                        $ids = Auth::guard('admin_user')->user()->id;
                        $names = Auth::guard('admin_user')->user()->name;
                        $audit = new Audit();
                        $audit->account_id = $ids;

                        $audit->action = "$names changed status of an Admin ($admin->name)";
                        $audit->save();

                        $admin->is_active = 1;
                    } else {
                        $ids = Auth::guard('admin_user')->user()->id;
                        $names = Auth::guard('admin_user')->user()->name;
                        $audit = new Audit();
                        $audit->account_id = $ids;

                        $audit->action = "$names changed status of a user ($admin->name)";
                        $audit->save();

                        $admin->is_active = 0;
                    }
                    $admin->save();
                } else {
                    $this->validate($req, [
                        'admin_id' => 'required',
                        'is_active' => 'required',
                            ], [
                        'is_active.required' => 'The status field is required.'
                            ]
                    );
                }

                \Session::flash('updateSA', '');
                break;
        }
        return redirect('admin');
    }

}
