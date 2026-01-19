<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::post('login', 'Api\LoginController@login');
Route::post('login-mobile', 'Api\LoginController@login_mobile');
Route::get('company' , 'Api\LoginController@getCompany');
Route::get("hris", "Api\LoginController@api_integration");

Route::get("get-kode-pos/{kodepos?}", "KjkCrmList@import_kode_pos")->name("api.get_kodepos");

Route::get("logout", "Api\LoginController@remove_device");

Route::get("attendance-reminder", "Api\AttendanceController@reminder_attendance");

Route::post("password/email", "Api\ForgotPasswordController@sendResetLinkEmail");

Route::post("/login", "Api\LoginController@login_sso");

Route::group(['middleware' => 'apiauth'], function(){

    Route::get("/branch", "Api\BranchController@list");

    Route::get("/get-locations", "Api\AttendanceController@get_locations");
    Route::get("/attendance/map-coordinates", "Api\AttendanceController@get_map_coordinates");
    Route::get('/articles', "Api\AttendanceController@get_articles");

    Route::post("/change-password", "Api\LoginController@change_password");
    Route::post("/change-image", "Api\LoginController@change_image");


    //user activity
    Route::get('users/{comp_id}','Api\LoginController@getUser');
    Route::get('users/activity/{comp_id}/{user_id}','Api\LoginController@getUserActivty');
    Route::post('users/add_activity','Api\LoginController@addActivity');


    Route::get('notif/{comp_id}/{user_id}','Api\LoginController@getnotif');


    // MAP
    Route::prefix('map')->group(function () {
        Route::get('/', 'Api\MapController@index');
        Route::get('/{type}/{id}', 'Api\MapController@view');
    });


    //attendance
    Route::prefix("attendance")->group(function(){
        Route::get('/detail/{id}', "Api\AttendanceController@getDetail");
        Route::get('/get-date-time', "Api\AttendanceController@getDateTime");
        Route::post('/post', 'Api\AttendanceController@attendance_post');
        Route::get("/history/{id}", 'Api\AttendanceController@attendance_history');
        Route::get("/get-history/{id}", 'Api\AttendanceController@attendance_get_history');

        Route::post("/check-radius", "Api\AttendanceController@check_radius");
    });

    //employee
    Route::prefix("employee")->group(function(){
        Route::get("/find-emp/{compid}", "Api\EmployeeController@find_emp");
    });

    // ESS
    Route::prefix("ess")->group(function(){
        Route::post("get-summary-data", "Api\ESSController@get_summary_data");
        Route::post("get-list-data", "Api\ESSController@get_list_data");
        Route::post("get-attendance-data", "Api\ESSController@get_attendance_data");
        Route::post("get-loan", "Api\ESSController@get_loan");
        Route::post("get-leave", "Api\ESSController@get_leave");
        Route::post("get-cash", "Api\ESSController@get_cash");
        Route::post("get-letter", "Api\ESSController@get_letter");
        Route::post("get-team", "Api\ESSController@get_team");
        Route::post('get-approval-list', "Api\ESSController@get_approval_list");

        Route::post("request-leave", "Api\ESSController@request_leave");
        Route::post("request-overtime", "Api\ESSController@request_overtime");
        Route::post("request-attendance-correction", "Api\ESSController@request_attendance_correction");
        Route::post("request-loan", "Api\ESSController@request_loan");
        Route::post("request-cash", "Api\ESSController@request_cash");
        Route::post("request-employment-letter", "Api\ESSController@request_employment_letter");
    });

});



