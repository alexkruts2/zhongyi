<?php



Route::group(['prefix' => 'mobile'], function () {
    Route::get('/property', 'Share\MobileController@index')->name('mobile.index');
    Route::get('/yimin', 'Share\MobileController@yimin')->name('mobile.yimin');
});