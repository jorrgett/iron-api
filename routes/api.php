<?php

use App\Helpers\FirebaseMessaging;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppParametersController;
use App\Http\Controllers\AppWarningController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OdometerController;
use App\Http\Controllers\OilChangeHistoryController;
use App\Http\Controllers\PrivacyTermsConditionsController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceAligmentController;
use App\Http\Controllers\ServiceBalancingController;
use App\Http\Controllers\ServiceBatteryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\ServiceOilController;
use App\Http\Controllers\ServiceOperatorController;
use App\Http\Controllers\ServiceTiresController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SyncOdooController;
use App\Http\Controllers\TireBrandController;
use App\Http\Controllers\TireModelController;
use App\Http\Controllers\TireOemDepthController;
use App\Http\Controllers\TireSizeController;
use App\Http\Controllers\TireStandarController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\VehicleBrandController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\VehicleModelPhotoController;
use App\Http\Controllers\VehicleSummaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


# Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'forget']);
Route::post('register', [AuthController::class, 'register']);
Route::post('reset-password', [AuthController::class, 'setUpdatePassword']);


# Public Routes
Route::get('/utils/iron', function () {return response()->json('OK');});
Route::get('privacy_terms_conditions/filter', [PrivacyTermsConditionsController::class, 'getLastActiveByType']);
Route::get('applications/latest-versions', [ApplicationController::class, 'getLatestEnabledVersions']);
Route::get('applications/available-versions/{platform}', [ApplicationController::class, 'getAvailableVersions']);

Route::post('/webhook/contact', [ContactController::class, 'upsert']);

# Sync Odoo
Route::group(['middleware' => 'external'], function () {
    Route::get('/sync_odoo', [SyncOdooController::class, 'sync']);
});

