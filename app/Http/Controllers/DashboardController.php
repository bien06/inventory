<?php

namespace App\Http\Controllers;

use App\Item;
use App\User;
use App\ItemAssign;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    public function __construct() {
        $this->middleware('admin_user');
    }

    public function index() {

        $items = Item::where('updated_at', '>=', Carbon::now()->today())->get();
        $itemcount = count($items);

        $users = User::where('updated_at', '>=', Carbon::now()->today())->get();
        $usercount = count($users);

        $assigns = ItemAssign::where('updated_at', '>=', Carbon::now()->today())->get();
        $assigncount = count($assigns);


        $datas = ItemAssign::orderBy('balance', 'asc')->paginate(10);
        return view('dashboard', compact('items', 'users', 'itemcount', 'usercount', 'assigncount', 'datas', 'assigns'));
    }

}
