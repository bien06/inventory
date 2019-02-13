<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Audit;
use App\AuditUser;

class AuditController extends Controller
{
    public function index(){
    	$users= AuditUser::all();
    	$admins= Audit::all();
    	return view('admin/audit', compact('users', 'admins'));
    }
}