# Private Routes
Route::group(['middleware' => 'jwt.auth'], function () {

    # Api Resources
    Route::apiResource('app_parameters', AppParametersController::class);
    Route::apiResource('applications', ApplicationController::class)->except('show');
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('odometers', OdometerController::class)->only('index');
    Route::apiResource('oil_history', OilChangeHistoryController::class)->only('index');
    Route::apiResource('privacy_terms_conditions', PrivacyTermsConditionsController::class);
    Route::apiResource('product_categories', ProductCategoryController::class)->only('index', 'update');
    Route::apiResource('products', ProductController::class)->only('index');
    Route::apiResource('service_aligments', ServiceAligmentController::class)->only('index');
    Route::apiResource('service_balancing', ServiceBalancingController::class)->only('index');
    Route::apiResource('service_batteries', ServiceBatteryController::class)->only('index');
    Route::apiResource('service_items', ServiceItemController::class)->only('index');
    Route::apiResource('service_operators', ServiceOperatorController::class)->only('index');
    Route::apiResource('service_oils', ServiceOilController::class)->only('index');
    Route::apiResource('services', ServiceController::class)->only('index', 'update');
    Route::apiResource('tire_oem_depths', TireOemDepthController::class);
    Route::apiResource('tire_otd_standars', TireStandarController::class)->except('show');
    Route::apiResource('tire_sizes', TireSizeController::class)->only('index');


    # App Warning
    Route::apiResource('app_warnings', AppWarningController::class);
    Route::get('warning_autohealing_tires', [AppWarningController::class, 'autoHealingTires']);
    Route::get('app_warning_resume', [AppWarningController::class, 'appWarningSummary']);


    # Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::post('generate', [AuthController::class, 'verifyCredentials']);
    Route::post('verify', [AuthController::class, 'confirmCredential']);


    # Back Office Routes
    Route::get('balancing_by_vehicle', [DashboardController::class, 'serviceBalancingComplete']);
    Route::get('battery_by_vehicle', [DashboardController::class, 'serviceBatteryComplete']);
    Route::get('battery_summary_status', [DashboardController::class, 'countBatterySummaryStatus']);
    Route::get('dashboard_stats', [DashboardController::class, 'getDashboard']);
    Route::get('detail_user_activities', [DashboardController::class, 'getUserActivities']);
    Route::get('search_services', [DashboardController::class, 'searchServices']);
    Route::get('service_balancing', [DashboardController::class, 'getServiceBalancingStatus']);
    Route::get('service_inspections', [DashboardController::class, 'serviceInspections']);
    Route::get('service_oil', [DashboardController::class, 'getServiceOilChangeStatus']);
    Route::get('services_by_user', [DashboardController::class, 'serviceByUser']);
    Route::get('tire_histories', [DashboardController::class, 'GetTireHistories']);
    Route::get('tire_summary_physical_state/{state}', [DashboardController::class, 'getUsersByTiresSummaryPhysicalState']);
    Route::get('tires_by_vehicle', [DashboardController::class, 'serviceTireComplete']);
    Route::get('tires_lifespan', [DashboardController::class, 'allUsersTiresLifespandConsumedStatus']);
    Route::get('tires_lifespan_status/{status}', [DashboardController::class, 'getUsersByTiresLifespanConsumedStatus']);
    Route::get('tires_require_change', [DashboardController::class, 'AllUsersTiresRequireChange']);
    Route::get('user_activities', [DashboardController::class, 'getUsersActivityStatus']);
    Route::get('user_batteries/{status}', [DashboardController::class, 'getUsersBatteriesSummary']);
    Route::get('user_batteries_physical_state', [DashboardController::class, 'usersBatteryPhysicalState']);
    Route::get('user_batteries_state/{status}', [DashboardController::class, 'getUserBatteriesState']);
    Route::get('user_batteries_status/{status}', [DashboardController::class, 'getUsersBatteryStatus']);
    Route::get('user_oilchange_status/{status}', [DashboardController::class, 'getUsersByOilChangeStatus']);
    Route::get('users_tires_require_change/{status}', [DashboardController::class, 'getUsersByTiresRequireChange']);
    Route::get('vehicles_by_user/{user_id}', [DashboardController::class, 'vehicleByUser']);


    # Deprecated Routes
    Route::get('get_oil_chart', [DashboardController::class, 'viewOilChart']);
    Route::get('get_tire_chart/{vehicle_id}', [DashboardController::class, 'viewTireChart']);
    Route::get('tire_physical_state', [DashboardController::class, 'countTireSummaryPhysicalState']);
    Route::get('service_details', [DashboardController::class, 'getServiceDetails']);
    Route::get('service_balancing_status/{status}', [DashboardController::class, 'getUsersByServiceBalancingStatus']);
    Route::get('dashboard_tire_balancing/{vehicle_id}', [DashboardController::class, 'getTireBalancing']);
    Route::get('dashboard_battery/{vehicle_id}', [DashboardController::class, 'getStatsBattery']);


    # Notifications
    Route::apiResource('notifications', NotificationController::class);
    Route::post('push_notifications',[ FirebaseMessaging::class, 'pushNotifications']);


    # Tire Brands and Models
    Route::apiResource('tire_brands', TireBrandController::class)->only('index');
    Route::apiResource('tire_models', TireModelController::class)->only('index');
    Route::get('tire_brands_all', [TireBrandController::class, 'getTireBrands']);
    Route::get('tire_models_all', [TireModelController::class, 'getTireModels']);
    Route::get('battery_brands', [TireBrandController::class, 'getBatteryBrands']);
    Route::get('battery_models', [TireModelController::class, 'getBatteryModels']);
    Route::get('oil_brands', [TireBrandController::class, 'getOilBrands']);


    # Topics
    Route::apiResource('topics', TopicController::class);


    # Services
    Route::get('alignment_details/{vehicle_id}', [DashboardController::class, 'getAlignmentDetails']);
    Route::get('balancing_details/{vehicle_id}', [DashboardController::class, 'getServiceBalancing']);
    Route::get('battery_details/{vehicle_id}', [DashboardController::class, 'getServiceBattery']);
    Route::get('dashboard_general/{vehicle_id}', [DashboardController::class, 'getServiceDashboard']);
    Route::get('oil_change_details/{vehicle_id}', [DashboardController::class, 'getOilChangeDetails']);
    Route::get('rotation_details/{vehicle_id}', [DashboardController::class, 'getRotationDetails']);
    Route::apiResource('service_tires', ServiceTiresController::class)->only('index');
    Route::get('tire_details/{vehicle_id}', [DashboardController::class, 'getTireDetails']);


    # Settings
    Route::get('logs', [SettingsController::class, 'getAllLogs']);
    Route::post('logs', [SettingsController::class, 'storeLog']);
    Route::post('purge_logs', [SettingsController::class, 'purgeLogs']);
    Route::get('roles', [SettingsController::class, 'getRoles']);
    Route::post('roles', [SettingsController::class, 'createRoles']);
    Route::put('roles/{id}', [SettingsController::class, 'updateRole']);
    Route::delete('roles/{id}', [SettingsController::class, 'deleteRole']);
    Route::get('permissions', [SettingsController::class, 'getPermissions']);
    Route::post('assign_permission', [SettingsController::class, 'assignPermissions']);
    Route::get('settings', [SettingsController::class, 'getSettings']);


    # Stores
    Route::apiResource('stores', StoreController::class)->only('index');
    Route::get('store_services', [StoreController::class, 'store_services']);


    # User List
    Route::post('notifications_user_list', [UserListController::class, 'filterServices']);


    # User Notifications
    Route::get('user_by_notification', [UserNotificationController::class, 'getByNotification']);
    Route::apiResource('user_notifications', UserNotificationController::class);
    Route::get('user_notification_center', [UserNotificationController::class, 'getByUser']);
    Route::get('user_notification_resume', [UserNotificationController::class, 'getUserNotificationsResume']);


    # Users
    Route::put('assign_fcm_token', [UserController::class, 'assignFcmToken']);
    Route::apiResource('users', UserController::class);
    Route::post('upload-img', [UserController::class, 'uploadPhoto']);
    Route::put('users/{id}/terms_and_conditions', [UserController::class, 'updateTermsAndConditions']);
    Route::put('users_by_admin/{id}', [UserController::class, 'updateUserByAdmin']);
    Route::get('users_audit', [UserController::class, 'userFilter']);


    # Utils
    Route::prefix('utils')->group(function () {
        Route::post('status_ping', [SettingsController::class, 'pingUser']);
        Route::post('status_ping/bulk', [SettingsController::class, 'pingUserBulk']);
        Route::post('register-code', [AuthController::class, 'sendCodeForRegister']);
        Route::post('validate-code', [AuthController::class, 'validateCodeForRegister']);
    });


    # Vehicles
    Route::get('service_timeline', [VehicleController::class, 'service_timeline']);
    Route::apiResource('vehicle_brands', VehicleBrandController::class)->only('index');
    Route::get('vehicle_brands/models', [VehicleBrandController::class, 'getBrandsWithModels']);
    Route::apiResource('vehicle_models', VehicleModelController::class)->only('index');
    Route::get('vehicle_summaries', [VehicleSummaryController::class, 'getSummaries']);
    Route::apiResource('vehicles', VehicleController::class)->only(['index', 'store', 'update']);
    Route::get('vehicles_by_res_partner', [VehicleController::class, 'get_by_id']);

    
    # Vehicle model photos
    Route::apiResource('vehicle_model_photos', VehicleModelPhotoController::class)->except('show', 'update');
    Route::post('vehicle_model_photos/{id}', [VehicleModelPhotoController::class, 'update']);
});