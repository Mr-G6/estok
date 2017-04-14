<?php

Route::group(['middleware'  =>  'auth'], function(){
    Route::get('/', 'DashboardController@index');

    Route::get('/settings', 'DashboardController@getConfig');
    Route::post('/settings', 'DashboardController@updateConfig');
    Route::get('/add/inventory', 'DashboardController@newInventory');
    Route::post('/add/inventory', 'DashboardController@createInventory');
    Route::delete('/inventory', 'DashboardController@deleteInventory');


    Route::get('/inventory/{id}/products', 'InventoryController@index');
    Route::get('/inventory/edit/{id}', 'InventoryController@editInventory');
    Route::post('/inventory/update', 'InventoryController@updateInventory');
    Route::get('/inventory/product/exist', 'InventoryController@productExist');
    Route::post('/inventory/product/add', 'InventoryController@addProduct');
    Route::post('/inventory/product/update', 'InventoryController@updateProduct');
    Route::get('/inventory/{id}/products/add', 'InventoryController@newProduct');
    Route::get('/inventory/{wh_id}/products/delete/{id}', 'InventoryController@deleteProduct');
    Route::get('/inventory/{id}/products/edit/{p_id}', 'InventoryController@editProduct');

    Route::post('/inventory/checkout/transaction', 'CheckoutController@checkout');
    Route::get('/inventory/checkout/{id}', 'CheckoutController@index');
    Route::get('/inventory/checkout/{id}/products', 'CheckoutController@getProducts');
    Route::get('/inventory/checkout/product/details', 'CheckoutController@getProductDetails');
    Route::get('/inventory/checkout/product/quantity', 'CheckoutController@validateQuantity');

    Route::get('/inventory/{id}/profit', 'ProfitController@index');
    Route::get('/inventory/{id}/profit/byId', 'ProfitController@getTransactionsById');
    Route::get('/inventory/{id}/profit/byName', 'ProfitController@getTransactionsByName');
    Route::get('/inventory/{id}/profit/byDate', 'ProfitController@getTransactionsByDate');

    Route::get('/inventory/{id}/sales', 'SalesController@index');
    Route::post('/inventory/clear/sales', 'SalesController@clearDues');
    Route::get('/inventory/sales/byReceiptId', 'SalesController@getSales');

    Route::get('/inventory/sales/byReceiptIdOrName', 'SalesController@getSalesByIdOrName');

    Route::post('/inventory/sales/delete', 'SalesController@deleteSales');

    Route::get('/print/receipt/{id}', 'PrinterController@receipt');

    Route::get('/logout', 'DashboardController@logout');


});
Auth::routes();