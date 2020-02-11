<?php
use App\Rights;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    $users = \App\User::get();

    $str = ['user.delete', 'user.read'];
    foreach ($users as $user) {
        echo $user->name . ' '.' ? :' . (Rights::can($user->id, 'user.read') ? 'oui' : 'non') . ' (id : '.$user->id.')<br>';
    }
    return;
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
