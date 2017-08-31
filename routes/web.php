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


Auth::routes();

Route::get('/', function () {
    return redirect()->route('chat');
});

/*
*  in this web app, we authenticated users.
 * to enforce Authenticated users only to use the app.
*/
Route::group(['middleware' => 'auth'], function () {
    /*
     * route for testing to get messenger views.
     */
    Route::get('/messenger/{chat_id?}', [
        'uses' => 'ChatController@Messenger',
        'as' => 'chat'
    ]);
    /*
     * route creating a new message with the API.
     */
    Route::post('/createMessage', 'ChatController@createMessage');
    /*
     * route for retrieving the message to the receiver.
     */
    Route::get('/retrieveMessage', 'ChatController@retrieveMessage');
    /*
     * route for updating the user status [ online or offline ].
     */
    Route::get('/chat_users', 'ChatController@chat_users');

});
