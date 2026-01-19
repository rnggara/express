<?php

use Illuminate\Support\Facades\Artisan;

Route::get("be-orders/", "HomeController@be_order")->name("be.orders");
Route::get("be-orders/views/{id}", "HomeController@be_order_views")->name("be.orders.views");
Route::get("be-orders/invoice/{id}", "HomeController@be_order_invoice")->name("be.orders.invoice");
Route::get("be-address/", "HomeController@be_address")->name("be.address");
Route::post("be-address/", "HomeController@be_address_post")->name("be.address_post");
Route::get("be-clients/", "HomeController@be_clients")->name("be.clients");
Route::get("be-country/", "HomeController@be_settings")->name("be.settings");
Route::post("be-country-update-post-code", "HomeController@be_country_update_post_code")->name("be.country_update_post_code");
Route::post("be-settings/", "HomeController@be_settings_post")->name("be.settings_post");
Route::get("be-vendors/", "HomeController@be_vendors")->name("be.vendors");
Route::post("be-vendors/", "HomeController@be_vendors_post")->name("be.vendors_post");
Route::post("be-vendor-zone/", "HomeController@be_vendors_post_zone")->name("be.vendors_post_zone");
Route::get("be-vendor-zone-remove/{vendor}/{id}", "HomeController@be_vendors_zone_remove")->name("be.vendors_zone_remove");
Route::get("be-zone-pricing/", "HomeController@be_zone_pricing")->name("be.zone_pricing");
Route::get("be-zone-pricing/{id}", "HomeController@be_zone_pricing_detail")->name("be.zone_pricing_detail");
Route::post("be-zone-pricing/store", "HomeController@be_zone_pricing_store")->name("be.zone_pricing_store");
Route::get("be-promo", "HomeController@be_promo")->name("be.promo");
Route::post("be-promo/store", "HomeController@be_promo_store")->name("be.promo.store");
Route::get("be-refund", "HomeController@be_refund")->name("be.refund");
Route::post("be-refund/store", "HomeController@be_refund_store")->name("be.refund.store");

Route::get("be-zone-multiplier/", "HomeController@be_zone_multiplier")->name("be.zone_multiplier");
Route::get("be-zone-multiplier/{id}", "HomeController@be_zone_multiplier_detail")->name("be.zone_multiplier_detail");
Route::post("be-zone-multiplier/store", "HomeController@be_zone_multiplier_store")->name("be.zone_multiplier_store");

Route::get("run-migrate", function(){
    Artisan::call('migrate');
    return "database migrated";
});

Route::prefix("job-managements")->group(function(){
    Route::get("/", "JobVacancyController@index")->name("job.index");
    Route::get('/delete/{id}', "JobVacancyController@delete")->name("job.delete");
    Route::get("/add", "JobVacancyController@showJobAdd")->name("job.add.view");
    Route::get("/confirmation/{id}", "JobVacancyController@confirmation_page")->name("job.confirmation");

    Route::post("/add", "JobVacancyController@add")->name("job.add");
});

Route::prefix("sso-client")->group(function(){
    Route::get("/", "SSOClient@index")->name("sso_client.index");
    Route::get("/callback", "SSOClient@callback")->name("sso_client.callback");

    Route::post("/{type}", "SSOClient@store")->name("sso_client.store");
});

Route::prefix("master-data")->group(function(){
    Route::get("/", "MasterDataController@index")->name("master_data.index");

    // locations
    Route::get("/delete/{state}/{id}", "MasterDataController@delete")->name("master_data.location.delete");

    Route::post("/store", "MasterDataController@store")->name("master_data.location.store");
});

Route::prefix("jobs")->group(function(){
    Route::get("/", "JobController@index")->name("applicant.job.index");
    Route::get("/detail/{id}", "JobController@detail")->name("applicant.job.detail");
    Route::get("/apply/{id}", "JobController@apply_page")->name("applicant.job.apply_page");
    Route::get('/applied', "JobController@applied")->name("applicant.job.applied");

    Route::post("/apply", "JobController@apply")->name("applicant.job.apply");
    Route::post("/search", "JobController@search")->name("applicant.job.search");

    Route::post("/report", "JobController@report")->name("applicant.job.report");
});

Route::prefix("cms")->group(function(){
    Route::prefix("landing-page")->group(function(){
        Route::get("/", "CMSController@applicant")->name("cms.applicant.index");
        Route::post("/update", "CMSController@applicant_update")->name("cms.applicant.update");
        Route::get("/delete/{type}/{id?}", "CMSController@applicant_delete")->name("cms.applicant.delete");
        Route::get("/order/{type}/{id?}", "CMSController@applicant_order")->name("cms.applicant.order");
    });

    Route::prefix("branding")->group(function(){
        Route::get("/", "CMSController@employer")->name("cms.employer.index");
        Route::post("/update", "CMSController@employer_update")->name("cms.employer.update");
        Route::get("/delete/{type}/{id?}", "CMSController@employer_delete")->name("cms.employer.delete");
    });

    Route::prefix("pages")->group(function(){
        Route::get("/", "CMSController@pages_index")->name("cms.pages.index");
        Route::post("/update", "CMSController@pages_update")->name("cms.pages.update");

        Route::get("/{type}/delete/{id?}", "CMSController@pages_delete")->name("cms.pages.delete");
        Route::get("/{type}/order/{direction}/{id?}", "CMSController@pages_order")->name("cms.pages.order");
    });
});

Route::post("/job/bookmark", "JobController@bookmark")->name("applicant.job.bookmark");

