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

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@home')->name('home');
Route::get('/article/{type}/{slug}', 'ArticleController@show')->name('article');
Route::get('/search', 'Admin\ArticleController@search')->name('article.search');

Route::prefix('/admin')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/2fa', 'Auth\LoginController@twofactor')->name('2fa')->middleware('2fa');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('/lock', 'Auth\LoginController@lock')->name('lock');
    Route::get('/access', 'Admin\DashboardController@access')->name('admin.request_access');

    Route::group(['middleware' => ['auth', '2fa']], function () {
        Route::get('/', 'Admin\DashboardController@dashboard');
        Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name('admin.dashboard');
        Route::get('/settings', 'Admin\SettingsController@settings')->name('admin.settings');

        Route::group(['prefix' => 'articles'], function () {
            Route::get('/', 'Admin\ArticleController@articles')->name('admin.articles');
            Route::get('/new', 'Admin\ArticleController@newArticle')->name('admin.new_article');
            Route::post('/new', 'Admin\ArticleController@store')->name('admin.new_article');
            Route::post('/upload', 'Admin\ArticleController@image')->name('admin.upload');
        });

        Route::group(['prefix' => 'article'], function () {
            Route::get('/{id}', 'Admin\ArticleController@article')->name('admin.article');
            Route::post('/{id}', 'Admin\ArticleController@update');
            Route::post('/delete/{id}', 'Admin\ArticleController@delete')->name('admin.delete_article');
        });

        Route::get('/preview/{id}', 'Admin\ArticleController@preview')->name('admin.preview_article');
    });
});

Route::group(['prefix' => 'assets'], function () {
    Route::get('/images/{image}', 'AssetController@getImage')->name('asset.image');
});
