<?php

use Enhacudima\DynamicExtract\Http\Controllers\AuthController;
use Enhacudima\DynamicExtract\Http\Controllers\FileDownloadController;
use Enhacudima\DynamicExtract\Http\Controllers\Reports\API\ExternalPushReport;
use Enhacudima\DynamicExtract\Http\Controllers\Reports\API\ExternalPushReportConfig;
use Enhacudima\DynamicExtract\Http\Controllers\Reports\ConfigurationControllerReport;
use Enhacudima\DynamicExtract\Http\Controllers\Reports\ExtractControllerReport;

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->prefix(config('dynamic-extract.prefix'))->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'welcome');
        Route::get('/sign-in/{user}', 'signIn')->name(config('dynamic-extract.prefix').'/sign-in');
        Route::get('/sign-out', 'logout')->name(config('dynamic-extract.prefix').'/sign-out');
    });

    Route::controller(ExternalPushReport::class)->group(function () {
        Route::get('/api/v1/data/{token}', 'index');
    });
    Route::controller(ExternalPushReportConfig::class)->group(function () {
        Route::get('/report/config/external/api', 'index');
        Route::post('report/config/external/api/store/new','store');
        Route::get('/report/config/external/api/delete/{id}', 'delete');
        Route::get('/report/config/external/api/edit/{id}', 'edit');
        Route::post('/report/config/external/api/store/edit', 'store_edit');
        Route::get('/report/config/external/api/schedule/{id}','schedule');
        Route::post('/report/config/external/api/schedule/add', 'schedule_add');
        Route::get('/report/config/external/api/schedule/delete/{id}','schedule_remove');
    });

    Route::controller(ExtractControllerReport::class)->group(function () {
        Route::get('/report/index', 'index');
        Route::get('/report/new', 'new');
        Route::get('/meusficheiros/deletefile/{filename}', 'deletefile');
        Route::get('/meusficheiros/all/deletefile', 'alldeletefile');
        Route::post('/report/filtro', 'filtro');
        Route::post('/report/filtro/table','view_filtro');
        Route::get('report/config/open/{id}/{type}','open_report_extract');
        Route::get('/report/config/favorite/{id}', 'favorite');
        Route::get('/report/config/favorite/remove/{id}', 'favorite_remove');
        Route::get('/search-report', 'search_reports');
    });

    Route::get('file/download/{filename}', [FileDownloadController::class, 'index'])->name(config('dynamic-extract.prefix').'/file/download');
    Route::controller(ConfigurationControllerReport::class)->group(function () {
        Route::get('report/config//delete/{id}','delete_report');
        Route::get('report/config/filtro/delete/{id}','delete_group_filter');
        Route::get('report/config/filtro/filtros/delete/{id}','delete_filter');
        Route::get('/report/config', 'index');
        Route::post('/report/config/store/new', 'store');
        Route::get('/report/config/delete/{id}', 'delete');
        Route::get('/report/config/edit/{id}', 'edit');
        Route::post('/report/config/store/edit', 'store_edit');

        Route::get('/report/config/filtro', 'filtro_index');
        Route::post('/report/config/filtro/store', 'filtro_index_store');
        Route::get('/report/config/filtro/edit/{id}', 'filtro_index_edit');
        Route::post('/report/config/filtro/edit/store', 'filtro_index_edit_store');
        Route::get('/report/config/filtro/filtros', 'filtros_all');

        Route::post('/report/config/filtro/filtros/new/store', 'filtros_all_store');
        Route::get('/report/config/filtro/filtros/edit/{id}', 'filtros_all_edit');
        Route::post('/report/config/filtro/filtros/edit/store', 'filtros_all_edit_store');
        Route::get('/report/config/filtro/list', 'filtros_list');
        Route::post('/report/config/filtro/list/store', 'filtros_list_store');

        Route::get('/report/config/filtro/list/edit/{id}', 'filtros_list_edit');
        Route::post('/report/config/filtro/list/edit/store', 'filtros_list_edit_store');
        Route::get('/report/config/filtro/columuns', 'filtros_columuns');
        Route::post('/report/config/filtro/columuns/store', 'filtros_columuns_store');
        Route::get('/report/config/filtro/columuns/edit/{id}', 'filtros_columuns_edit');

        Route::post('/report/config/filtro/columuns/edit/store', 'filtros_columuns_edit_store');
    });
});