Route::prefix("account")->group(function(){
    //account info
    Route::get('/info','UsersController@getDetailUser')->name('account.info');
    Route::post("/attend-code/randomize", 'UsersController@randomize')->name('account.randomize');
    Route::post('/sign/add/{id}', 'UsersController@signAdd')->name('account.sign.add');
    Route::post('/update/password','UsersController@updatePasswordAccount')->name('account.update.password');
    Route::post('/update/detail','UsersController@updateAccountInfo')->name('account.update.info');
    Route::post("/delete-account",'UsersController@deleteAccount')->name('account.delete');
    Route::post("/edit-about", 'UsersController@editAbout')->name('account.edit.about');

    Route::get("/setting", "UsersController@setting_page")->name("account.setting");
    Route::post("/setting/update-profile", "UsersController@updateProfile")->name('account.setting.update_profile');
    Route::post("/setting/change-password", "UsersController@change_password")->name("account.setting.change_password");
    //user-colaborator
    Route::post("/setting/user-collaborator/add", "UsersController@uc_add")->name("account.setting.uc_add");
    Route::post("/setting/user-collaborator/delete/{type}", "UsersController@uc_delete")->name("account.setting.uc_delete");

    Route::post("/company-profile/store", "UsersController@companyStore")->name("account.comp.store");

    //personal data
    Route::post("/personal-data/delete", "UsersController@personalDelete")->name("account.personal.delete");


    //education
    Route::post("/educations/store", "UsersController@eduStore")->name("account.edu.store");
    Route::post("/educations/delete", "UsersController@eduDelete")->name("account.edu.delete");

    //experience
    Route::post("/experience/store", "UsersController@expStore")->name("account.exp.store");
    Route::post("/experience/delete", "UsersController@expDelete")->name("account.exp.delete");

    //portofolio
    Route::post("/portofolio/store", "UsersController@portStore")->name("account.port.store");
    Route::post("/portofolio/delete", "UsersController@portDelete")->name("account.port.delete");

    //social-media
    Route::post("/social-media/store", "UsersController@medsosStore")->name("account.medsos.store");
    Route::post("/social-media/delete", "UsersController@medsosDelete")->name("account.medsos.delete");

    //skill
    Route::post("/skills/store", "UsersController@skillStore")->name("account.skill.store");
    Route::post("/skills/delete", "UsersController@skillDelete")->name("account.skill.delete");

    Route::get("/my-bookmark", "UsersController@myBookmark")->name("account.my_bookmark");
    Route::get("/my-applicant", "UsersController@MyApplicant")->name("account.my_applicant");
    Route::get("/my-applicant/detail/{id}", "UsersController@myApplicantDetail")->name("account.my_applicant_detail");
    Route::post("/my-applicant/reschedule", "UsersController@myApplicantReschedule")->name("account.my_applicant.reschedule");
    Route::post("/my-applicant/update", "UsersController@myApplicantUpdate")->name("account.my_applicant.update");
    Route::get("/my-applicant/cancel/{id}", "UsersController@myApplicantCancel")->name("account.my_applicant_cancel");

    //additional information
    Route::post("/{type}/store", "UsersController@addStore")->name("account.add.store");
    Route::post("/{type}/delete", "UsersController@addDelete")->name("account.add.delete");

    Route::get("/to-pdf/{id}", "UsersController@toPdf")->name("account.to.pdf");

});

Route::prefix("test")->group(function(){
    Route::get("/", "HomeController@test_page")->name("test.page");
    Route::get("/take-exam/{id}", "HrdQuestion@take_exam")->name("test.take_exam");
    Route::get("/result/{id?}", "HrdQuestion@result_page")->name("test.result.page");

    Route::get('/generate-papikostick', "HrdQuestion@generate_papikostick");

    Route::get("/papikostik-psikogram/{id}", "HrdQuestion@papikostik_psikogram")->name("test.papikostik.psikogram");
    Route::get("/disc-psikogram/{id}", "HrdQuestion@disc_psikogram")->name("test.disc.psikogram");
    Route::get("/mbti-psikogram/{id}", "HrdQuestion@mbti_psikogram")->name("test.mbti.psikogram");
    Route::get("/wpt-psikogram/{id}", "HrdQuestion@wpt_psikogram")->name("test.wpt.psikogram");
});

Route::prefix("crm")->group(function(){
    Route::get("/test-drawer", function(){
        return view("_crm.test_drawer");
    });

    Route::post("/export/{type?}", "KjkCrmLeads@exportopp")->name("crm.export.opp");

    Route::get("/modal-address", function(){
        return view("layouts.components.modal_address");
    })->name("crm.modal_address");

    Route::post("/test-drawer", "KjkCrmDashboard@test_drawer");
    //
    Route::get("/lead/crmLead_d", "KjkCrmLeads@crmLead_d")->name('crm.lead.crmLead_d');
    Route::get("/", "KjkCrmDashboard@index")->name('crm.index');

    Route::get('/lead/detail/{id?}', "KjkCrmLeads@crmLeadDetail")->name("crm.lead.detail");

    Route::post("/lead/layout/add", "KjkCrmLeads@crmLayoutAdd")->name("crm.layout.add");
    Route::post("/lead/layout/delete", "KjkCrmLeads@crmLayoutDelete")->name("crm.layout.delete");
    Route::get("/lead/layout/edit/{type}/{id}", "KjkCrmLeads@crmLayoutEdit")->name("crm.layout.edit");

    Route::get("/lead/{layoutid?}", "KjkCrmLeads@crmLeadIndex")->where("layoutid", '[0-9]+')->name("crm.lead.index");
    Route::get("/lead/{layoutid?}/{fid?}/{rowid?}", "KjkCrmLeads@crmLeadAdd")->where("layoutid", '[0-9]+')->where("fid", '[0-9]+')->where("rowid", '[0-9]+')->name("crm.lead.add");
    Route::post("/lead/lead/update-funnel", "KjkCrmLeads@crmLeadUpdateFunnel")->name("crm.lead.update-funnel");
    Route::post("/lead/funnel/update-order", "KjkCrmLeads@crmFunnelUpdateOrder")->name("crm.funnel.update-order");

    Route::post("/lead/store", "KjkCrmLeads@crmLeadStore")->name("crm.lead.store");
    Route::post("/lead/update", "KjkCrmLeads@crmLeadUpdate")->name("crm.lead.update");
    Route::get("/lead/delete/{id?}", "KjkCrmLeads@crmLeadDelete")->name("crm.lead.delete");
    Route::get("/lead/archive/{id?}", "KjkCrmLeads@crmLeadArchive")->name("crm.lead.archive");

    Route::get("/lead/delete-detail/{type}/{id}", "KjkCrmLeads@crmLeadDeleteDetail")->name("crm.lead.delete_detail");

    Route::post("/lead/notes/add", "KjkCrmLeads@crmLeadNotesAdd")->name("crm.lead.notes.add");
    Route::post("/lead/task/add", "KjkCrmLeads@crmLeadTaskAdd")->name("crm.lead.task.add");
    Route::post("/lead/file/add", "KjkCrmLeads@crmLeadFileAdd")->name("crm.lead.file.add");

    Route::post("/lead/funnel/add", "KjkCrmLeads@crmFunnelAdd")->name("crm.funnel.add");
    Route::post("/lead/funnel/delete", "KjkCrmLeads@crmFunnelDelete")->name("crm.funnel.delete");
    Route::post("/lead/company/add", "KjkCrmLeads@crmCompanyAdd")->name("crm.company.add");
    Route::post("/lead/contact/add", "KjkCrmLeads@crmContactAdd")->name("crm.contact.add");

    Route::post("/lead/comment/add", "KjkCrmLeads@crmCommentAdd")->name("crm.comment.add");
    Route::get("/lead/comment/view/{type?}/{id?}", "KjkCrmLeads@crmCommentView")->name("crm.comment.view");
    Route::get("/lead/comment/delete/{id?}", "KjkCrmLeads@crmCommentDelete")->name("crm.comment.delete");

    Route::prefix("/archive")->group(function(){
        Route::get("/", "KjkCrmArchive@index")->name("crm.archive.index");
        Route::get("/recover/{type}/{id}", "KjkCrmArchive@recover")->name("crm.archive.recover");
    });

    Route::prefix("/products")->group(function(){
        Route::get("/", "KjkCrmProducts@index")->name("crm.products.index");
        Route::get("/detail/{id?}", "KjkCrmProducts@detail")->name("crm.products.detail");
        Route::post("/add", "KjkCrmProducts@add")->name("crm.products.add");
        Route::get("/delete/{id}", "KjkCrmProducts@delete")->name("crm.products.delete");
        Route::get("/archive/{id?}", "KjkCrmProducts@archive")->name("crm.products.archive");
    });

    Route::prefix("/list")->group(function(){
        Route::get("/", "KjkCrmList@index")->name("crm.list.index");
        Route::get("/{type?}/view/{id?}", "KjkCrmList@view")->name("crm.list.view");
        Route::post("/{type}/add-notes", "KjkCrmList@add_notes")->name("crm.list.add_notes");
        Route::post("/{type}/add-files", "KjkCrmList@add_files")->name("crm.list.add_files");
        Route::post("/{type}/add", "KjkCrmList@add")->name("crm.list.add");
        Route::post("/{type}/import", "KjkCrmList@import")->name("crm.list.import");
        Route::get("/{type}/delete/{id}", "KjkCrmList@delete")->name("crm.list.delete");
        Route::get("/{type}/archive/{id?}", "KjkCrmList@archive")->name("crm.list.archive");
        Route::get("/{type?}/hierarchy/{id?}", "KjkCrmList@hierarchy")->name("crm.list.hierarchy");
        Route::get("/{view?}/delete-detail/{type?}/{id?}", "KjkCrmList@delete_detail")->name("crm.list.delete_detail");

        Route::post("/comment/add/{view?}", "KjkCrmList@crmCommentAdd")->name("crm.list.comment.add");
        Route::get("/comment/view/{view?}/{type?}/{id?}", "KjkCrmList@crmCommentView")->name("crm.list.comment.view");
        Route::get("/comment/delete/{view?}/{id?}", "KjkCrmList@crmCommentDelete")->name("crm.list.comment.delete");
    });
});

