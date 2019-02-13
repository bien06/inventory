<?php


Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::post('update', 'HomeController@assign');

// routes for admin
Route::get('admin_login', 'AdminAuth\LoginController@showLoginForm');
Route::post('admin_login', 'AdminAuth\LoginController@login');
Route::post('admin_logout', 'AdminAuth\LoginController@logout');

Route::get('/admin_home', 'AdminHomeController@index');

Route::post('admin_password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
Route::get('admin_password/reset', 'AdminAuth\ForgotPasswordController@sendLinkRequestForm');
Route::post('admin_password/reset', 'AdminAuth\ResetPasswordController@reset');
Route::get('admin_password/reset/{token}','AdminAuth\ResetPasswordController@showResetForm');

Route::get('admin', 'AdminController@index');
Route::post('admin/add', 'AdminController@add');
Route::post('admin/update', 'AdminController@update');

// routes for dashboard
Route::get('dashboard', 'DashboardController@index'); 
 
// routes for queries
Route::get('query', 'QueriesController@index'); 
Route::post('query', 'QueriesController@viewSummary');
Route::post('query/search', 'QueriesController@viewAdmin');

// routes for items
Route::get('item', 'ItemController@index');
Route::post('item/add', 'ItemController@store');
Route::post('item/update', 'ItemController@update');
Route::post('item/remove', 'ItemController@remove');

// routes for user
Route::get('users', 'UserController@index');
Route::post('users/add', 'UserController@add');
Route::post('users/update', 'UserController@update');

//routes for location
Route::post('users/add_location', 'UserController@addLoc');
Route::post('users/update_location', 'UserController@updateLoc');

//routes for assigning item
Route::get('assignment', 'ItemController@itemAssign');
Route::post('item/assign', 'ItemController@assign');
Route::post('item/limits', 'ItemController@limits');
Route::post('item/void', 'ItemController@voidItem');
Route::get('item/assign/{id}', 'ItemController@getuser');

// routes for user page
Route::get('home', 'HomeController@index');
Route::get('userindex', 'HomeController@index');
Route::post('assign/update', 'HomeController@assign');

// routes for audit
Route::get('audit', 'AuditController@index');

// routes for change password
Route::get('change_password', 'HomeController@changepassword');
Route::post('change_password', 'HomeController@updatepassword');