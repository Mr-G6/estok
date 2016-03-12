<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'DashboardController@index');

    Route::get('/warehouse/{id}/products', 'WareHouseController@index');
    Route::get('/warehouse/edit/{id}', 'WareHouseController@editWareHouse');
    Route::post('/warehouse/update', 'WareHouseController@updateWarehouse');
    Route::get('/warehouse/product/exist', 'WareHouseController@productExist');
    Route::post('/warehouse/product/add', 'WareHouseController@addProduct');
    Route::post('/warehouse/product/update', 'WareHouseController@updateProduct');
    Route::get('/warehouse/{id}/products/add', 'WareHouseController@newProduct');
    Route::get('/warehouse/{wh_id}/products/delete/{id}', 'WareHouseController@deleteProduct');
    Route::get('/warehouse/{id}/products/edit/{p_id}', 'WareHouseController@editProduct');
});