Route::prefix("attendance")->group(function(){
    Route::get("/", "KjkAttendanceController@index")->name("attendance.index");

    Route::get("/widget/{widget_key?}", "KjkAttendanceController@update_widget")->name("attendance.update_widget");
    Route::get("/widget-chart/{widget_key?}", "KjkAttendanceController@chart_widget")->name("attendance.chart_widget");

    Route::prefix("registration")->group(function(){
        Route::get("/", "KjkAttRegistration@index")->name("attendance.registration.index");
        Route::get("/detail/{id}", "KjkAttRegistration@detail")->name("attendance.registration.detail");

        Route::post("/store", "KjkAttRegistration@store")->name("attendance.registration.store");
        Route::post("/update", "KjkAttRegistration@update")->name("attendance.registration.update");
    });

    Route::prefix("approval")->group(function(){
        Route::get("/", "KjkAttApproval@index")->name("attendance.approval.index");

        Route::post("/approval", "KjkAttApproval@approve")->name("attendance.approval.approve");
    });

    Route::prefix("leave")->group(function(){
        Route::get("/", "KjkAttLeave@index")->name("attendance.leave.index");

        Route::get("/detail/{type?}/{id?}", "KjkAttLeave@detail")->name("attendance.leave.detail");

        Route::post("/request-leave", "KjkAttLeave@request_leave")->name("attendance.leave.request_leave");
        Route::post("/sold-leave", "KjkAttLeave@sold_leave")->name("attendance.leave.sold_leave");
        Route::post("/extend-leave", "KjkAttLeave@extend_leave")->name("attendance.leave.extend_leave");
        Route::post("/approve", "KjkAttLeave@approve")->name("attendance.leave.approve");
        Route::post("/update-leave", "KjkAttLeave@update_leave")->name('attendance.leave.update_leave');
    });

    Route::prefix("/schedule")->group(function(){
        Route::get("/", "KjkAttSchedule@index")->name("attendance.schedule.index");

        Route::post("/generate", "KjkAttSchedule@generate")->name("attendance.schedule.generate");
    });

    Route::prefix("/correction")->group(function(){
        Route::get("/", "KjkAttCorrection@index")->name("attendance.correction.index");

        Route::get("/attendance/{id?}", "KjkAttCorrection@att_detail")->name("attendance.correction.detail");
        Route::get("/attendance-detail/{id?}", "KjkAttCorrection@att_detail_edit")->name("attendance.correction.detail_edit");

        Route::post("/attendance-correction", "KjkAttCorrection@attendance_correction")->name("attendance.correction.attendance");
        Route::post("/employee-correction", "KjkAttCorrection@employee_correction")->name("attendance.correction.employee");
        Route::post("/workgroup-correction", "KjkAttCorrection@workgroup_correction")->name("attendance.correction.workgroup");
    });

    Route::prefix("/collect-data")->group(function(){
        Route::get("/", "KjkAttCollectData@index")->name('attendance.collect_data.index');
        Route::get("/history/{id}", "KjkAttCollectData@history")->name('attendance.collect_data.history');
        Route::post('/process', "KjkAttCollectData@process")->name('attendance.collect_data.process');
        Route::post('/finalize-process', "KjkAttCollectData@finalize_process")->name('attendance.collect_data.finalize_process');
    });

    Route::prefix("/overtime")->group(function(){
        Route::get("/", "KjkAttOvertime@index")->name("attendance.overtime.index");
        Route::get("/detail/{id?}", "KjkAttOvertime@detail")->name("attendance.overtime.detail");

        Route::post("/store", "KjkAttOvertime@store")->name('attendance.overtime.store');
    });

    Route::prefix("approval-attendance")->group(function(){
        Route::get("/", "KjkAttApprovalAttendance@index")->name('attendance.approval_attendance.index');
    });
});

