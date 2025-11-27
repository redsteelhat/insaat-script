<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes (No authentication required)
Route::prefix('/')->name('public.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');
    Route::get('/hizmetler', [App\Http\Controllers\Public\ServiceController::class, 'index'])->name('services');
    Route::get('/hizmetler/{slug}', [App\Http\Controllers\Public\ServiceController::class, 'show'])->name('services.show');
    Route::get('/projeler', [App\Http\Controllers\Public\PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/projeler/{slug}', [App\Http\Controllers\Public\PortfolioController::class, 'show'])->name('portfolio.show');
    Route::get('/teklif-al', [App\Http\Controllers\Public\LeadController::class, 'create'])->name('quote-request');
    Route::post('/teklif-al', [App\Http\Controllers\Public\LeadController::class, 'store'])->name('quote-request.store');
    Route::get('/blog', [App\Http\Controllers\Public\BlogController::class, 'index'])->name('blog');
    Route::get('/blog/{slug}', [App\Http\Controllers\Public\BlogController::class, 'show'])->name('blog.show');
    Route::get('/iletisim', [App\Http\Controllers\Public\ContactController::class, 'index'])->name('contact');
    Route::post('/iletisim', [App\Http\Controllers\Public\ContactController::class, 'store'])->name('contact.store');
});

// Admin Routes (Requires authentication + admin role)
Route::prefix('/admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin,project_manager,designer,procurement,finance'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Lead & CRM
    Route::resource('leads', App\Http\Controllers\Admin\LeadController::class);
    
    // Quotes
    Route::resource('quotes', App\Http\Controllers\Admin\QuoteController::class);
    Route::post('quotes/{quote}/send', [App\Http\Controllers\Admin\QuoteController::class, 'send'])->name('quotes.send');
    Route::post('quotes/{quote}/duplicate', [App\Http\Controllers\Admin\QuoteController::class, 'duplicate'])->name('quotes.duplicate');
    
    // Projects
    Route::resource('projects', App\Http\Controllers\Admin\ProjectController::class);
    
    // Site Reports
    Route::resource('site-reports', App\Http\Controllers\Admin\SiteReportController::class);
    
    // Contracts
    Route::resource('contracts', App\Http\Controllers\Admin\ContractController::class);
    
    // Change Orders
    Route::resource('change-orders', App\Http\Controllers\Admin\ChangeOrderController::class);
    
    // Vendors
    Route::resource('vendors', App\Http\Controllers\Admin\VendorController::class);
    
    // Purchase Orders
    Route::resource('purchase-orders', App\Http\Controllers\Admin\PurchaseOrderController::class);
    
    // Materials
    Route::resource('materials', App\Http\Controllers\Admin\MaterialController::class);
    
    // Budgets
    Route::resource('budgets', App\Http\Controllers\Admin\BudgetController::class);
    
    // Invoices
    Route::resource('invoices', App\Http\Controllers\Admin\InvoiceController::class);
    
    // Issues
    Route::resource('issues', App\Http\Controllers\Admin\IssueController::class);
});

// Saha/Şantiye Panel Routes (Mobile - Requires authentication + site_supervisor role)
Route::prefix('/saha')->name('site.')->middleware(['auth', 'verified', 'role:site_supervisor'])->group(function () {
    Route::get('/', [App\Http\Controllers\Site\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('projects', App\Http\Controllers\Site\ProjectController::class)->only(['index', 'show']);
    Route::get('/projects/{id}/daily-report', [App\Http\Controllers\Site\DailyReportController::class, 'create'])->name('daily-report.create');
    Route::post('/projects/{id}/daily-report', [App\Http\Controllers\Site\DailyReportController::class, 'store'])->name('daily-report.store');
    Route::post('/projects/{id}/progress', [App\Http\Controllers\Site\ProgressController::class, 'store'])->name('progress.store');
    Route::resource('material-requests', App\Http\Controllers\Site\MaterialRequestController::class);
    Route::resource('issues', App\Http\Controllers\Site\IssueController::class);
});

// Client Portal Routes (Optional - Requires authentication + client role)
Route::prefix('/musteri')->name('client.')->middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::get('/', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('projects', App\Http\Controllers\Client\ProjectController::class)->only(['index', 'show']);
    Route::get('/documents', [App\Http\Controllers\Client\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/invoices', [App\Http\Controllers\Client\InvoiceController::class, 'index'])->name('invoices.index');
});

// Profile routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';