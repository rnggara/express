<?php

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

Auth::routes(['verify' => true]);

Route::get("resetMistake", "UsersController@resetMistake");

Route::get("login-portal", "Auth\LoginController@login_portal")->name("login_portal");
Route::get("login-lms", "Auth\LoginController@login_lms")->name("login_lms");

Route::get("password/email-sent", "Auth\ForgotPasswordController@showEmailSent")->name("forgot.email.sent");
Route::get("password/complete", "Auth\ResetPasswordController@showComplete")->name("forgot.password.complete");

Route::post("email/resend-link", "Auth\VerificationController@resend_email")->name("email.link.resend");

Route::get("login-view", "Auth\LoginController@login");
Route::get("logout-mobile", "Auth\LoginController@logout_mobile")->name("logout.mobile");

Route::get("/telegram-bot/webhook", "Api\TelegramBot@webhook");
Route::get("/telegram-bot/test", "Api\TelegramBot@test");

Route::get("/registration-complete", "Auth\RegisterController@showCompleteRegister");
Route::get("/verification-complete", "Auth\VerificationController@showVerificationComplete");

Route::post("/locale-switch", "LangSwitchController@switch")->name("lang.switch");

Route::get("/test-email", "Mail\MailController@index");

Route::get("/register-employer", "Auth\RegisterController@showRegisterEmployerForm");
Route::post("/register-check-email", "Auth\RegisterController@checkEmail")->name('register.check_email');
Route::post("/register-employer", "Auth\RegisterController@registerEmployer")->name('register.employer');

Route::get("/pengembangan-diri", "HrdQuestion@test_guest")->name("test.guest");

Route::post("/cari-pekerjaan", "JobController@cari_guest")->name("cari.job.guest");

Route::prefix("pekerjaan")->group(function(){
    Route::get("/", "JobController@index")->name("applicant.job_guest.index");
    Route::get("/detail/{id}", "JobController@detail")->name("applicant.job_guest.detail");
    Route::get("/apply/{id}", "JobController@apply_page")->name("applicant.job_guest.apply_page");
    Route::get('/applied', "JobController@applied")->name("applicant.job_guest.applied");

    Route::post("/apply", "JobController@apply")->name("applicant.job_guest.apply");
    Route::post("/search", "JobController@search")->name("applicant.job_guest.search");

    Route::post("/report", "JobController@report")->name("applicant.job_guest.report");
});

Route::prefix("perusahaan")->group(function(){
    Route::get("/", "KjkCompanySearch@index")->name("app.cs_guest.index");
    Route::get("/detail/{id}", "KjkCompanySearch@detail")->name("app.cs_guest.detail");
    Route::get("/review/{id}", "KjkCompanySearch@review")->name("app.cs_guest.review");

    Route::get("/get-location", "KjkCompanySearch@getLocation")->name("app.cs_guest.getLocation");

    Route::post("/search", "KjkCompanySearch@search")->name("app.cs_guest.search");
    Route::post("/review", "KjkCompanySearch@review_post")->name("app.cs_guest.review_post");
    Route::post("/list-reviews/{id}", "KjkCompanySearch@review_list")->name("app.cs_guest.review_list");
    Route::post("/list-job-ads/{id}", "KjkCompanySearch@job_ads")->name("app.cs_guest.job_ads");
});

Route::prefix("cron")->group(function(){
    Route::get("/assign-cuti", "KjkAttLeave@cronCuti");
    Route::get("/update-registration", "KjkAttRegistration@cronUpdate");
    Route::get("/update-transfer", "PersonelRequest@cronPersonel");

    Route::get("/daily-attendance-record", "Api\AttendanceController@attDaily");
});

Route::get("/privacy-policy", function(){
    return view("privacy");
})->name("policy.page");

Route::get("/terms", function(){
    return view("terms");
})->name('term.page');

Route::get("/check-session", "Auth\LoginController@hasSession");


Route::group(['middleware' => 'checkConfig'], function () {

    Route::prefix("artikel")->group(function(){
        Route::get("/", "KjkArtikelController@index")->name("artikel.index");
        Route::get("/detail/{id}", "KjkArtikelController@detail")->name("artikel.detail");
    });

    Route::middleware(['auth', "verified"])->group(function(){
        Route::middleware(['deleted_account', 'complete_profile'])->group(function (){
                include("routes.php");
                include("prototype.php");
        });

        Route::get("/complete-profile", "KjkCompleteProfileController@page")->name("complete.profile");
        Route::get("/complete-profile/page", "KjkCompleteProfileController@index")->name("complete.profile.page");
        Route::get("/complete-profile/skip", "KjkCompleteProfileController@skip")->name("complete.profile.skip");
        Route::post("/complete-profile/step", "KjkCompleteProfileController@step")->name("complete.profile.step");
        Route::post("/complete-profile/post", "KjkCompleteProfileController@post")->name("complete.profile.post");

        Route::get("/deleted-account", function(){
            return view("auth.deleted_account");
        });
    });
    Route::group(['middleware' => 'guest'], function (){
        // Route::get('/', [
        //     'uses' => 'Auth\LoginController@showLoginForm'
        // ]);
        Route::get('/', "LandingPageController@index");
        Route::get('/for-employer', "LandingPageController@index_employer");


        Route::get("/collaborator", "UsersController@uc_verify")->name("account.setting.uc_verify");
        Route::post("/collaborator", "UsersController@uc_update")->name("account.setting.uc_update");
        Route::get("/collaborator/token-expired", "UsersController@uc_expired")->name("account.setting.uc_expired");
        Route::get("/collaborator/success", "UsersController@uc_success")->name("account.setting.uc_success");

        Route::get("/activation", "UsersController@account_activation")->name("account.setting.activation");
        Route::post("/activation-post", "UsersController@account_activation_post")->name("account.setting.activation_post");

    });
    Route::group(['namespace' => 'Config'], function(){
        Route::get('/success', 'InstallWizardController@success')->name('install.success');
    });
});

Route::get("/hrd/employee/pension/receive/{type}/{id?}", "HrdEmployeeController@pension_receive")->name("employee.pension.receive");
Route::post('/hrd/employee/pension/confirm', "HrdEmployeeController@pension_confirm")->name("employee.pension.confirm");

Route::get('contract/{id?}', 'HrdContract@view')->name("hrd.contract.view");
Route::post('contract/approve', "HrdContract@approve")->name("hrd.contract.approve");
Route::post("ppe/do", "HrdContract@ppe_do")->name("employee.hrd.ppe_do");
Route::get("ppe/{id?}", "HrdContract@ppe_emp")->name("hrd.ppe");
Route::get('page/{type}/{id}', "HrdContract@landing_page")->name("hrd.contract.landing");

Route::group(['middleware' => 'isConfig', 'namespace' => 'Config'], function(){
    Route::get('/install', 'InstallWizardController@index')->name('install');
    Route::post('/install/submit', 'InstallWizardController@submit')->name('install.submit');
});

Route::get("callback", "Auth\LoginController@callback");

Route::get("summary-att", "KjkAttCollectData@sumAtt");




