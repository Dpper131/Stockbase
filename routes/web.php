<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\StockController;
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

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
    Route::get('/', [HomeController::class,'index'])->name('home.index');
    Route::get('/stock', [StockController::class,'getPage'])->name('stocks.index');
    Route::get('/stock/{slug}',[StockController::class,'show'])->name('stock.show');
    Route::group(['middleware' => ['guest']], function() {
        Route::post('/', [UserController::class,'login'])->name('login.perform');
        Route::post('/stock', [UserController::class,'login'])->name('login.perform');
        Route::get('/client/{uuid}',[ApplicationController::class,'getClientPage'])->name('client.index');
        Route::get('/client/{uuid}/{id}/delete',[ApplicationController::class,'delete'])->name('appl.delete');
        Route::get('/client/{uuid}/{id}/showContract',[ApplicationController::class,'show'])->name('appl.show1');
        Route::get('/client/{uuid}/{id}',[ApplicationController::class,'showContract'])->name('appl.check1');
        Route::post('/addAppl', [ApplicationController::class, 'add'])->name('addAppl');
        Route::get('client', function (?Request $request){
            return to_route('client.index',['uuid' => $request['uuid']]);
        })->name('client.redirect');

    });

    Route::group(['middleware' => ['auth']], function() {
        Route::get('/users/{id}',[UserController::class,'userProfile'])->name('user.profile');
        Route::get('/users/{id}/delete',[UserController::class,'delete'])->name('user.delete');
        Route::get('/users',[UserController::class,'show'])->name('users');
        Route::post('/changeUserPassword',[UserController::class,'changePassword'])->name('user.chPassword');
        Route::post('/adduser',[UserController::class,'addUser'])->name('user.add');
        Route::get('/logout', function (){
                Session::flush();
                Auth::logout();
                 return redirect('/');
        })->name('logout.perform');
        Route::get('/company',[CompanyController::class,'index'])->name('company.index');
        Route::post('/changeUserInfo', [UserController::class, 'editProfile'])->name('editProfile');
        Route::post('/changePassword', [UserController::class, 'changePasswordMod'])->name('changePassword');
        Route::get('/appl',[ApplicationController::class,'getPage'])->name('appl.index');
        Route::get('/appl/{id}',[ApplicationController::class,'show'])->name('appl.show');
        Route::get('/appl/contr/{id}',[ApplicationController::class,'showContract'])->name('appl.check');
        Route::post('/createcontr', [ApplicationController::class,'createContract'])->name('appl.perform');
        Route::post('/changecount', [StockController::class, 'changeCount'])->name('changeStockCount');
        Route::post('/addstock', [StockController::class, 'addStock'])->name('addStock');
        Route::get('/company/{name}/delete',[CompanyController::class,'delete'])->name('company.delete');
        Route::get('/company/{name}',[CompanyController::class,'show'])->name('company.show');
        Route::post('/editcompany',[CompanyController::class,'editCompany'])->name('company.edit');
        Route::post('/addcompany', [CompanyController::class, 'addCompany'])->name('addCompany');

    });
});
