<?php

Route::group(['namespace' => 'Admin'], function () {

    /**
     * Project Local Lists
     */
    Route::prefix('locales')->group(function () {
        Route::controller('LanguageController')->group(function () {
            Route::get('/', 'getLocales');
        });
    });

    /**
     * Commercial Users list
     */
    Route::group(['namespace' => 'Commercial',  'prefix' => 'commercial'], function () {
        Route::get('users', 'UsersController@list');
        Route::get('businesses', 'BusinessController@list');
        Route::get('period-of-stay', 'GroupController@list');
    });

    Route::group(['namespace' => 'Pages'], function () {
        Route::controller('PagesController')->group(function () {
            Route::get('text-pages', 'getPages');
        })->namespace('Pages');
    });

    /**
     * Categories Route Namespace
     */
    Route::group(['namespace' => 'Category'], function () {

        Route::prefix('categories')->group(function () {
            Route::controller('CategoryController')->group(function () {
                Route::get('/', 'list');
                Route::get('list', 'parentCategories');
                Route::get('top-list', 'topCategories');
                Route::get('{category}', 'show');
            });
        });

        Route::get('filters', 'FilterController@list');
        Route::get('filters/answers', 'FilterController@answersList');
    });

    Route::get('refusals', 'RefusalController@list');
});