Route::prefix("personel")->group(function(){

    Route::get("/", "PersonelDashboard@index")->name("personel.index");

    Route::prefix("add-employee")->group(function(){
        Route::get("/", "PersonelEmployeeTable@add_employee")->name("personel.add_employee");
        Route::post("/step", "PersonelEmployeeTable@step")->name("personel.step");
        Route::post("/post", "PersonelEmployeeTable@add_employee_post")->name("personel.add_employee.post");
    });

    Route::prefix("employee-table")->group(function(){
        Route::get("/", "PersonelEmployeeTable@index")->name("personel.employee_table.index");
        Route::get("/detail/{id?}", "PersonelEmployeeTable@detail")->name("personel.employee_table.detail");

        Route::post("/resign", "PersonelEmployeeTable@resign")->name("personel.employee_table.resign");
        Route::post("/offence", "PersonelEmployeeTable@offence")->name("personel.employee_table.offence");
        Route::post("/transfer", "PersonelEmployeeTable@transfer")->name("personel.employee_table.transfer");
        Route::post("/reactive", "PersonelEmployeeTable@reactive")->name("personel.employee_table.reactive");

        Route::post("/profile-store", "PersonelEmployeeTable@profile_store")->name("personel.employee_table.profile.store");

        Route::post("/export", "PersonelEmployeeTable@export")->name("personel.employee_table.export");

        Route::post("/document-store", "PersonelEmployeeTable@document_store")->name("personel.employee_table.document.store");

        Route::post("/family-store", "PersonelEmployeeTable@family_store")->name("personel.employee_table.family.store");
        Route::get("/family-delete/{id}", "PersonelEmployeeTable@family_delete")->name("personel.employee_table.family.delete");

        Route::post("/education-store", "PersonelEmployeeTable@education_store")->name("personel.employee_table.education.store");
        Route::get("/education-delete/{id}", "PersonelEmployeeTable@education_delete")->name("personel.employee_table.education.delete");

        Route::post("/work-store", "PersonelEmployeeTable@work_store")->name("personel.employee_table.work.store");
        Route::get("/work-delete/{id}", "PersonelEmployeeTable@work_delete")->name("personel.employee_table.work.delete");

        Route::post("/language-store", "PersonelEmployeeTable@language_store")->name("personel.employee_table.language.store");
        Route::get("/language-delete/{id}", "PersonelEmployeeTable@language_delete")->name("personel.employee_table.language.delete");

        Route::post("/mcu-store", "PersonelEmployeeTable@mcu_store")->name("personel.employee_table.mcu.store");
        Route::get("/mcu-delete/{id}", "PersonelEmployeeTable@mcu_delete")->name("personel.employee_table.mcu.delete");

        Route::post("/license-store", "PersonelEmployeeTable@license_store")->name("personel.employee_table.license.store");
        Route::get("/license-delete/{id}", "PersonelEmployeeTable@license_delete")->name("personel.employee_table.license.delete");

        Route::post("/asset-store", "PersonelEmployeeTable@asset_store")->name("personel.employee_table.asset.store");

        Route::post("/update-data", "PersonelEmployeeTable@update_data")->name("personel.employee_table.update_data");

        Route::get("/formal-letter/{id?}", "PersonelEmployeeTable@formal_letter")->name("personel.employee_table.formal_letter");

        Route::post("formal-letter", "PersonelEmployeeTable@formal_letter_post")->name("personel.employee_table.flpost");

    });

    Route::prefix("request")->group(function(){
        Route::get("/", "PersonelRequest@index")->name("personel.request.index");

        Route::post("/cancel-request/{id}", "PersonelRequest@cancel_request")->name("personel.request.cancel_request");
        Route::post("/action", "PersonelRequest@action")->name("personel.request.action");
    });

    Route::prefix("onboarding")->group(function(){
        Route::get("/", "PersonelOnboarding@index")->name("personel.onboarding.index");

        Route::get("/upload-data/{id?}", "PersonelOnboarding@upload_data")->name('personel.onboarding.upload');
        Route::get("/approve-data/{id?}", "PersonelOnboarding@approve_data")->name('personel.onboarding.approve_data');

        Route::post("/store", "PersonelOnboarding@store")->name("personel.onboarding.store");
        Route::post("/update", "PersonelOnboarding@update")->name("personel.onboarding.update");
        Route::post("/approve", "PersonelOnboarding@approve")->name("personel.onboarding.approve");
        Route::post("/detail-update", "PersonelOnboarding@detail_update")->name("personel.onboarding.detail_update");
    });

    Route::prefix("formal-letter")->group(function(){
        Route::get("/", "PersonelFormalLetter@index")->name("personel.fl.index");
        Route::get("/add", "PersonelFormalLetter@add")->name("personel.fl.add");
        Route::get("/fields", "PersonelFormalLetter@get_field")->name("personel.fl.get_field");
        Route::get("/delete/{id}", "PersonelFormalLetter@delete")->name("personel.fl.delete");
        Route::get("/print/{id}", "PersonelFormalLetter@print")->name("personel.fl.print");

        Route::post("/ajaxField", "PersonelFormalLetter@ajaxField")->name("personel.fl.ajaxField");
        Route::post("/save", "PersonelFormalLetter@save")->name("personel.fl.save");
        Route::post("/upload-attachment", "PersonelFormalLetter@upload_attachment")->name("personel.fl.upload_attachment");
        Route::post("/approve-request", "PersonelFormalLetter@approve_request")->name('personel.fl.approve_request');
    });
});

