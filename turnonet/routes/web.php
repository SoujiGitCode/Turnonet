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

//-- uploads images --//
Route::post('upload-image', 'UploadController@postUpload');
Route::post('upload-thumb','UploadController@upload_thumb');
Route::post('upload-docs','UploadController@upload_docs');
//--login cms--//
Route::resource('cms', 'LoginController');
//--logout cms--//
Route::get('logout', 'LoginController@logout');
//--logout cms--//
Route::post('mantenaice', 'DashboardController@mantenaice');
//--recover password--//
Route::get('recover-password', 'LoginController@recovery');
// store recovery //
Route::post('store_recovery', 'LoginController@store_recovery');
//--dashboard cms---//
Route::resource('dashboard', 'DashboardController');
//-- account ---//
Route::get('my-account', 'DashboardController@account');
//---users cms ----//
Route::resource('user', 'UserController');
// --- users lists--//
Route::get('user_lists', 'UserController@lists');
//---roles ----//
Route::resource('rol', 'RolController');
// -- roles lists--//
Route::get('rol_lists', 'RolController@lists');
//-- permissions --//
Route::get('permission/{id}', 'RolController@permission');
// -- list permissions --//
Route::get('permission_lists/{id}', 'RolController@permission_lists');
// --assing permission --//
Route::post('assign', 'RolController@assign');
// --remove permission --//
Route::post('remove', 'RolController@remove');
//--Analytics---//
Route::get('analytics', 'DashboardController@analytics');
Route::get('analytics-date/{init}/{end}','DashboardController@analytics_date');
Route::get('views_pages_listing', 'DashboardController@pages_listing');
Route::get('views_pages_listing_date/{init}/{end}', 'DashboardController@pages_listing_date');

// ----banners----//
Route::resource('banners', 'BannerController');
// --banner list--//
Route::get('banners_lists', 'BannerController@lists');
// -- list photo banner --//
Route::post('lists-photo-banner', 'BannerController@lists_photo');
// --delete photo banner --//
Route::post('delete-photo-banner', 'BannerController@delete_photo');

//---move post---//
Route::post('move-post', 'PostController@move_post');
// ----menu ----//
Route::resource('menu', 'MenuController');
// --menu lists--//
Route::get('menu_lists', 'MenuController@lists');
// --move menu-- //
Route::post('move-menu', 'MenuController@move_menu');

// ----faq----//
Route::resource('faq', 'FaqController');
// --faq list--//
Route::get('faq_lists', 'FaqController@lists');
//---move faq ---//
Route::post('move-faq', 'FaqController@move_faq');
// -- list photo section --//
Route::post('lists-photo-faq', 'FaqController@lists_photo');
// --delete photo section --//
Route::post('delete-photo-faq', 'FaqController@delete_photo');

// ----sections----//
Route::resource('section', 'SectionController');
// --section list--//
Route::get('sections_lists', 'SectionController@lists');
// -- list photo section --//
Route::post('lists-photo-section', 'SectionController@lists_photo');
// --delete photo section --//
Route::post('delete-photo-section', 'SectionController@delete_photo');
//---move section---//
Route::post('move-section', 'SectionController@move_section');
// ----widgets----//
Route::resource('widget', 'WidgetController');
// --widget list--//
Route::get('widgets_lists', 'WidgetController@lists');
// -- list photo widget --//
Route::post('lists-photo-widget', 'WidgetController@lists_photo');
// --delete photo widget --//
Route::post('delete-photo-widget', 'WidgetController@delete_photo');
//---move widget---//
Route::post('move-widget', 'WidgetController@move_widget');

// ----comunications----//
Route::resource('communication', 'CommunicationsController');
// --comunication list--//
Route::get('communications_lists', 'CommunicationsController@lists');
// -- list photo comunication --//
Route::post('lists-photo-communication', 'CommunicationsController@lists_photo');
// --delete photo comunication --//
Route::post('delete-photo-communication', 'CommunicationsController@delete_photo');

Route::post('reenviar', 'CommunicationsController@reenviar');

//----site options----//
Route::get('options', 'OptionsController@index')->middleware('role:5');
Route::get('seo', 'OptionsController@seo')->middleware('role:6');
//----marketplace----//
Route::get('marketplace', 'OptionsController@marketplace')->middleware('role:11');
//---update options --//
Route::post('update_options', 'OptionsController@update');


