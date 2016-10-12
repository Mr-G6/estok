<?php

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::group(['middleware'  =>  'auth'], function(){
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

        Route::post('/warehouse/checkout/transaction', 'CheckoutController@checkout');
        Route::get('/warehouse/checkout/{id}', 'CheckoutController@index');
        Route::get('/warehouse/checkout/{id}/products', 'CheckoutController@getProducts');
        Route::get('/warehouse/checkout/product/details', 'CheckoutController@getProductDetails');
        Route::get('/warehouse/checkout/product/quantity', 'CheckoutController@validateQuantity');

        Route::get('/warehouse/{id}/profit', 'ProfitController@index');
        Route::get('/warehouse/{id}/profit/byName', 'ProfitController@getTransactionsByName');
        Route::get('/warehouse/{id}/profit/byDate', 'ProfitController@getTransactionsByDate');

        Route::get('/warehouse/{id}/sales', 'SalesController@index');
        Route::get('/warehouse/sales/byReceiptId', 'SalesController@getSales');

        Route::get('/warehouse/sales/byReceiptIdOrName', 'SalesController@getSalesByIdOrName');

        Route::post('/warehouse/sales/delete', 'SalesController@deleteSales');

        Route::get('/print/receipt/{id}', 'PrinterController@receipt');
    });
});
