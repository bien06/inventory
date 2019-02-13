<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemAssign;
use App\Item;
use Auth;
use App\User;
use App\AuditUser;
use App\UserAssign;
use DB;

class HomeController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $id = Auth::user()->branch_id;
        $items = Item::all();
        $assigns = ItemAssign::where('branch_id', $id)->get();
        return view('user/index', compact('assigns', 'items'));
    }

    public function assign(Request $req) {
        $this->validate($req, [
            'name' => 'required',
            'good' => 'required|numeric|min:0',
            'reject' => 'required|numeric|min:0',
        ]);
        $ids = Auth::user()->id;
        $names = Auth::user()->name;
        $data = ItemAssign::where('id', $req->id)->first();
        $dataItem = DB::table('item_assignments')->select('item_id')->where('id', $req->id)->first();
        $id = $dataItem->item_id;
        $item = Item::where('id', $id)->first();
        $itemname = $item->item_name;
        $bal = $data->balance;
        switch ($req->submit) {
            case 'update':
                $balance = $req->good + $req->reject;
                if ($balance <= $data->balance) {
                    if ($req->reject <= 0) {
                        $data->good += $req->good;
                        $data->rejected += $req->reject;
                        $data->balance = $data->balance - ($req->good + $req->reject);

                        $dt = new UserAssign();
                        $dt->good = $req->good;
                        $dt->rejected = $req->reject;
                        $dt->user_id = $ids;
                        $dt->item_assign = $data->id;
                        $dt->action = 'Update';

                        $audit = new AuditUser();
                        $audit->account_id = $ids;

                        $audit->action = "$names updated an item ($itemname)";
                    } else {
                        $this->validate($req, [
                            'reason' => 'required',
                        ]);
                        $data->good += $req->good;
                        $data->rejected += $req->reject;
                        $data->balance = $data->balance - ($req->good + $req->reject);

                        $dt = new UserAssign();
                        $dt->good = $req->good;
                        $dt->rejected = $req->reject;
                        $dt->user_id = $ids;
                        $dt->item_assign = $data->id;
                        $dt->reason = $req->reason;
                        $dt->action = 'Update';

                        $audit = new AuditUser();
                        $audit->account_id = $ids;

                        $audit->action = "$names updated an item ($itemname)";
                    }
                    $data->save();
                    $dt->save();
                    $audit->save();
                    \Session::flash('updateI', '');
                } else if ($data->balance < $req->good) {
                    $this->validate($req, [
                        'good' => 'required|numeric|min:0|max:'.$bal,
                    ]);
                } else if ($data->balance < $req->reject) {
                    $this->validate($req, [
                        'reject' => 'required|numeric|min:0|max:'.$bal,
                    ]);
                } else if ($data->balance < $balance) {
                    $this->validate($req, [
                        'good' => 'required|numeric|min:0|max:'.$bal,
                        'reject' => 'required|numeric|min:0|max:'.$bal,
                            ], [
                        'good.max' => 'The total value must be less than balance. ',
                        'reject.max' => 'The total value must be less than balance.'
                            ]
                    );
                }
                break;
            case 'void':
                $user = UserAssign::where('user_id', $ids)->where('item_assign', $data->id)->first();
                if ($user) {
                    if ($data->good >= $req->good && $data->rejected >= $req->reject) {
                        $data->good -= $req->good;
                        $data->rejected -= $req->reject;
                        $data->balance = $data->balance + ($req->good + $req->reject);
                        $data->save();

                        $dt = new UserAssign();
                        if ($req->good != 0) {
                            $dt->good = -($req->good);
                        } else {
                            $dt->good = $req->good;
                        }

                        if ($req->reject != 0) {
                            $dt->rejected = -($req->reject);
                        } else {
                            $dt->rejected = $req->reject;
                        }

                        $dt->user_id = $ids;
                        $dt->item_assign = $data->id;
                        $dt->reason = $req->reason;
                        $dt->action = 'Void';
                        $dt->save();


                        $audit = new AuditUser();
                        $audit->account_id = $ids;

                        $audit->action = "$names restored an item's count ($itemname)";
                        $audit->save();
                        \Session::flash('voidI', '');
                    } else if ($data->good < $req->good) {
                        $this->validate($req, [
                            'good' => 'required|numeric|min:0|input_less',
                        ]);
                    } else if ($data->rejected < $req->reject) {
                        $this->validate($req, [
                            'reject' => 'required|numeric|min:0|input_less',
                        ]);
                    } else {
                        $this->validate($req, [
                            'good' => 'required|numeric|min:0|input_less',
                            'reject' => 'required|numeric|min:0|input_less',
                        ]);
                    }
                } else {
                    \Session::flash('errorM', '');
                }
                break;
        }
        return redirect('home');
    }

    public function changepassword() {
        $ids = Auth::user()->id;
        $profile = User::find($ids);
        return view('user/changepass', compact('profile'));
    }

    public function updatepassword(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8|',
            'password_confirmation' => 'required|min:8|same:password',
        ]);

        $ids = Auth::user()->id;
        if ($request->password == $request->password_confirmation) {
            $profile = User::find($ids);
            $profile->email = $request->email;
            $profile->password = bcrypt($request->password);
            $profile->save();
            return redirect('home');
        } else {
            return redirect('change_password');
        }
    }

}