//---audits--//
Route::resource('audits','AuditsController');
Route::get('audits_listing','AuditsController@listing');
Route::get('audits_search_listing/{type}/{init}/{end}','AuditsController@search_listing');


//---comments ----//
Route::resource('comments', 'CommentsController');
Route::get('comments_lists', 'CommentsController@listing');
Route::get('list_comment/{id}','CommentsController@list_comment');
Route::post('delete_comments','CommentsController@delete_alls');

//---users cms ----//
Route::resource('users-app', 'UserAppController');
// --- users lists--//
Route::get('user_app_lists', 'UserAppController@lists');
// --update status-- //
Route::post('up-status-user', 'UserAppController@up_status');
// --- users lists--//
Route::get('users-app/{id}/open', 'UserAppController@open_account');
// --- activities--//
Route::get('users-app/{id}/activities', 'UserAppController@activities');

Route::get('activities_listing/{type}/{init}/{end}','ActivitiesController@listing');

//---business cms ----//
Route::resource('business', 'CmsBusinessController');
Route::get('reports-business', 'CmsBusinessController@reports');
Route::get('payments-reports', 'CmsBusinessController@payments_reports');
Route::get('reports-account/{id}', 'CmsBusinessController@accounts');
// --- business lists--//
Route::get('business_lists/{status}', 'CmsBusinessController@lists');

Route::get('business_lists_users/{id}', 'CmsBusinessController@lists_users');

Route::get('business_insert_user/{id}', 'CmsBusinessController@insert_users');

// --- business lists--//
Route::get('business_lists_search/{pay}/{status}', 'CmsBusinessController@lists_search');

Route::get('business_lists_payments', 'CmsBusinessController@lists_payments');


Route::get('business_lists_payments_search/{init}/{end}/{business}','CmsBusinessController@lists_payments_search');

// --update status-- //
Route::post('up-status-business', 'CmsBusinessController@up_status');
// --update status pay-- //
Route::post('up-pay-business', 'CmsBusinessController@up_pay');
// --update status sms-- //
Route::post('up-sms-business', 'CmsBusinessController@up_sms');
// --update status sms res-- //
Route::post('up-sms-res-business', 'CmsBusinessController@up_sms_res');
// --update status sms res-- //
Route::post('up-video-business', 'CmsBusinessController@update_video');
//---lenders ----//
Route::resource('lenders', 'CmsLendersController');
// --- lenders lists--//
Route::get('lenders_lists', 'CmsLendersController@lists');
// --update status-- //
Route::post('up-status-lender', 'CmsLendersController@up_status');

// --customers---//
Route::resource('clients', 'CustomersController');
// --customers list-//
Route::get('clients_lists', 'CustomersController@lists');

Route::post('move-clients', 'CustomersController@move_customer');



//---customers ----//
Route::resource('customers', 'CmsCustomersController');

Route::get('inactive-customers', 'CmsCustomersController@inactive');
// --- customers lists--//
Route::get('customers_lists', 'CmsCustomersController@lists');
// --update status-- //
Route::post('up-status-customer', 'CmsCustomersController@up_status');



// ----servicess----//
Route::resource('services', 'ServicesWidgetController');
// --services list--//
Route::get('services_lists', 'ServicesWidgetController@lists');
// -- list photo services --//
Route::post('lists-photo-services', 'ServicesWidgetController@lists_photo');
// --delete photo services --//
Route::post('delete-photo-services', 'ServicesWidgetController@delete_photo');
//---move services---//
Route::post('move-services', 'ServicesWidgetController@move_services');


// ----planss----//
Route::resource('plans', 'PlansController');
// --plans list--//
Route::get('plans_lists', 'PlansController@lists');
// -- list photo plans --//
Route::post('lists-photo-plans', 'PlansController@lists_photo');
// --delete photo plans --//
Route::post('delete-photo-plans', 'PlansController@delete_photo');
//---move plans---//
Route::post('move-plans', 'PlansController@move_plans');


// --galleries---//
Route::resource('videos', 'GalleriesController');
// --galleries list-//
Route::get('videos_lists', 'GalleriesController@lists');




