<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FeesCollectionController;
use App\Http\Controllers\FeesReportController;
use App\Http\Controllers\StudentReportController;
use App\Http\Controllers\UserController;


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

Route::get('/clear', function() {
    //   $mytime = Carbon\Carbon::now();
    //  return $mytime->toDateTimeString();
    $exitCode = Artisan::call('cache:clear');
     $exitCode = Artisan::call('config:clear');
     $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');

    return '<h1>cleared</h1>';
});
Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('dashboard', [DashboardController::class,'dashboard']);


Route::get('/course', [CourseController::class, 'index'])->name('course.index');
Route::get('/course/create', [CourseController::class, 'create'])->name('course.create');
Route::post('/course/store', [CourseController::class, 'store'])->name('course.store');
Route::get('/course/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');
Route::post('/course/update/{id}', [CourseController::class, 'update'])->name('course.update');
Route::delete('/course/delete/{id}', [CourseController::class, 'destroy'])->name('course.destroy');

Route::get('/scheme', [SchemeController::class, 'index'])->name('scheme.index');
Route::get('/scheme/create', [SchemeController::class, 'create'])->name('scheme.create');
Route::post('/scheme/store', [SchemeController::class, 'store'])->name('scheme.store');
Route::get('/scheme/edit/{id}', [SchemeController::class, 'edit'])->name('scheme.edit');
Route::post('/scheme/update/{id}', [SchemeController::class, 'update'])->name('scheme.update');
Route::delete('/scheme/delete/{id}', [SchemeController::class, 'destroy'])->name('scheme.destroy');


Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students/store',  [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{student}',[StudentController::class, 'destroy'])->name('students.destroy');

Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
Route::get('/fees/report/export', [FeesCollectionController::class, 'export'])
    ->name('fees.report.export');

Route::get('fees', [FeesCollectionController::class,'index'])->name('fees.index');
Route::get('fees/create', [FeesCollectionController::class,'create'])->name('fees.create');
Route::post('fees/store', [FeesCollectionController::class,'store'])->name('fees.store');
Route::delete('fees/{id}', [FeesCollectionController::class,'destroy'])->name('fees.destroy');
Route::get('fees/paid/{student}', [FeesCollectionController::class, 'getPaidFees'])
     ->name('fees.paid');

     Route::get('fees/{id}/edit', [FeesCollectionController::class,'edit'])->name('fees.edit');
Route::put('fees/{id}', [FeesCollectionController::class,'update'])->name('fees.update');

Route::get('/fees/history/{student}', [FeesCollectionController::class, 'history'])
    ->name('fees.history');

Route::get('/fees/summary/{student}/{type}', [FeesCollectionController::class, 'summary'])
    ->name('fees.summary');

Route::get('/fees-report', [FeesReportController::class,'index'])
    ->name('fees.report');

// Route::get('/students/search', [FeesReportController::class,'searchStudents'])
//     ->name('students.search');
Route::get('/fees-report/student-wise', [FeesReportController::class, 'studentWise'])
    ->name('fees.report.student');
    Route::get('/fees/student-report/export', 
    [FeesReportController::class, 'studentWiseExport'])
    ->name('fees.report.student.export');

Route::get('/student-report/date-wise', [StudentReportController::class, 'dateWise'])
    ->name('student.report.date');
    Route::get('/students/report-date/export',
    [StudentReportController::class, 'dateWiseExport'])
    ->name('students.report-date.export');


Route::get('/student-report/status-wise', [StudentReportController::class, 'statusWise'])
    ->name('student.report.status');
    Route::get('students/status-wise/export',
    [StudentReportController::class, 'statusWiseExport']
)->name('students.status.export');

Route::get('/student-report/course-wise', [StudentReportController::class, 'courseWise'])
    ->name('student.report.course');
    Route::get('students/course-wise/export',
    [StudentReportController::class, 'courseWiseExport']
)->name('students.course.export');

    
Route::patch('/students/{student}/status', [StudentController::class, 'updateStatus'])
    ->name('students.status');
Route::get('/students/{id}/details', [StudentController::class, 'details'])
    ->name('students.details');

    Route::get(
    '/fees-report/student/{id}',
    [FeesReportController::class, 'studentView']
)->name('fees.report.student.view');

  Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
