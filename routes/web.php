<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});
//
Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');


//список
Route::get('/', 'LinkController@list')->name('short_links');
//добавление
Route::get('/add/', 'LinkController@addForm')->name('add_short_link');
Route::post('/add/', 'LinkController@add');

Route::group(
    array(
        'prefix' => 'user',
        'middleware' => 'auth'
    ),
    function() {
        //профиль
        Route::get('/', 'UserController@profile')->name('user');
        //редактирование профиля
        Route::get('/edit', 'UserController@editForm')->name('edit_profile');
        Route::post('/edit', 'UserController@edit');
        //роли остальным пользователям
        Route::get('/give_role', 'UserController@usersList')
            ->middleware('role:admin')
            ->name('give_role');
        Route::post('/give_role', 'UserController@giveRoleUser')->middleware('role:admin');

        //короткие ссылки пользователя
        Route::group(
            array(
                'prefix' => 'short_link'
            ),
            function() {
                //список
                Route::get('/', 'LinkController@userLinkList')->name('user_link');
                //добавление
                Route::get('/add/', 'LinkController@addForm');
                Route::post('/add/', 'LinkController@add');
                //редактирование
                Route::get('/edit/{id}', array('as' => 'edit_link', 'uses' => 'LinkController@editForm'));
                Route::post('/edit/{id}', array('as' => 'delete_link', 'uses' => 'LinkController@edit'));
                //удаление
                Route::get('/delete/{id}', 'LinkController@delete');

                //модерация линков
                Route::group(
                    array(
                        'prefix' => 'moderate',
                        'middleware' => 'role:moderator'
                    ),
                    function() {
                        Route::get('/', 'LinkController@moderateLinkList')->name('moderate_links');

                        Route::get('/activate/{id}', array('as'=> 'activate_link', 'uses' => 'LinkController@activateLink'));
                        Route::get('/deactivate/{id}', array('as' => 'deactivate_link', 'uses' => 'LinkController@deactivateLink'));
                    }
                );
            }
        );
    }
);

//переход
Route::get('/{id}', 'LinkController@link')->name('short_link');