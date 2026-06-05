<?php

use App\Http\Controllers\Api\ListingMapController;
use Illuminate\Support\Facades\Route;

Route::get('/listings/map', [ListingMapController::class, 'index']);
Route::get('/listings/by-ids', [ListingMapController::class, 'byIds']);