Route::prefix("preferences")->group(function(){
    Route::get("/", "KjkPreferences@index")->name("crm.pref.index");

    Route::prefix("attendance")->group(function(){
        Route::prefix("reason-name")->group(function(){
            Route::get("/", "KjkPreferenceAttendance@reason_name_index")->name("crm.pref.attendance.reason_name.index");
            Route::get("/detail/{type?}/{id?}", "KjkPreferenceAttendance@reason_name_detail")->name("crm.pref.attendance.reason_name.detail");
            Route::get("/delete/{type?}/{id?}", "KjkPreferenceAttendance@reason_name_delete")->name("crm.pref.attendance.reason_name.delete");

            Route::post("/store", "KjkPreferenceAttendance@reason_name_store")->name("crm.pref.attendance.reason_name.store");
        });

        Route::prefix("workgroup")->group(function(){
            Route::get("/", "KjkPreferenceAttendance@workgroup_index")->name("crm.pref.attendance.workgroup.index");

            Route::get("/detail/{type?}/{id?}", "KjkPreferenceAttendance@workgroup_detail")->name("crm.pref.attendance.workgroup.detail");
            Route::get("/delete/{type?}/{id?}", "KjkPreferenceAttendance@workgroup_delete")->name("crm.pref.attendance.workgroup.delete");

            Route::post("/store", "KjkPreferenceAttendance@workgroup_store")->name("crm.pref.attendance.workgroup.store");
        });

        Route::prefix("overtime")->group(function(){
            Route::get("/", "KjkPreferenceAttendance@overtime_index")->name("crm.pref.attendance.overtime.index");

            Route::get("/detail/{type?}/{id?}", "KjkPreferenceAttendance@overtime_detail")->name("crm.pref.attendance.overtime.detail");
            Route::get("/delete/{type?}/{id?}", "KjkPreferenceAttendance@overtime_delete")->name("crm.pref.attendance.overtime.delete");

            Route::post("/store", "KjkPreferenceAttendance@overtime_store")->name("crm.pref.attendance.overtime.store");
        });

        Route::prefix("leave")->group(function(){
            Route::get("/", "KjkPreferenceAttendance@leave_index")->name("crm.pref.attendance.leave.index");

            Route::get("/detail/{id?}", "KjkPreferenceAttendance@leave_detail")->name("crm.pref.attendance.leave.detail");
            Route::get("/delete/{id?}", "KjkPreferenceAttendance@leave_delete")->name("crm.pref.attendance.leave.delete");

            Route::post("/store", "KjkPreferenceAttendance@leave_store")->name("crm.pref.attendance.leave.store");
        });

        Route::prefix("machine-type")->group(function(){
            Route::get("/", "KjkPreferenceAttendance@machine_type_index")->name("crm.pref.attendance.machine_type.index");

            Route::get("/detail/{type?}/{id?}", "KjkPreferenceAttendance@machine_type_detail")->name("crm.pref.attendance.machine_type.detail");
            Route::get("/delete/{type?}/{id?}", "KjkPreferenceAttendance@machine_type_delete")->name("crm.pref.attendance.machine_type.delete");

            Route::post("/store", "KjkPreferenceAttendance@machine_type_store")->name("crm.pref.attendance.machine_type.store");
        });

        Route::prefix("holiday-table")->group(function(){
            Route::get("/", "KjkPreferenceAttendance@holiday_table_index")->name("crm.pref.attendance.holiday_table.index");
            Route::get("/detail/{id?}", "KjkPreferenceAttendance@holiday_table_detail")->name("crm.pref.attendance.holiday_table.detail");
            Route::get("/delete/{id?}", "KjkPreferenceAttendance@holiday_table_delete")->name("crm.pref.attendance.holiday_table.delete");

            Route::post("/store", "KjkPreferenceAttendance@holiday_table_store")->name("crm.pref.attendance.holiday_table.store");
            Route::post("/assign-year", "KjkPreferenceAttendance@holiday_table_assign_year")->name("crm.pref.attendance.holiday_table.assign_year");
        });

        Route::prefix("periode")->group(function(){
            Route::get("/", "KjkPreferenceAttendance@periode_index")->name("crm.pref.attendance.periode.index");

            Route::get("/detail/{id?}", "KjkPreferenceAttendance@periode_detail")->name("crm.pref.attendance.periode.detail");
            Route::get("/delete/{id?}", "KjkPreferenceAttendance@periode_delete")->name("crm.pref.attendance.periode.delete");

            Route::post("/store", "KjkPreferenceAttendance@periode_store")->name("crm.pref.attendance.periode.store");
        });

        Route::prefix("preferences")->group(function(){
            Route::get("/", "KjkPreferenceAttendance@preferences_index")->name("crm.pref.attendance.preferences.index");

            Route::post("/store", "KjkPreferenceAttendance@preferences_store")->name("crm.pref.attendance.preferences.store");
        });
    });

    Route::prefix("personel")->group(function(){
        Route::prefix("employee-status")->group(function(){
            Route::get("/", "KjkPreferencePersonel@employee_status")->name("crm.pref.personel.employee_status.index");
            Route::post("/post", "KjkPreferencePersonel@employee_status_post")->name("crm.pref.personel.employee_status.post");
        });
        Route::prefix("identity")->group(function(){
            Route::get("/", "KjkPreferencePersonel@identity")->name("crm.pref.personel.identity.index");
            Route::post("/post", "KjkPreferencePersonel@identity_post")->name("crm.pref.personel.identity.post");
        });
        Route::prefix("education")->group(function(){
            Route::get("/", "KjkPreferencePersonel@education")->name("crm.pref.personel.education.index");
            Route::post("/post", "KjkPreferencePersonel@education_post")->name("crm.pref.personel.education.post");
        });
        Route::prefix("major")->group(function(){
            Route::get("/", "KjkPreferencePersonel@major")->name("crm.pref.personel.major.index");
            Route::post("/post", "KjkPreferencePersonel@major_post")->name("crm.pref.personel.major.post");
        });
        Route::prefix("language")->group(function(){
            Route::get("/", "KjkPreferencePersonel@language")->name("crm.pref.personel.language.index");
            Route::post("/post", "KjkPreferencePersonel@language_post")->name("crm.pref.personel.language.post");
        });
        Route::prefix("religion")->group(function(){
            Route::get("/", "KjkPreferencePersonel@religion")->name("crm.pref.personel.religion.index");
            Route::post("/post", "KjkPreferencePersonel@religion_post")->name("crm.pref.personel.religion.post");
        });
        Route::prefix("marital-status")->group(function(){
            Route::get("/", "KjkPreferencePersonel@marital_status")->name("crm.pref.personel.marital_status.index");
            Route::post("/post", "KjkPreferencePersonel@marital_status_post")->name("crm.pref.personel.marital_status.post");
        });
        Route::prefix("licenses")->group(function(){
            Route::get("/", "KjkPreferencePersonel@licenses")->name("crm.pref.personel.licenses.index");
            Route::post("/post", "KjkPreferencePersonel@licenses_post")->name("crm.pref.personel.licenses.post");
        });
        Route::prefix("blood-type")->group(function(){
            Route::get("/", "KjkPreferencePersonel@blood_type")->name("crm.pref.personel.blood_type.index");
            Route::post("/post", "KjkPreferencePersonel@blood_type_post")->name("crm.pref.personel.blood_type.post");
        });
        Route::prefix("gender")->group(function(){
            Route::get("/", "KjkPreferencePersonel@gender")->name("crm.pref.personel.gender.index");
            Route::post("/post", "KjkPreferencePersonel@gender_post")->name("crm.pref.personel.gender.post");
        });
        Route::prefix("custom-properties")->group(function(){
            Route::get("/", "KjkPreferencePersonel@custom_properties")->name("crm.pref.personel.custom_properties.index");
            Route::post("/post", "KjkPreferencePersonel@custom_properties_post")->name("crm.pref.personel.custom_properties.post");
        });

        Route::prefix("onboarding")->group(function(){
            Route::prefix("form-database")->group(function(){
                Route::get("/", "KjkPreferenceOnboarding@fd_index")->name("crm.pref.onboarding.fd.index");
                Route::post("/post", "KjkPreferenceOnboarding@fd_post")->name("crm.pref.onboarding.fd.post");
            });

            Route::prefix("onboarding-template")->group(function(){
                Route::get("/", "KjkPreferenceOnboarding@ot_index")->name("crm.pref.onboarding.ot.index");
                Route::post("/post", "KjkPreferenceOnboarding@ot_post")->name("crm.pref.onboarding.ot.post");
            });
        });

        Route::prefix("approval")->group(function(){
            Route::prefix("transfer")->group(function(){
                Route::get("/", "KjkPreferencePersonalApproval@index")->name("crm.pref.personel.approval.index");
                Route::post("/", "KjkPreferencePersonalApproval@save")->name("crm.pref.personel.approval.save");
            });
        });
    });

    Route::prefix("crm")->group(function(){
        Route::prefix("dashboard")->group(function(){
            Route::get("/", "KjkPreferences@dashboard_index")->name("crm.pref.crm.dashboard.index");
            Route::get("/update/{key?}/{status?}", "KjkPreferences@dashboard_update")->name("crm.pref.crm.dashboard.update");
        });

        Route::prefix("permission")->group(function(){
            Route::get("/", "KjkPreferences@permission_index")->name("crm.pref.crm.permission.index");
            Route::post("/store", "KjkPreferences@permission_store")->name("crm.pref.crm.permission.store");
        });

        Route::prefix("user")->group(function(){
            Route::get("/", "KjkPreferences@user_index")->name("crm.pref.crm.user.index");
            Route::get("/detail/{id?}", "KjkPreferences@user_detail")->name("crm.pref.crm.user.detail");
            Route::post("/store/{type?}", "KjkPreferences@user_store")->name("crm.pref.crm.user.store");
            Route::get("/archive/{type?}/{id?}", "KjkPreferences@user_archive")->name("crm.pref.crm.user.archive");
            Route::get("/edit/{type?}/{id?}", "KjkPreferences@user_edit")->name("crm.pref.crm.user.edit");

            Route::get("/get-job-title/{type}/{parent_id?}", "KjkPreferences@user_get_job_title")->name("crm.pref.crm.user.get_job_title");
            Route::get("/delete-job-title/{id?}", "KjkPreferences@user_delete_job_title")->name("crm.pref.crm.user.delete_job_title");
            Route::post("/edit-job-title/{id?}", "KjkPreferences@user_edit_job_title")->name("crm.pref.crm.user.edit_job_title");
        });

        Route::prefix("opportunity")->group(function(){
            Route::get("/", "KjkPreferences@opportunity_index")->name("crm.pref.crm.opportunity.index");
            Route::get("/detail/{id?}", "KjkPreferences@opportunity_detail")->name("crm.pref.crm.opportunity.detail");
            Route::get("/detail-funnel", "KjkPreferences@opportunity_add_funnel")->name("crm.pref.crm.opportunity.add_funnel");
            Route::get("/properties/{id?}", "KjkPreferences@properties_view")->name("crm.pref.crm.opportunity.properties");
            Route::get("/change-status/{id?}", "KjkPreferences@opportunity_change_status")->name("crm.pref.crm.opportunity.change_status");
            Route::get("/archive/{id?}", "KjkPreferences@opportunity_archive")->name("crm.pref.crm.opportunity.archive");
            Route::post("/store", "KjkPreferences@opportunity_store")->name("crm.pref.crm.opportunity.store");
        });

        Route::prefix("properties")->group(function(){
            Route::get("/preview", "KjkPreferences@properties_preview")->name("crm.pref.crm.properties.preview");
            Route::get("/additional/{type?}", "KjkPreferences@properties_additional")->name("crm.pref.crm.properties.additional");
            Route::post("/store", "KjkPreferences@properties_store")->name("crm.pref.crm.properties.store");
            Route::get("/change-status/{id?}", "KjkPreferences@properties_change_status")->name("crm.pref.crm.properties.change_status");
            Route::get("/archive/{id?}", "KjkPreferences@properties_archive")->name("crm.pref.crm.properties.archive");
            Route::get("/detail/{id?}", "KjkPreferences@properties_detail")->name("crm.pref.crm.properties.detail");
        });

        Route::get("/company", "KjkPreferences@company_index")->name("crm.pref.crm.company.index");
        Route::get("/contact", "KjkPreferences@contact_index")->name("crm.pref.crm.contact.index");
        Route::get("/file", "KjkPreferences@file_index")->name("crm.pref.crm.file.index");
        Route::get("/product", "KjkPreferences@product_index")->name("crm.pref.crm.product.index");
    });

    Route::prefix("general")->group(function(){
        Route::prefix("basic-information")->group(function(){
            Route::get("/", "KjkPreferenceGeneral@basic_information_index")->name("crm.pref.general.basic_information.index");
            Route::post("/update", "KjkPreferenceGeneral@basic_information_post")->name("crm.pref.general.basic_information.post");
        });
        Route::prefix("password")->group(function(){
            Route::get("/", "KjkPreferenceGeneral@password_index")->name("crm.pref.general.password.index");
            Route::post("/reset-password", "KjkPreferenceGeneral@password_post")->name("crm.pref.general.password.post");
        });
    });

    Route::prefix("user-access-control")->group(function(){
        Route::prefix("role")->group(function(){
            Route::get("/", "KjkPreferenceUAC@role_index")->name("crm.pref.uac.role.index");
            Route::get("/permission/{id?}", "KjkPreferenceUAC@role_permission")->name("crm.pref.uac.role.permission");
            Route::get("/permission-remove/{type}/{id}/{key}", "KjkPreferenceUAC@role_permission_remove")->name("crm.pref.uac.role.permission_remove");
            Route::post("/store", "KjkPreferenceUAC@role_post")->name("crm.pref.uac.role.post");
            Route::post("/permission-update", "KjkPreferenceUAC@role_permission_update")->name("crm.pref.uac.role.permission_update");
        });
        Route::prefix("user")->group(function(){
            Route::get("/", "KjkPreferenceUAC@user_index")->name("crm.pref.uac.user.index");
            Route::post("/store", "KjkPreferenceUAC@user_post")->name("crm.pref.uac.user.post");
        });
    });

    Route::prefix("company")->group(function(){
        Route::prefix("company-list")->group(function(){
            Route::get("/", "KjkPreferenceCompany@company_list_index")->name("crm.pref.company.company_list.index");
            Route::get("/detail/{id}", "KjkPreferenceCompany@company_list_detail")->name("crm.pref.company.company_list.detail");
            Route::get("/setting/{type}/{id}", "KjkPreferenceCompany@company_list_setting")->name("crm.pref.company.company_list.setting");
            Route::post("/update", "KjkPreferenceCompany@company_list_post")->name("crm.pref.company.company_list.post");
            Route::post("/update-detail/{type}/{id}", "KjkPreferenceCompany@company_list_update_detail")->name("crm.pref.company.company_list.update_detail");

            Route::prefix("/structure")->group(function(){
                Route::get("/setting/{id}", "KjkPreferenceCompany@company_list_structure")->name("crm.pref.company.company_list.structure");

                Route::prefix("job-level/{id}")->group(function(){
                    Route::get("/", "KjkPreferenceCompany@job_level_index")->name("crm.pref.company.job_level.index");
                    Route::post("/store", "KjkPreferenceCompany@job_level_post")->name("crm.pref.company.job_level.post");
                });
                Route::prefix("job-grade/{id}")->group(function(){
                    Route::get("/", "KjkPreferenceCompany@job_grade_index")->name("crm.pref.company.job_grade.index");
                    Route::post("/store", "KjkPreferenceCompany@job_grade_post")->name("crm.pref.company.job_grade.post");
                });
                Route::prefix("departement/{id}")->group(function(){
                    Route::get("/", "KjkPreferenceCompany@departement_index")->name("crm.pref.company.departement.index");
                    Route::get('/structure', "KjkPreferenceCompany@departement_structure")->name("crm.pref.company.departement.structure");
                    Route::post("/store", "KjkPreferenceCompany@departement_post")->name("crm.pref.company.departement.post");
                });
                Route::prefix("position/{id}")->group(function(){
                    Route::get("/", "KjkPreferenceCompany@position_index")->name("crm.pref.company.position.index");
                    Route::get('/structure', "KjkPreferenceCompany@position_structure")->name("crm.pref.company.position.structure");
                    Route::post("/store", "KjkPreferenceCompany@position_post")->name("crm.pref.company.position.post");
                });
            });
        });

    });

    // Route::prefix
});

