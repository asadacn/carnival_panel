<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientCommentController;
use App\Http\Controllers\CardSellerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotspotZoneController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\HotspotClientController;
use App\Http\Controllers\DueBillController;
use App\Http\Controllers\DueBillPaymentController;
use App\Models\CardSeller;
use App\Models\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\TicketManager;
use App\Http\Livewire\TechnicianManager;
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



// AJAX endpoints for client data (outside auth to test)
Route::get('ajax/clients/{clientId}/bills', [App\Http\Controllers\DueBillController::class, 'getBillsForClient'])->name('ajax.client.bills');

Route::middleware(['auth'])->group(function () {
// ISP Statistics API
Route::get('clients/stats/isp', [ClientController::class, 'getIspStatistics'])->name('clients.isp.stats');

Route::resource('clients', ClientController::class);
//Clients Import Export
Route::get('client/export/', [ClientController::class, 'export'])->name('clients.export');
Route::post('client/import/', [ClientController::class, 'import'])->name('clients.import');
Route::get('client/import/create', [ClientController::class, 'create_import'])->name('clients.import.create');
Route::get('client/erase/', [ClientController::class, 'erase'])->name('clients.erase')->middleware('password.confirm');

// Get client package price
Route::get('clients/{clientId}/package-price', [ClientController::class, 'getPackagePrice'])->name('clients.package-price');

// Client Comments (social-media style)
Route::get('clients/{clientId}/comments', [ClientCommentController::class, 'index'])->name('clients.comments.index');
Route::post('clients/{clientId}/comments', [ClientCommentController::class, 'store'])->name('clients.comments.store');
Route::delete('client-comments/{commentId}', [ClientCommentController::class, 'destroy'])->name('clients.comments.destroy');

//Hotspot Import Export
Route::get('hotspot/export/', [HotspotZoneController::class, 'export'])->name('hotspots.export');
Route::post('hotspot/import/', [HotspotZoneController::class, 'import'])->name('hotspots.import');
Route::get('hotspot/import/create', [HotspotZoneController::class, 'create_import'])->name('hotspots.import.create');
Route::get('hotspot/erase/', [HotspotZoneController::class, 'erase'])->name('hotspots.erase')->middleware('password.confirm');

Route::resource('packages', App\Http\Controllers\PackageController::class);
Route::resource('investments', App\Http\Controllers\InvestmentController::class);

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::resource('hotspotZones', App\Http\Controllers\HotspotZoneController::class);



Route::get('cardSellers/export/', [App\Http\Controllers\CardSellerController::class, 'export'])->name('cardseller.export');
Route::post('cardSellers/import/', [App\Http\Controllers\CardSellerController::class, 'import'])->name('cardseller.import');
Route::get('cardSellers/import/create', [App\Http\Controllers\CardSellerController::class, 'create_import'])->name('cardseller.import.create');
Route::get('cardSellers/erase/', [App\Http\Controllers\CardSellerController::class, 'erase'])->name('cardseller.erase')->middleware('password.confirm');



Route::resource('areas', App\Http\Controllers\AreaController::class);


Route::resource('collectors', App\Http\Controllers\CollectorController::class);

Route::resource('sMSTEMPALTES', App\Http\Controllers\SMS_TEMPALTEController::class);

Route::get('solo_sms',[SmsController::class,"send_sms"])->name('solo_sms');
Route::get('bulk_sms',[SmsController::class,"bulk_sms"])->name('bulk_sms');
Route::get('bulk_sms/preview',[SmsController::class,"preview_bulk_contacts"])->name('bulk_sms.preview');
Route::get('create_bulk_sms',[SmsController::class,"create_bulk_sms"])->name('create_bulk_sms');
Route::get('reg_bulk_sms',[SmsController::class,"reg_bulk_sms"])->name('reg_bulk_sms');
Route::get('sms/log',[SmsController::class,"sms_log"])->name('sms_log');

Route::post('bulk-voice-campaign', [App\Http\Controllers\SmsController::class, 'bulk_voice_campaign'])->name('bulk_voice_campaign');
Route::get('bulk-voice-campaign/{campaignId}/status', [App\Http\Controllers\SmsController::class, 'get_voice_campaign_status'])->name('bulk_voice_campaign.status');


Route::resource('cardSellers', App\Http\Controllers\CardSellerController::class);


Route::resource('hotspotClients', App\Http\Controllers\HotspotClientController::class);
Route::get('hotspotClients/{id}/send-sms', [HotspotClientController::class, 'sendSmsReminder'])->name('hotspotClients.sendSms');
Route::post('hotspotClients/{id}/activate', [HotspotClientController::class, 'activatePackage'])->name('hotspotClients.activate');


Route::get('/tickets/analytics', [App\Http\Controllers\TicketAnalyticsController::class, 'index'])->name('tickets.analytics');

Route::get('/tickets', TicketManager::class)->name('tickets.live');
Route::post('/tickets/send-telegram', [App\Http\Controllers\TicketController::class, 'sendTelegramToGroup'])->name('tickets.send-telegram');

Route::get('/technicians', TechnicianManager::class)->name('technicians');

// Due Bills Management
Route::get('due-bills/report', [App\Http\Controllers\DueBillController::class, 'report'])->name('due-bills.report');
Route::get('due-bills/{id}/mark-paid', [App\Http\Controllers\DueBillController::class, 'markAsPaid'])->name('due-bills.mark-paid');
Route::post('due-bills/send-telegram', [App\Http\Controllers\DueBillController::class, 'sendTelegramNotification'])->name('due-bills.send-telegram');
Route::resource('due-bills', App\Http\Controllers\DueBillController::class);
Route::get('clients/{id}/due-bills', [App\Http\Controllers\DueBillController::class, 'clientBills'])->name('clients.due-bills');
Route::get('clients/{id}/bills-statement-pdf', [App\Http\Controllers\DueBillController::class, 'billStatementPdf'])->name('clients.bills-statement-pdf');

// Due Bill Payments Management
Route::get('due-bill-payments/report', [App\Http\Controllers\DueBillPaymentController::class, 'report'])->name('due-bill-payments.report');
Route::resource('due-bill-payments', App\Http\Controllers\DueBillPaymentController::class);
Route::get('clients/{id}/payment-history', [App\Http\Controllers\DueBillPaymentController::class, 'clientPaymentHistory'])->name('clients.payment-history');
Route::get('clients/{id}/payments-statement-pdf', [App\Http\Controllers\DueBillPaymentController::class, 'paymentStatementPdf'])->name('clients.payments-statement-pdf');

Route::post('/api/check-master-password', [HomeController::class, 'checkMasterPassword']);
});

