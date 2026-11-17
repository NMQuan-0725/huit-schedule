<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;

// Trang tổng quan tra cứu và lọc lịch
apeutics: [
    Route::get('/', [ScheduleController::class, 'index']),
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index'),
    
    // API/Endpoint phục vụ thuật toán gợi ý giảng viên
    Route::get('/api/suggest-teachers', [ScheduleController::class, 'suggestTeachers'])->name('schedules.suggest')
]
;