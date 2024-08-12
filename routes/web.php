<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes([
    'password.confirm' => false, // 404 disabled
    'password.email'   => false, // 404 disabled
    'password.request' => false, // 404 disabled
    'password.reset'   => false, // 404 disabled
    'register'         => false, // 404 disabled
]);

Route::group(['as'=>'app.','prefix'=>'admin/','middleware'=>['auth']],function(){
// Route::group(['as'=>'app.','prefix'=>'admin/'],function(){
        // Dashboard Route
        Route::get('/','backendController@index')->name('index');

        // Profile Routes
        Route::get('my-profile', 'ProfileController@my_profile')->name('profile.index');
        Route::post('my-profile/update', 'ProfileController@my_profile_update')->name('profile.update');
        Route::post('password-update', 'ProfileController@change_password')->name('password.update');

        // User Routes
        Route::group(['as'=>'user.','prefix'=>'user/'], function(){
            Route::get('/','UserController@index')->name('index');
            Route::post('update-or-store','UserController@updateOrStore')->name('update-or-store');
            Route::post('edit','UserController@edit')->name('edit');
            Route::post('delete','UserController@delete')->name('delete');
            Route::post('bulk-delete','UserController@bulkDelete')->name('bulk-delete');
            Route::post('status-change','UserController@statusChange')->name('status-change');
            Route::get('import','UserController@import')->name('import');
            Route::post('import-data','UserController@importData')->name('import-data');
        });

        // Category Routes
        Route::group(['prefix'=>'category/','as'=>'category.'],function(){
            Route::get('/','CategoryController@index')->name('index');
            Route::post('update-or-store','CategoryController@updateOrStore')->name('update-or-store');
            Route::post('edit','CategoryController@edit')->name('edit');
            Route::post('delete','CategoryController@delete')->name('delete');
            Route::post('bulk-delete','CategoryController@bulkDelete')->name('bulk-delete');
            Route::post('status-change','CategoryController@statusChange')->name('status-change');
            Route::get('import','CategoryController@import')->name('import');
            Route::post('import-data','CategoryController@importData')->name('import-data');
        });


           // Product Routes
           Route::group(['prefix'=>'product/','as'=>'product.'],function(){
            Route::get('/','ProductController@index')->name('index');
            Route::post('update-or-store','ProductController@updateOrStore')->name('update-or-store');
            Route::post('edit','ProductController@edit')->name('edit');
            Route::post('delete','ProductController@delete')->name('delete');
            Route::post('bulk-delete','ProductController@bulkDelete')->name('bulk-delete');
            Route::post('status-change','ProductController@statusChange')->name('status-change');
            Route::get('import','ProductController@import')->name('import');
            Route::post('import-data','ProductController@importData')->name('import-data');
        });


         // hero Routes
         Route::group(['as'=>'heroes.','prefix'=>'heroes/'], function(){
            Route::get('/','HeroController@index')->name('index');
            Route::post('update-or-store','HeroController@updateOrStore')->name('update-or-store');
            Route::post('edit','HeroController@edit')->name('edit');
            Route::post('delete','HeroController@delete')->name('delete');
            Route::post('bulk-delete','HeroController@bulkDelete')->name('bulk-delete');
            Route::post('status-change','HeroController@statusChange')->name('status-change');
        });

    // Branch Routes
    // Route::group(['prefix'=>'branchs/','as'=>'branch.'],function(){
    //     Route::get('/','BranchController@index')->name('index');
    //     Route::post('update-or-store','BranchController@updateOrStore')->name('update-or-store');
    //     Route::post('edit','BranchController@edit')->name('edit');
    //     Route::post('delete','BranchController@delete')->name('delete');
    //     Route::post('bulk-delete','BranchController@bulkDelete')->name('bulk-delete');
    //     Route::post('status-change','BranchController@statusChange')->name('status-change');
    //     Route::get('import','BranchController@import')->name('import');
    //     Route::post('import-data','BranchController@importData')->name('import-data');
    // });

});
