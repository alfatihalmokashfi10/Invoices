<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\InvoiceAchiveController;
use App\Http\Controllers\Invoices_ReportController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\Customers_ReportController;
use App\Http\Controllers\InvoiceAttachmentsController;

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
    return view('auth.login');
});
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


//lAuth::routes();

Route::resource('invoices',InvoiceController::class);
Route::resource('sections',SectionsController::class);
Route::resource('products',ProductController::class);
Route::post('InvoiceAttachments',[InvoiceAttachmentsController::class,'store']);
Route::get('InvoicesDetails/{id}', [ InvoicesDetailsController::class,'edit']);

Route::get('notiInvoicesDetails/{id}', [ InvoicesDetailsController::class,'index']);
Route::get('section/{id}',[ InvoiceController::class,'getproducts']);
Route::get('View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class,'open_file']);
//Route::get('report',  Invoices_ReportController::class,'index');

Route::get('noti_read', [InvoiceController::class,'noti_read']);
Route::get('/Status_show/{id}', [InvoiceController::class,'show'])->name('Status_show');
Route::get('export', [InvoiceController::class,'export']);
Route::get('invoice_paid', [InvoiceController::class,'invoice_paid']);
Route::get('invoice_unpaid',[ InvoiceController::class,'invoice_unpaid']);
Route::get('invoice_partial',[ InvoiceController::class,'invoice_partial']);
Route::post('Search_invoices',[ Invoices_ReportController::class,'Search_invoices']);
Route::get('invoices_report',[ Invoices_ReportController::class,'index']);
Route::post('Search_customers',[ Customers_ReportController::class,'Search_customers']);
Route::get('customers_report',[ Customers_ReportController::class,'index']);
Route::get('Print_invoice/{id}',[ InvoiceController::class,'Print_invoice']);
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

Route::resource('Archive', InvoiceAchiveController::class);
//Route::get('export_invoices', ,[ InvoiceController::class,'export');
Route::post('/Status_Update/{id}', [InvoiceController::class,'Status_Update'])->name('Status_Update');
Route::get('download/{invoice_number}/{file_name}', [InvoicesDetailsController::class,'get_file']);
Route::post('delete_file',[ InvoicesDetailsController::class,'destroy'])->name('delete_file');

Route::get('{page}',[ AdminController::class,'index']);