/************************ EXCEL ********************/
Route::get('excel/users/{ini}/{end}','CmsExcelController@users');
Route::get('excel/lenders','CmsExcelController@lenders');
Route::get('excel/inactive-customers','CmsExcelController@inactive');
Route::get('excel/business/{pay}/{status}','CmsExcelController@business');
Route::get('excel/administrators/{ini}/{end}','CmsExcelController@administrators');
Route::get('excel/messages/{ini}/{end}','CmsExcelController@messages');
Route::get('excel/audits/{user}/{ini}/{end}','CmsExcelController@activities');
Route::get('excel/activities/{user}/{ini}/{end}','CmsExcelController@activities_user');
Route::get('excel/communications/{ini}/{end}','CmsExcelController@communications');

Route::get('excel/commissions-date/{ini}/{end}/{business}','CmsExcelController@commissions_date');
Route::get('excel/commissions','CmsExcelController@commissions');

Route::get('pdf/business/{id}/{ini}/{end}','CmsExcelController@pdf_reports');


/************** LOGIN ***********/
Route::get('/','FrontendController@index');
Route::post("/",'FrontendController@index_2');
//Route::get("e/es/sol/{item1}/{item2}/{item3}/{item4}/{item5}/{item6}/{item7}",'FrontendController@index_3');
Route::get('iniciar-sesion','FrontendController@sigin');
Route::get('enviar-codigo','FrontendController@send_code');
Route::get('cambiar-clave','FrontendController@reset_password');
Route::get('activar-cuenta/{id}','FrontendController@active_account');
Route::post('store_contacto','FrontendController@store_message');
Route::post('store_registro','FrontendController@store_register');

Route::post('store_signin', 'FrontendController@store_signin');
Route::post('store_code', 'FrontendController@store_code');
Route::post('store_reset', 'FrontendController@store_reset');


/************** AGENDA ***********/

Route::post('set_business','AccountController@set_business');
Route::get('agenda','DiaryController@index');
Route::get('agenda/empresa/{id}','DiaryController@business');
Route::get('agenda/empresa/diarios/{id}','DiaryController@business_registers');
Route::get('agenda/cliente/{id}/{user}','DiaryController@business_user');
Route::get('lista_servicios_prestador/{id}','DiaryController@lista_servicios_prestador');



Route::get('agenda/empresa/cancelados/{id}','DiaryController@cancels');
Route::get('agenda/reasignar/{id}','DiaryController@reasing');
Route::get('agenda/nuevo/{id}','DiaryController@news');
Route::get('agenda/cancelar/{id}','DiaryController@cancelar');
Route::get('agenda/prestadores/{id}','DiaryController@lenders');
Route::get('agenda/disponibilidad/{id}','DiaryController@create');
Route::post('update_status_shift','DiaryController@up_status');
Route::post('update_status_all_shift','DiaryController@up_status_all');


Route::post('update_asis_shift','DiaryController@up_asistencia');
Route::get('list_services/{id}','DiaryController@lists_services');
Route::get('nuevo-turno','DiaryController@create');
Route::get('agenda/turno/{id}','DiaryController@view_shitf');
Route::get('agenda/editar/turno/{id}','DiaryController@edit_shitf');
Route::get('lists_diary/{date}/{status}/{type}/{lender}/{branch}/{id}','DiaryController@lists');
Route::get('lists_diary_register/{date}/{status}/{type}/{lender}/{branch}/{id}','DiaryController@lists_register');



Route::get('lists_diary_user/{user}/{date}/{status}/{type}/{lender}/{branch}/{id}','DiaryController@lists_user');



Route::post('delete_shedules_cancel','DiaryController@delete_shedules_cancel');




/************** DIRECTORIO ***********/


Route::get('directorio/empresa/{id}','DirectoryController@index');
Route::get('directorio/nuevo/{id}','DirectoryController@create');
Route::get('directorio/{id}/editar','DirectoryController@edit');
Route::get('lists_directory/{id}/{status}','DirectoryController@lists');
Route::get('lists_directory_search/{search}/{id}/{status}','DirectoryController@lists_search');
Route::get('get_lists_lenders','DirectoryController@lists_lenders');
Route::post('alta_user','DirectoryController@alta_user');
Route::post('up_status_directory','DirectoryController@up_status_directory');


