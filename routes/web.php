<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashBoardController;
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
Route::get('/', [FrontEndController::class, 'index'])->name('home');
Route::get('/doctors', [FrontEndController::class, 'doctor'])->name('frontend.doctor');
Route::get('/new-appointment/{id}/{date}', [FrontEndController::class, 'show'])->name('create.appointment');

Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');
Route::get('/home', [HomeController::class, 'index'])->name('frontend.home');

Auth::routes();

// Patient Routes
Route::group(['middleware' => ['auth', 'patient']], function () {
    Route::get('/user-profile', 'ProfileController@index')->name('profile');
    Route::post('/user-profile', 'ProfileController@store')->name('profile.store');
    Route::post('/profile-pic', 'ProfileController@profilePic')->name('profile.pic');
    Route::post('/book/appointment', 'FrontEndController@store')->name('book.appointment');
    Route::get('/my-booking', 'FrontEndController@myBookings')->name('my.booking');
    Route::get('/my-prescription', 'FrontEndController@myPrescription')->name('my.prescription');
    Route::post('prescription/store', 'FrontEndController@reportUpload')->name('prescription.store');
});
// Admin Routes
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('doctor', 'DoctorController');
    Route::get('/patients', 'PatientListController@index')->name('patients');
    Route::get('/status/update/{id}', 'PatientListController@toggleStatus')->name('admin.update.status');
    Route::get('/all-patients', 'PatientListController@allTimeAppointment')->name('all.appointments');
    Route::resource('/department', 'DepartmentController');
});
// Doctor Routes
Route::group(['middleware' => ['auth', 'doctor']], function () {
    Route::resource('appointment', 'AppointmentController');
    Route::post('/appointment/check', 'AppointmentController@check')->name('appointment.check');
    Route::post('/appointment/update', 'AppointmentController@updateTime')->name('update');
    Route::get('bookings', 'PrescriptionController@index')->name('booking');
    Route::post('prescription', 'PrescriptionController@store')->name('prescription');
    Route::get('/status/update/{id}', 'PatientListController@toggleStatus')->name('checkin.update.status');
    Route::get('/prescription/{userId}/{date}', 'PrescriptionController@show')->name('prescription.show');
    Route::get('/all-prescriptions', 'PrescriptionController@showAllPrescriptions')->name('all.prescriptions');
});