Route::prefix("attendance")->name("attendance.")->group(function(){
    Route::get("/report", "KjkAttendance@report_page")->name("report");
    Route::get("/report/add", "KjkAttendance@report_add")->name("report.add");
    Route::get("/report/result/{id}", "KjkAttendance@report_result")->name("report.result");
    Route::get("/report/edit/{id}", "KjkAttendance@report_edit")->name("report.edit");
    Route::get("/report/delete/{id}", "KjkAttendance@report_delete")->name("report.delete");
    Route::post("/report", "KjkAttendance@report_store")->name("report_result");
    Route::get("/map/{id}", "MobileAttendance@map_page")->name("map");
});

Route::prefix("locations")->name("wh.")->group(function(){
    //warehouse
    Route::get('','AssetWarehouseController@index')->name('index');
    Route::get("/view/{id}", "AssetWarehouseController@view_page")->name("view");

    Route::post("/add-user", "AssetWarehouseController@add_user")->name("add_user");
    Route::get("/delete-user/{id?}", "AssetWarehouseController@delete_user")->name("delete_user");
    Route::post('/store','AssetWarehouseController@store')->name('store');
    Route::post('/update','AssetWarehouseController@update')->name('update');
    Route::get('/delete/{id}','AssetWarehouseController@delete')->name('delete');
    Route::get("/rack-view/{id}",'AssetWarehouseController@rack')->name('rack');
    Route::get("/qr/{id?}", "AssetWarehouseController@qr")->name("qr");
    Route::get("/qr-view/{id}", "AssetWarehouseController@qr_view")->name("qr-view");
    Route::post("/upload-featured-image", 'AssetWarehouseController@upload_image')->name("upload");
});