Route::get('get_user_data/{id}/{code}','DirectoryController@user_data');
Route::get('get_user_last_data/{id}','DirectoryController@user_last_data');
Route::post('delete_user','DirectoryController@delete_user');
Route::post('store_update_cliente','DirectoryController@update');
Route::post('store_update_cliente_turno','DirectoryController@update_turno');



Route::post('store_create_cliente','DirectoryController@store');


/************** CUENTA ***********/

Route::get('escritorio','AccountController@index');
Route::get('ajustes','AccountController@account');
Route::get('actualizar-cuenta','AccountController@upaccount');
Route::get('actualizar-clave','AccountController@uppassword');
Route::get('mi-perfil','AccountController@profile');
Route::post('store_update','AccountController@store_update');
Route::post('store_update_password','AccountController@store_update_password');

Route::get('cerrar-sesion','AccountController@logout');
Route::get('turnonet-v3','AccountController@turnonet_v3');

Route::post('delete_noty_type','AccountController@delete_noty_type');
Route::post('delete_noty','AccountController@delete_noty');
Route::post('delete_noty_active','AccountController@delete_noty_active');
Route::get('terminos-y-condiciones','AccountController@terminos');
Route::get('politicas-de-privacidad','AccountController@politicas');



/************** SOPORTE ***********/

Route::get('soporte','SupportController@index');
Route::post('store_support', 'SupportController@store_support');
Route::post('store_shift','ShitfController@store');
Route::post('reasing_shift','ShitfController@reasing');

/************** EMPRESAS ***********/


Route::get('empresas','BusinessController@index');
Route::get('empresa/nueva','BusinessController@create');
Route::get('empresa/nueva/horarios/{id}','BusinessController@create_shedules');
Route::get('empresa/nueva/sucursal/{id}','BusinessController@create_branch');
Route::get('empresa/editar/{id}','BusinessController@edit');
Route::get('empresa/estadisticas/{id}','BusinessController@statistics');
Route::get('empresa/estadisticas/filtrar/{id}/{init}/{end}','BusinessController@statistics_date');
Route::get('empresa/frame/{id}','BusinessController@frame');
Route::get('empresa/lista-negra/{id}','BusinessController@blacklist');
Route::get('empresa/frame/theme/{id}','BusinessController@frame_theme');

Route::get('empresa/baja/{id}','BusinessController@baja');
Route::get('empresa/administradores/{id}','BusinessController@admins');
Route::get('empresa/notificaciones/{id}','BusinessController@notifications');
Route::get('empresa/frame/especialidad/{id}','BusinessController@frame_speciality');
Route::get('empresa/configuracion/{id}','BusinessController@settings');


Route::get('empresa/mercado-pago/{id}','BusinessController@mercado_pago');
Route::get('asociate-mercado-pago/{id}','BusinessController@create_mercado_pago');


Route::post('renew-mercado-pago','BusinessController@renew_mercado_pago');




Route::get('empresa/reportes/{id}','BusinessController@reports');
Route::get('empresa/generar-reportes/{id}','BusinessController@send_reports');
Route::get('empresa/obras-sociales/{id}','BusinessController@social_work');
Route::get('empresa/datos-requeridos/{id}','BusinessController@required_fields');
Route::get('empresa/dias-no-laborables/{id}','BusinessController@not_working');
Route::get('empresa/horarios/{id}','BusinessController@shedules');
Route::get('subcategories/{id}','BusinessController@subcategories');
Route::get('lists_bussiness','BusinessController@lists');
Route::post('update_notifications_business','BusinessController@update_notifications');
Route::post('update_status_business','BusinessController@update_status');
Route::post('update_settings_business','BusinessController@update_settings');
Route::post('update_frame','BusinessController@update_theme');

Route::post('update_reports_business','BusinessController@update_reports');
Route::post('update_works_business','BusinessController@update_works');
Route::post('update_business','BusinessController@update_business');
Route::post('create_business','BusinessController@create_business');
Route::post('update_shedules_business','BusinessController@update_shedules');
Route::post('update_required_business','BusinessController@update_required');




