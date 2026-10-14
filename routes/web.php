<?php

use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LookupController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarRentalVoucherController;
use App\Http\Controllers\HotelVoucherController;
use App\Http\Controllers\TransfersVoucherController;
use App\Http\Controllers\VipMeetAssistVoucherController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

// Voucher Generator (requires login)
Route::middleware(['auth'])->group(function () {
Route::get('/', [HotelVoucherController::class, 'create'])->name('home');
Route::get('/hotel-voucher', [HotelVoucherController::class, 'create'])->name('hotel-voucher.create');
Route::post('/hotel-voucher', [HotelVoucherController::class, 'store'])->name('hotel-voucher.store');
Route::get('/hotel-voucher/{voucher}', [HotelVoucherController::class, 'show'])->name('hotel-voucher.show');
Route::get('/hotel-voucher/{voucher}/pdf', [HotelVoucherController::class, 'downloadPdf'])->name('hotel-voucher.pdf');
Route::post('/hotel-voucher/{voucher}/png', [HotelVoucherController::class, 'savePng'])->name('hotel-voucher.png');
Route::post('/hotel-voucher/{voucher}/save-pdf', [HotelVoucherController::class, 'savePdf'])->name('hotel-voucher.save-pdf');
Route::get('/hotel-voucher/{voucher}/email-pdf', [HotelVoucherController::class, 'emailPdf'])->name('hotel-voucher.email-pdf');

// Car Rental Voucher
Route::get('/car-rental', [CarRentalVoucherController::class, 'create'])->name('car-rental-voucher.create');
Route::post('/car-rental', [CarRentalVoucherController::class, 'store'])->name('car-rental-voucher.store');
Route::get('/car-rental/{voucher}', [CarRentalVoucherController::class, 'show'])->name('car-rental-voucher.show');
Route::get('/car-rental/{voucher}/pdf', [CarRentalVoucherController::class, 'downloadPdf'])->name('car-rental-voucher.pdf');
Route::post('/car-rental/{voucher}/png', [CarRentalVoucherController::class, 'savePng'])->name('car-rental-voucher.png');
Route::post('/car-rental/{voucher}/save-pdf', [CarRentalVoucherController::class, 'savePdf'])->name('car-rental-voucher.save-pdf');
Route::get('/car-rental/{voucher}/email-pdf', [CarRentalVoucherController::class, 'emailPdf'])->name('car-rental-voucher.email-pdf');

// Transfers Voucher
Route::get('/transfers', [TransfersVoucherController::class, 'create'])->name('transfers-voucher.create');
Route::post('/transfers', [TransfersVoucherController::class, 'store'])->name('transfers-voucher.store');
Route::get('/transfers/{voucher}', [TransfersVoucherController::class, 'show'])->name('transfers-voucher.show');
Route::get('/transfers/{voucher}/pdf', [TransfersVoucherController::class, 'downloadPdf'])->name('transfers-voucher.pdf');
Route::post('/transfers/{voucher}/png', [TransfersVoucherController::class, 'savePng'])->name('transfers-voucher.png');
Route::post('/transfers/{voucher}/save-pdf', [TransfersVoucherController::class, 'savePdf'])->name('transfers-voucher.save-pdf');
Route::get('/transfers/{voucher}/email-pdf', [TransfersVoucherController::class, 'emailPdf'])->name('transfers-voucher.email-pdf');

// VIP Meet & Assist Voucher
Route::get('/vip-meet-assist', [VipMeetAssistVoucherController::class, 'create'])->name('vip-meet-assist-voucher.create');
Route::post('/vip-meet-assist', [VipMeetAssistVoucherController::class, 'store'])->name('vip-meet-assist-voucher.store');
Route::get('/vip-meet-assist/{voucher}', [VipMeetAssistVoucherController::class, 'show'])->name('vip-meet-assist-voucher.show');
Route::get('/vip-meet-assist/{voucher}/pdf', [VipMeetAssistVoucherController::class, 'downloadPdf'])->name('vip-meet-assist-voucher.pdf');
Route::post('/vip-meet-assist/{voucher}/png', [VipMeetAssistVoucherController::class, 'savePng'])->name('vip-meet-assist-voucher.png');
Route::post('/vip-meet-assist/{voucher}/save-pdf', [VipMeetAssistVoucherController::class, 'savePdf'])->name('vip-meet-assist-voucher.save-pdf');
Route::get('/vip-meet-assist/{voucher}/email-pdf', [VipMeetAssistVoucherController::class, 'emailPdf'])->name('vip-meet-assist-voucher.email-pdf');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/test-email', function () {
        try {
            $user = auth()->user();
            \Mail::raw('Test email from Alma Travel Voucher System', function ($m) use ($user) {
                $m->to($user->email)->subject('Test Email');
            });
            return 'Email sent to '.$user->email;
        } catch (\Throwable $e) {
            return 'FAILED: '.get_class($e).' - '.$e->getMessage().' at '.$e->getFile().':'.$e->getLine();
        }
    })->name('test-email');
});

Route::get('/login', [AuthController::class, 'showEmployeeLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Legacy admin login routes (redirect to main login)
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.store');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('employees', EmployeeController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('lookups', [LookupController::class, 'index'])->name('lookups.index');
    Route::post('lookups/categories', [LookupController::class, 'storeCategory'])->name('lookups.categories.store');
    Route::patch('lookups/categories/{category}', [LookupController::class, 'updateCategory'])->name('lookups.categories.update');
    Route::delete('lookups/categories/{category}', [LookupController::class, 'destroyCategory'])->name('lookups.categories.destroy');
    Route::post('lookups/categories/{category}/values', [LookupController::class, 'storeValue'])->name('lookups.values.store');
    Route::patch('lookups/values/{value}', [LookupController::class, 'updateValue'])->name('lookups.values.update');
    Route::delete('lookups/values/{value}', [LookupController::class, 'destroyValue'])->name('lookups.values.destroy');
    Route::get('settings', [CompanySettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [CompanySettingController::class, 'update'])->name('settings.update');
    Route::get('vouchers', [AdminVoucherController::class, 'index'])->name('vouchers.index');
    Route::get('vouchers/{voucher}', [AdminVoucherController::class, 'show'])->name('vouchers.show');
    Route::delete('vouchers/{voucher}', [AdminVoucherController::class, 'destroy'])->name('vouchers.destroy');
});