Route::get("faq", "HomeController@faq_page")->name("faq.index");

// Route::get("/test-email", "Mail\MailController@index");

Route::prefix("search-talent")->name("search_talent.")->group(function(){
    Route::get("", "KjkSearchTalent@index")->name("index");
    Route::get("/detail-applicant/{id?}", "KjkSearchTalent@detail")->name("detail");
    Route::get("/bookmark", "KjkSearchTalent@bookmark_page")->name('bookmark_page');

    Route::post("/search", "KjkSearchTalent@search")->name('search');
    Route::post("/bookmark", "KjkSearchTalent@bookmark")->name('bookmark');
});

Route::prefix("report-job")->name("job_report.")->group(function(){
    Route::get("", "KjkJobReport@index")->name("index");
    Route::get("/detail/{id?}", "KjkJobReport@detail")->name("detail");
    Route::get("/detail-applicant/{id?}", "KjkJobReport@detail_applicant")->name("detail_applicant");
    Route::get("/get-collaborator/{id?}", "KjkJobReport@get_collaborator")->name("get_collaborator");
    Route::post("/nonaktfikan", "KjkJobReport@nonaktifkan")->name("nonaktif");
    Route::post("/assign-collaborator", "KjkJobReport@assign_collaborator")->name("collaborator");
    Route::post("/add-to-backlog", "KjkJobReport@backlog")->name("backlog");
    Route::post("/reject-applicants", "KjkJobReport@reject_applicant")->name("reject_applicant");
    Route::get("/view/{id}", "KjkJobReport@preview")->name("view");

    Route::post("/update", "KjkJobReport@update")->name('update');
    Route::post("/bookmark", "KjkJobReport@bookmark")->name('bookmark');
    Route::post("/export", "KjkJobReport@export_excel")->name("export.excel");
});

// emp question
Route::prefix("/test")->group(function(){
    Route::get("/list", "HrdQuestion@index")->name("hrd.test.index");
    Route::get("/activate/{id}", "HrdQuestion@activate")->name("hrd.test.activate");
    Route::get("/questions/{id}", "HrdQuestion@question")->name("hrd.test.question");
    Route::get("/questions-detail/{id}", "HrdQuestion@detail")->name("hrd.test.detail");
    Route::get("/questions-view/{id}", "HrdQuestion@qview")->name("hrd.test.question_view");
    Route::get("/order-change/{type}/{order}/{id}", "HrdQuestion@order_change")->name("hrd.test.order_change");
    Route::get("/delete/{type}/{id}", "HrdQuestion@delete")->name("hrd.test.delete");

    Route::post("/question/add", "HrdQuestion@question_add")->name("hrd.test.question_add");
    Route::post("/add", "HrdQuestion@add")->name("hrd.test.add");
    Route::post("/point/add", "HrdQuestion@point_add")->name("hrd.test.point_add");

    Route::get("/exam/{id}", "HrdQuestion@exam")->name("hrd.test.exam");
    Route::get('/exam-review/{id}', "HrdQuestion@exam_review")->name("hrd.test.review");
    Route::post('/exam-step/{id?}/{step?}', "HrdQuestion@exam_step")->name("hrd.test.exam_step");
    Route::post("/exam/start", "HrdQuestion@exam_start")->name("hrd.text.exam_start");
    Route::post("/exam/result", "HrdQuestion@exam_result")->name("hrd.text.exam_result");

    Route::post('category/add', "HrdQuestion@category_add")->name("hrd.test.category.add");
    Route::post('category/update/{id?}', "HrdQuestion@category_update")->name("hrd.test.category.update");
    Route::get('category/get', "HrdQuestion@category_get")->name("hrd.test.category.get");
    Route::get('category/delete/{id?}', "HrdQuestion@category_delete")->name("hrd.test.category.delete");

    Route::get("/materials/{id}", "HrdQuestion@materials")->name("hrd.test.materials");
    Route::post("/materials/add", "HrdQuestion@materials_add")->name("hrd.test.materials_add");
});

Route::prefix("/employee")->group(function(){

    Route::prefix("attendance")->group(function(){
        Route::get("/", "MobileAttendance@index")->name("emp.mt.index");
        Route::get("/view/{type?}/{id?}", "MobileAttendance@view_attendance")->name("emp.mt.view");


        Route::prefix("report")->group(function(){
            Route::get("/", "MobileAttendance@report_index")->name("emp.mt.report");
            Route::get("/personal", "MobileAttendance@report_personal")->name("emp.mt.report_personal");
        });

        Route::post("/assign_user", "MobileAttendance@assign_user")->name("emp.mt.assign_user");

        Route::post("/", "MobileAttendance@index")->name("emp.mt.index");
    });

    Route::prefix("work-hour")->group(function(){
        Route::get("/", "MobileAttendance@workHourIndex")->name("emp.wh.index");
        Route::post("/save", "MobileAttendance@workHourSave")->name("emp.wh.save");
    });
});

Route::prefix("random-question")->group(function(){
    Route::get('/', "KjkRandomQuestion@index")->name("rq.index");
    Route::get('/answers/{id}', "KjkRandomQuestion@detail")->name("rq.detail");
    Route::post('/add-question', "KjkRandomQuestion@add_question")->name("rq.add_question");
    Route::post('/add-answer', "KjkRandomQuestion@add_answer")->name("rq.add_answer");
});

