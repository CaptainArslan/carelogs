<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientListController;
use App\Http\Controllers\PrescriptionController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('frontend.index');
});
Route::get('/detail', function () {
    return view('frontend.detail');
});
Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');

// Patient Routes
// Route::group(['middleware' => ['auth', 'patient']], function () {
//     // Profile Routes
//     Route::get('/user-profile', [ProfileController::class, 'index'])->name('profile');
//     Route::post('/user-profile', [ProfileController::class, 'store'])->name('profile.store');
//     Route::post('/profile-pic', [ProfileController::class, 'profilePic'])->name('profile.pic');

//     Route::post('/book/appointment', [FrontEndController::class,'store'])->name('book.appointment');
//     Route::get('/my-booking', [FrontEndController::class ,'myBookings'])->name('my.booking');
//     Route::get('/my-prescription', [FrontEndController::class ,'myPrescription'])->name('my.prescription');
// });
// Admin Routes
// Route::group(['middleware' => ['auth', 'admin']], function () {
//     Route::resource('doctor', 'DoctorController');
//     Route::get('/patients', [PatientListController::class, 'index'])->name('patients');
//     Route::get('/status/update/{id}', [PatientListController::class, 'toggleStatus'])->name('update.status');
//     Route::get('/all-patients', [PatientListController::class, 'allTimeAppointment'])->name('all.appointments');
//     Route::resource('/department', 'DepartmentController');
// });
// Doctor Routes
// Route::group(['middleware' => ['auth', 'doctor']], function () {
//     Route::resource('appointment', 'AppointmentController');
//     Route::post('/appointment/check', [AppointmentController::class, 'check'])->name('appointment.check');
//     Route::post('/appointment/update', [AppointmentController::class, 'updateTime'])->name('update');
//     Route::get('patient-today', [PrescriptionController::class, 'index'])->name('patient.today');
//     Route::post('prescription', [PrescriptionController::class, 'store'])->name('prescription');
//     Route::get('/prescription/{userId}/{date}', [PrescriptionController::class, 'show'])->name('prescription.show');
//     Route::get('/all-prescriptions', [PrescriptionController::class, 'showAllPrescriptions'])->name('all.prescriptions');
// });


// Patient Routes
Route::group(['middleware' => ['auth', 'patient']], function () {
    // Profile Routes
    Route::get('/user-profile', 'ProfileController@index')->name('profile');
    Route::post('/user-profile', 'ProfileController@store')->name('profile.store');
    Route::post('/profile-pic', 'ProfileController@profilePic')->name('profile.pic');

    Route::post('/book/appointment', 'FrontEndController@store')->name('book.appointment');
    Route::get('/my-booking', 'FrontEndController@myBookings')->name('my.booking');
    Route::get('/my-prescription', 'FrontEndController@myPrescription')->name('my.prescription');
});
// Admin Routes
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('doctor', 'DoctorController');
    Route::get('/patients', 'PatientListController@index')->name('patients');
    Route::get('/status/update/{id}', 'PatientListController@toggleStatus')->name('update.status');
    Route::get('/all-patients', 'PatientListController@allTimeAppointment')->name('all.appointments');
    Route::resource('/department', 'DepartmentController');
});
// Doctor Routes
Route::group(['middleware' => ['auth', 'doctor']], function () {
    Route::resource('appointment', 'AppointmentController');
    Route::post('/appointment/check', 'AppointmentController@check')->name('appointment.check');
    Route::post('/appointment/update', 'AppointmentController@updateTime')->name('update');
    Route::get('patient-today', 'PrescriptionController@index')->name('patient.today');
    Route::post('prescription', 'PrescriptionController@store')->name('prescription');
    Route::get('/prescription/{userId}/{date}', 'PrescriptionController@show')->name('prescription.show');
    Route::get('/all-prescriptions', 'PrescriptionController@showAllPrescriptions')->name('all.prescriptions');
});