/************************ EXCEL ********************/
Route::get('excel/turnos/{ini}/{end}/{status}/{type}/{lender}/{branch}/{id}','ExcelController@shift');
Route::get('excel/turnos/diarios/{ini}/{end}/{status}/{type}/{lender}/{branch}/{id}','ExcelController@shift_register');
Route::get('excel/turnos/sodimac/diarios/{ini}/{end}/{status}/{type}/{lender}/{branch}/{id}','ExcelController@shift_sodimac_register');
Route::get('excel/turnos/sodimac/{ini}/{end}/{status}/{type}','ExcelController@shift_sodimac');




Route::get('excel/turnos/cliente/{user}/{ini}/{end}/{status}/{type}/{lender}/{branch}/{id}','ExcelController@shift_user');
Route::get('excel/clientes/{id}','ExcelController@customers');
Route::get('excel/pagos/{init}/{end}/{id}','ExcelController@payments');


/************************ PDF ********************/
Route::get('pdf/cliente/{business}/{code}/{id}','PdfController@directory');
Route::get('pdf/turno/{code}','PdfController@shift');
Route::get('pdf/estadisticas/{id}/{init}/{end}','PdfController@statistics');
Route::get('pdf/reporte/{category}/{type}/{id_sq}/{business}/{date}','PdfController@report');


/********************Holidays**********************/
Route::resource('holidays', 'HolidaysController');
Route::post('store_branch','HolidaysController@store_branch');
Route::post('store_lender','HolidaysController@store_lender');

Route::get('lists_holidays/{id}/{status}', 'HolidaysController@lists');
Route::get('lists_holidays_branch/{id}/{status}', 'HolidaysController@lists_branch');
Route::get('lists_holidays_lender/{id}/{status}', 'HolidaysController@lists_lender');
Route::post('delete_holiday','HolidaysController@remove');
Route::post('delete_holiday_branch','HolidaysController@remove_branch');
Route::post('delete_holiday_lender','HolidaysController@remove_lender');
Route::post('alta_date','HolidaysController@alta_date');
Route::post('alta_date_branch','HolidaysController@alta_date_branch');
Route::post('alta_date_lender','HolidaysController@alta_date_lender');
Route::post('up_status_working_business','HolidaysController@up_status_working_business');
Route::post('up_status_working_branch','HolidaysController@up_status_working_branch');
Route::post('up_status_working_lender','HolidaysController@up_status_working_lender');



/*********************MERCADO PAGO**********************/

Route::post('remove_mercado_pago','MercadoPagoController@remove');
Route::get('empresa/associate/{id}','MercadoPagoController@associate');
Route::get('lists_payments/{id}','MercadoPagoController@lists');
Route::get('lists_payments_date/{init}/{end}/{id}','MercadoPagoController@lists_search');



/********************Speciality**********************/
Route::resource('speciality', 'SpecialityController');
Route::get('lists_speciality/{id}/{status}', 'SpecialityController@lists');
Route::post('alta_speciality','SpecialityController@alta_speciality');
Route::post('up_status_speciality','SpecialityController@status_speciality');


/********************Services**********************/
Route::resource('service', 'ServiceController');
Route::get('lists_service/{id}/{status}', 'ServiceController@lists');
Route::post('alta_service','ServiceController@alta_service');

/********************Notes**********************/
Route::resource('notes', 'NotesController');
Route::get('lists_notes/{id}/{status}', 'NotesController@lists');
Route::get('lists_notes_customer/{id}/{status}/{customer}', 'NotesController@lists_customer');
Route::post('alta_notes','NotesController@alta_notes');
Route::post('up_status_notes','NotesController@status_notes');


/********************blacklist**********************/
Route::resource('blacklists', 'BlackListsController');
Route::get('lists_blacklists/{id}/{status}', 'BlackListsController@lists');
Route::post('alta_blacklists','BlackListsController@alta_blacklist');
Route::post('up_status_blacklists','BlackListsController@status_blacklist');


/********************Admins**********************/
Route::resource('admins', 'AdminsController');
Route::get('lists_admins/{id}/{status}', 'AdminsController@lists');
Route::post('alta_admin','AdminsController@alta_admin');
Route::post('up_status_admin','AdminsController@up_status_admin');


