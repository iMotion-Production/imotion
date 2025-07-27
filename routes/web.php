<?php

use App\Http\Controllers\Public\DigitalMarketingController;
use App\Http\Controllers\Public\GraphicDesignController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MobileApplicationController;
use App\Http\Controllers\Public\PrintingDesignController;
use App\Http\Controllers\Public\WebsiteDevelopmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/digital-marketing', DigitalMarketingController::class)->name('service.digital-marketing');
Route::get('/printing-design', PrintingDesignController::class)->name('service.printing-design');
Route::get('/web-development', WebsiteDevelopmentController::class)->name('service.web-development');
Route::get('/mobile-application', MobileApplicationController::class)->name('service.mobile-application');
Route::get('/graphic-design', GraphicDesignController::class)->name('service.graphic-design');
