<?php

use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DigitalMarketingController;
use App\Http\Controllers\Public\GraphicDesignController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MobileApplicationController;
use App\Http\Controllers\Public\PrintingDesignController;
use App\Http\Controllers\Public\WebsiteDevelopmentController;
use App\Http\Controllers\Mailbox\AuthController as MailboxAuthController;
use App\Http\Controllers\Mailbox\MailboxController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/digital-marketing', DigitalMarketingController::class)->name('service.digital-marketing');
Route::get('/printing-design', PrintingDesignController::class)->name('service.printing-design');
Route::get('/web-development', WebsiteDevelopmentController::class)->name('service.web-development');
Route::get('/mobile-application', MobileApplicationController::class)->name('service.mobile-application');
Route::get('/graphic-design', GraphicDesignController::class)->name('service.graphic-design');
Route::get('/about', AboutController::class)->name('about');
Route::get('/contact', ContactController::class)->name('contact');

// Client mailbox portal (cPanel). Domain always comes from the logged-in user, never the request.
Route::prefix('mailbox')->name('mailbox.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [MailboxAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [MailboxAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
        Route::get('/reset-password', [MailboxAuthController::class, 'resetWithoutToken'])->name('reset.missing');
        Route::get('/reset-password/{token}', [MailboxAuthController::class, 'showReset'])->name('reset.show');
        Route::post('/reset-password', [MailboxAuthController::class, 'reset'])->middleware('throttle:5,1')->name('reset');
    });

    Route::post('/logout', [MailboxAuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'mail.client'])->group(function () {
        Route::get('/', [MailboxController::class, 'index'])->name('index');
        Route::post('/add', [MailboxController::class, 'add'])->middleware('throttle:20,1')->name('add');
        Route::post('/{address}/password', [MailboxController::class, 'password'])->middleware('throttle:20,1')->name('password');
        Route::post('/{address}/webmail', [MailboxController::class, 'webmail'])->middleware('throttle:20,1')->name('webmail');
        Route::post('/{address}/delete', [MailboxController::class, 'destroy'])->middleware('throttle:20,1')->name('delete');
    });
});