/************************LENDERS******************************/



Route::get('prestador/nuevo/{id}','LendersController@create');
Route::get('prestadores','LendersController@index');
Route::get('prestador/editar/{id}','LendersController@edit');
Route::get('prestador/baja/{id}','LendersController@baja');
Route::get('prestador/configuracion/{id}','LendersController@settings');
Route::get('prestador/horarios/{id}','LendersController@shedules');
Route::get('prestador/dias-no-laborables/{id}','LendersController@not_working');
Route::get('prestador/notificaciones/{id}','LendersController@notifications');
Route::get('prestador/obras-sociales/{id}','LendersController@social_work');
Route::get('prestador/servicios/{id}','LendersController@services');
Route::get('prestador/especialidades/{id}','LendersController@speciality');
Route::get('prestador/observaciones/{id}','LendersController@notes');
Route::get('prestador/generar-reportes/{id}','LendersController@send_reports');
Route::post('alta_lender','LendersController@alta_lender');





Route::get('lista_branch/{id}','LendersController@lista_branch');



Route::post('update_notifications_lender','LendersController@update_notifications');
Route::post('update_status_lender','LendersController@update_status');
Route::post('update_settings_lender','LendersController@update_settings');
Route::post('update_works_lender','LendersController@update_works');
Route::post('update_lender','LendersController@update_lender');
Route::post('create_lender','LendersController@create_lender');
Route::post('update_shedules_lender','LendersController@update_shedules');
Route::get('lists_lenders_business/{id}','LendersController@lists_business');
Route::get('lists_lenders_branch/{id}/{type}','LendersController@lists');
Route::get('agenda/prestador/{id}','LendersController@diary');





/********************BRANCH**************************/
Route::get('sucursales','BranchController@index');
Route::get('sucursal/nueva/{id}','BranchController@create');
Route::get('sucursal/editar/{id}','BranchController@edit');
Route::get('sucursal/baja/{id}','BranchController@baja');
Route::get('sucursal/configuracion/{id}','BranchController@settings');
Route::get('sucursal/horarios/{id}','BranchController@shedules');
Route::get('sucursal/dias-no-laborables/{id}','BranchController@not_working');
Route::get('sucursal/notificaciones/{id}','BranchController@notifications');
Route::get('sucursal/prestadores/{id}','BranchController@lenders');
Route::get('sucursal/generar-reportes/{id}','BranchController@send_reports');
Route::get('agenda/sucursal/{id}','BranchController@diary');
Route::get('lists_branch/{id}/{type}','BranchController@lists');
Route::post('alta_branch','BranchController@alta_branch');

Route::get('lists_branch_business/{id}','BranchController@lists_business');

Route::post('update_notifications_branch','BranchController@update_notifications');
Route::post('update_status_branch','BranchController@update_status');
Route::post('update_settings_branch','BranchController@update_settings');
Route::post('update_works_branch','BranchController@update_works');
Route::post('update_branch','BranchController@update_branch');
Route::post('create_branch','BranchController@create_branch');
Route::post('update_shedules_branch','BranchController@update_shedules');