Route::prefix("/preferences")->group(function(){
    Route::prefix("custom-role")->group(function(){
        Route::get("/privileges/{id}", "KjkCustomRole@privilege")->name("pref.cr.priv");
        Route::get("/user/{id}", "KjkCustomRole@user")->name("pref.cr.user");

        Route::post("/add", "KjkCustomRole@add")->name("pref.cr.add");
        Route::post("/delete", "KjkCustomRole@delete")->name("pref.cr.delete");
        Route::post("/privileges", "KjkCustomRole@update_privilege")->name("pref.cr.update_privilege");
        Route::post("/user-privileges", "KjkCustomRole@user_privilege")->name("pref.cr.user_priv");
    });
});

Route::prefix("/calendar")->group(function(){
    Route::get("/", "KjkCalendarEmployer@index")->name("calendar.index");
    Route::get("/events", "KjkCalendarEmployer@event")->name("calendar.event");
    Route::get("/applicant/{id?}", "KjkCalendarEmployer@applicant")->where("id", '[0-9]+')->name("calendar.applicant");
    Route::get("/applicant/update/{id?}", "KjkCalendarEmployer@applicant_update")->name("calendar.applicant_update");
    Route::get("/reschedule/{id?}", "KjkCalendarEmployer@reschedule")->name("calendar.reschedule");

    Route::post("/add", "KjkCalendarEmployer@add")->name("calendar.add");
    Route::post("/review", "KjkCalendarEmployer@review")->name("calendar.review");
    Route::post("/reschedule", "KjkCalendarEmployer@reschedule_update")->name("calendar.reschedule_update");
});

Route::prefix("/be")->group(function(){
    Route::prefix("employers")->group(function(){
        Route::get("/", "KjkBpController@employers_index")->name("bp.employers.index");
        Route::get("/job-ads/{id}", "KjkBpController@employers_job_ads")->name("bp.employers.job_ads");
        Route::get("/company-profile/{id}", "KjkBpController@employers_company")->name("bp.employers.company");
    });

    Route::prefix("applicants")->group(function(){
        Route::get("/", "KjkBpController@applicants_index")->name("bp.applicants.index");
        Route::get("/change-status/{id}", "KjkBpController@applicants_status")->name("bp.applicants.status");
        Route::get("/detail/{id}", "KjkBpController@applicants_detail")->name("bp.applicants.detail");
    });

    Route::prefix("clients")->group(function(){
        Route::get("/", "KjkBpController@clients_index")->name("bp.clients.index");

        Route::post("/navigate-to", "KjkBpController@clients_navigate")->name("bp.clients.navigate");
    });

    Route::prefix("job-ads")->group(function(){
        Route::get("/", "KjkBpController@job_ads_index")->name("bp.job_ads.index");
        Route::get("/review/{id}", "KjkBpController@job_ads_review")->name("bp.job_ads.review");

        Route::post("/review", "KjkBpController@job_ads_review_post")->name("bp.job_ads.review_post");
    });
});


Route::prefix("cari-perusahaan")->group(function(){
    Route::get("/", "KjkCompanySearch@index")->name("app.cs.index");
    Route::get("/detail/{id}", "KjkCompanySearch@detail")->name("app.cs.detail");
    Route::get("/review/{id}", "KjkCompanySearch@review")->name("app.cs.review");

    Route::get("/get-location", "KjkCompanySearch@getLocation")->name("app.cs.getLocation");

    Route::post("/search", "KjkCompanySearch@search")->name("app.cs.search");
    Route::post("/review", "KjkCompanySearch@review_post")->name("app.cs.review_post");
    Route::post("/list-reviews/{id}", "KjkCompanySearch@review_list")->name("app.cs.review_list");
    Route::post("/list-job-ads/{id}", "KjkCompanySearch@job_ads")->name("app.cs.job_ads");
});


Route::get("/dashboard-chart", "HomeController@dashboardChart")->name("dashboard.chart");

Route::prefix("/hrd/employee")->group(function(){
    Route::get("/{id}/address", "HrdEmployeeController@setAddressPage")->name("emp.address.page");

    Route::post("/{id}/save-coordinates", "HrdEmployeeController@saveCoordinates")->name("emp.coordinates.save");
    Route::post("/{id}/change-password", "HrdEmployeeController@changePassword")->name("emp.user.change_password");
});

Route::prefix("pivot-tables")->group(function(){
    Route::get("/", "KjkPivotTables@index")->name("pivot.index");
    Route::get("/view/{type?}", "KjkPivotTables@view")->name("pivot.view");
});

Route::prefix("ess")->group(function(){

    Route::get("/", "ESSDashboard@index")->name("ess.index");
    Route::post("/attend", "ESSDashboard@attend")->name("ess.att");

    Route::prefix("profile")->group(function(){
        Route::get("/", "ESSProfile@index")->name("ess.profile.index");

        Route::post("/update-private-data", "ESSProfile@update_private_data")->name("ess.profile.update_private_data");
    });

    Route::prefix("overtime")->group(function(){
        Route::get("/", "ESSOvertime@index")->name("ess.overtime.index");
        Route::get("/detail/{id?}", "ESSOvertime@detail")->name("ess.overtime.detail");
        Route::get("/delete/{id}", "ESSOvertime@delete")->name("ess.overtime.delete");
    });

    Route::prefix("team")->group(function(){
        Route::get("/", "ESSTeam@index")->name("ess.team.index");
        Route::get("/detail/{id}", "ESSTeam@detail")->name("ess.team.detail");
    });

    Route::prefix("attendance")->group(function(){
        Route::get("/", "ESSAttendance@index")->name("ess.attendance.index");
        Route::get("/delete/{id}", "ESSAttendance@delete")->name("ess.attendance.delete");

        Route::post("/request", "ESSAttendance@add")->name("ess.attendance.add");
    });

    Route::prefix("approval")->group(function(){
        Route::get("/", "ESSApproval@index")->name("ess.approval.index");

        Route::post("/post/{type?}", "ESSApproval@approve")->name("ess.approval.approve");
    });

    Route::prefix("benefit")->group(function(){
        Route::get("/", "ESSBenefit@index")->name("ess.benefit.index");

        Route::get("/print/{type}/{id}", "ESSBenefit@print")->name("ess.benefit.print");
    });

    Route::prefix("leave")->group(function(){
        Route::get("/", "ESSLeave@index")->name("ess.leave.index");
    });
    Route::prefix("loan")->group(function(){
        Route::get("/", "ESSLoan@index")->name("ess.loan.index");
        Route::get("/delete/{id}", "ESSLoan@delete")->name("ess.loan.delete");

        Route::post("/request", "ESSLoan@add")->name("ess.loan.add");
    });
    Route::prefix("cash-advance")->group(function(){
        Route::get("/", "ESSCash@index")->name("ess.cash-advance.index");
        Route::get("/delete/{id}", "ESSCash@delete")->name("ess.cash-advance.delete");

        Route::post("/request", "ESSCash@add")->name("ess.cash-advance.add");
    });
    Route::prefix("employment-letter")->group(function(){
        Route::get("/", "ESSLetter@index")->name("ess.employment-letter.index");
        Route::get("/delete/{id}", "ESSLetter@delete")->name("ess.employment-letter.delete");

        Route::post("/request", "ESSLetter@add")->name("ess.employment-letter.add");

    });
});
