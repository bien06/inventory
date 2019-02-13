<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemAssign;
use App\Item;
use App\UserAssign;
use App\AdminLog;
use Carbon\Carbon;

class QueriesController extends Controller {

    public function __construct() {
        $this->middleware('admin_user');
    }

    public function index() {
        $totalgood = 0;
        $totalcount = 0;
        $totalrejected = 0;
        $totalbalance = 0;
        $totalgood2 = 0;
        $totalcount2 = 0;
        $totalrejected2 = 0;
        $totalbalance2 = 0;
        $fromdate = null;
        $todate = null;
        $fromdate2 = null;
        $todate2 = null;
        $daily = UserAssign::where('updated_at', '>=', Carbon::now()->today())->where('action', 'Update')->get();
        $assigns = ItemAssign::all();
        $admins = AdminLog::where('updated_at', '>=', Carbon::now()->today())->get();
        $items = Item::where('is_deleted', 0)->get();
        return view('admin/query', compact('fromdate2', 'todate2', 'admins', 'items', 'assigns', 'daily', 'totalgood', 'totalcount', 'totalrejected', 'totalbalance', 'totalgood2', 'totalcount2', 'totalrejected2', 'totalbalance2', 'fromdate', 'todate'));
    }

    public function viewAdmin(Request $request) {
        $totalgood = 0;
        $totalcount = 0;
        $totalrejected = 0;
        $totalbalance = 0;
        $totalgood2 = 0;
        $totalcount2 = 0;
        $totalrejected2 = 0;
        $totalbalance2 = 0;
        $fromdate = null;
        $todate = null;
        $fromdate2 = new Carbon($request->fromdate2);
        $todate2 = new Carbon($request->todate2);
        $todate2 = $todate2->addDays(1);

        $daily = UserAssign::where('updated_at', '>=', Carbon::now()->today())->where('action', 'Update')->get();
        $assigns = ItemAssign::all();
        $items = Item::where('is_deleted', 0)->get();
        $admins = AdminLog::whereBetween('created_at', array($fromdate2, $todate2))->get();

        $fromdate2 = $fromdate2->format('F j, Y');
        $todate2 = new Carbon($request->todate2);
        $todate2 = $todate2->format('F j, Y');

//        $from = new Carbon($request->fromdate2);
//        $to = new Carbon($request->todate2);
//        $admins = AdminLog::where('created_at', '>=', $from)->orWhere('created_at', '>=', $to)->get();

        return view('admin.query', compact('admins', 'fromdate', 'todate', 'daily', 'assigns', 'items', 'fromdate2', 'todate2', 'totalgood', 'totalcount', 'totalrejected', 'totalbalance', 'totalgood2', 'totalcount2', 'totalrejected2', 'totalbalance2'));
    }

    public function viewSummary(Request $request) {
        $totalgood = 0;
        $totalcount = 0;
        $totalrejected = 0;
        $totalbalance = 0;
        $totalgood2 = 0;
        $totalcount2 = 0;
        $totalrejected2 = 0;
        $totalbalance2 = 0;
        $fromdate = new Carbon($request->fromdate);
        $todate = new Carbon($request->todate);
        $todate = $todate->addDays(1);
        $fromdate2 = null;
        $todate2 = null;

        $daily = UserAssign::whereBetween('updated_at', array($fromdate, $todate))->where('action', 'Update')->get();
        $admins = AdminLog::where('updated_at', '>=', Carbon::now()->today())->get();

        $fromdate = $fromdate->format('F j, Y');
        $todate = new Carbon($request->todate);
        $todate = $todate->format('F j, Y');

        $assigns = ItemAssign::all();
        $items = Item::where('is_deleted', 0)->get();
        return view('admin/query', compact('fromdate2', 'todate2', 'admins', 'items', 'assigns', 'daily', 'totalgood', 'totalcount', 'totalrejected', 'totalbalance', 'totalgood2', 'totalcount2', 'totalrejected2', 'totalbalance2', 'fromdate', 'todate'));
    }

}