/***************LOG ERRORS*******************/
Route::get('logging', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

foreach(DB::table('communications')->get() as $rs):
    if($rs->url!=""):
        Route::get($rs->url, 'AccountController@view_communication');
    endif;
endforeach;


Route::get('open-account/{id}', 'FrontendController@open_account');


/***************CRONJOB*******************/

Route::get('limpiar-turnos','CronJobController@status_turnos');
Route::get('limpiar-auditoria','CronJobController@delete_audits');
Route::get('limpiar-cache','CronJobController@clear_cache');
Route::get('reportes-diarios/{hour}','CronJobController@customers_reports');
Route::get('recordatorio-1-dia','CronJobController@reminders_oneday');
Route::get('recordatorio-5-dias','CronJobController@reminders_fivedays');

Route::get('resetTurnos/{id}','CronJobController@resetTurnos');


Route::get('actualizar-empresas/{id}','CronJobController@update_business');

Route::get('actualizar-turnos-de-empresas/{id}','CronJobController@update_shift_business');


Route::get('recordatorio-sms-1-dia','CronJobController@reminders_oneday_sms');
Route::get('recordatorio-sms-2-dias','CronJobController@reminders_twoday_sms');
Route::get('recordatorio-sms-5-dias','CronJobController@reminders_fiveday_sms');



Route::get('enviar-sms','CronJobController@send_sms');
Route::get('enviar-sms-id/{id}','CronJobController@send_sms_id');
Route::get('crear-sms-id/{id}','CronJobController@create_sms_id');



Route::get('enviar-turno/{id}/{email}/{name}','CronJobController@shift');
Route::get('enviar-email','CronJobController@send_mails');
Route::post('send_reports','CronJobController@send_reports');
Route::post('send_reports_branch','CronJobController@send_reports_branch');
Route::post('send_reports_business','CronJobController@send_reports_business');




Route::post('verify_reports','CronJobController@verify_reports');
Route::post('verify_reports_branch','CronJobController@verify_reports_branch');
Route::post('verify_reports_business','CronJobController@verify_reports_business');
Route::get('set_commisions_business','CronJobController@update_commisions_business');




Route::get('get-email','FrontendController@email');
Route::get('reporte/{category}/{type}/{id_sq}/{business}/{date}/{content}','FrontendController@report_email');
Route::get('calendario/{month}/{year}/{business}/{branch}/{lender}/{services}/{overturn}','CalendarController@index');
Route::get('times/{date}/{business}/{branch}/{lender}/{services}/{overturn}','CalendarController@times');


Route::get('e/esn/{id}/{code}','FrameController@index');
Route::get('e/{id}','FrameController@index_e');
Route::get('e/esn/esp/{id}/{speciality}','FrameController@speciality');
Route::get('e/es/{id}/{code}','FrameController@index');
Route::get('e/es/esp/{id}/{speciality}','FrameController@speciality');
Route::get('e/esn/{id}/{branch}/{lender}/{code}','FrameController@calendario');
Route::get('e/es/{id}/{branch}/{lender}/{code}','FrameController@calendario');

//reinaldo
Route::get('validatemail','FrameController@validateMailExistance');


Route::get('cancelar/{id}/{user}/{branch}/{lender}/{business}','FrameController@cancel');

Route::post('confirmar_turno','FrameController@store_shift');
Route::post('actualizar_turno','FrameController@update_shift');
Route::post('cancelar_turno','FrameController@cancel_shift');


Route::get('{id}/response','CommentsController@edit');


Route::get('lista_prestadores/{id}/{speciality}/{branch}','FrameController@lists_business');

Route::get('api_diary/{date}/{status}/{type}/{business}/{branch}/{lender}','ApiDiaryController@lists');
Route::get('api_diary_register/{date}/{status}/{type}/{lender}/{branch}/{id}','ApiDiaryController@lists_register');


foreach(DB::table('frame')->get() as $rs):
    if($rs->url!=""):
        Route::get($rs->url, 'FrameController@view_alias');
    endif;
endforeach;
Route::get('{business}/{lender}/confirmar/{shift}','FrameController@confirm');
Route::get('{business}/{lender}/turno/{shift}','FrameController@shift');
Route::get('{business}/disponibilidad/{lender}','FrameController@calendar');
Route::get('{business}/especialidad/{lender}','FrameController@view_speciality');
Route::get('{business}/{lender}/cancelar/{shift}','FrameController@view_cancel');

//---move post---//
Route::post('move-lender', 'LendersController@move_lender');

Route::get('set-bloqueo', 'CalendarController@setBloqueo');


Route::get('setY', 'UserAppController@setY');
Route::get('setT','CalendarController@setT');

Route::get('callbackmp','FrontendController@callbackmp');

//get states
Route::get('states/{id}','AccountController@getStates');
//get cities
Route::get('cities/{id}','AccountController@getCities');


Route::get('cancelar-sms','SmsController@index');



Route::get('associate-zoom/{id}','ZoomController@index');
Route::post('remove_zoom','ZoomController@remove');
Route::get('redirect-zoom','ZoomController@redirect');
Route::get('get-user','ZoomController@getUser');



Route::get('endpoint','ZoomController@endpoint');

Route::get('ver-horas','FrameController@ver_horas');

Route::get('actualizar_key_mp','CronJobController@actualizar_key_mp');