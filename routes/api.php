<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CatalogSyncController;

Route::middleware('auth:sanctum')->post('/products/sync', [CatalogSyncController::class, 'sync']);