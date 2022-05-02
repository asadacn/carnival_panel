<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\SmsController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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


Auth::routes();



Route::middleware(['auth'])->group(function () {
Route::resource('clients', ClientController::class);
Route::get('client/export/', [ClientController::class, 'export'])->name('clients.export');
Route::post('client/import/', [ClientController::class, 'import'])->name('clients.import');
Route::get('client/import/create', [ClientController::class, 'create_import'])->name('clients.import.create');

Route::get('client/erase/', [ClientController::class, 'erase'])->name('clients.erase')->middleware('password.confirm');

Route::resource('packages', App\Http\Controllers\PackageController::class);
Route::resource('investments', App\Http\Controllers\InvestmentController::class);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::resource('hotspotZones', App\Http\Controllers\HotspotZoneController::class);


Route::resource('cardSellers', App\Http\Controllers\CardSellerController::class);
});



Route::resource('areas', App\Http\Controllers\AreaController::class);


Route::resource('collectors', App\Http\Controllers\CollectorController::class);

// Route::get('sms',function (Request $request)
// {

//     dd($request->clients);
//     foreach($request->clients as $client){

//   //  dd($client['contact']);

//     $response = Http::get('http://sms.asolution24.com/api/send?key=f044ca72dbd6b15c942f86c9245d836d22806212&priority=1&phone=88'.$client['contact'].'&message=test message');
//         }

//     return $response;
//})->name('sms');


// Route::get('solo_sms',function (Request $request)
// {

//     $client = Client::find($request->client_id);

//     // $response = Http::get('http://sms.asolution24.com/api/send?key=f044ca72dbd6b15c942f86c9245d836d22806212&priority=1&phone=88'.$client->contact.'&message='.$request->sms);

//   // return $response;
// return sms($client->contact,$request->sms);


// })->name('solo_sms');

Route::resource('sMSTEMPALTES', App\Http\Controllers\SMS_TEMPALTEController::class);

Route::get('solo_sms',[SmsController::class,"send_sms"])->name('solo_sms');
Route::get('bulk_sms',[SmsController::class,"bulk_sms"])->name('bulk_sms');
Route::get('sms/log',[SmsController::class,"sms_log"])->name('sms_log');
