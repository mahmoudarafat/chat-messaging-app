<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/* ==================================================== */


/*
*  in API we authenticated users by using Passport.
 * and middleware API Authentication [ auth:api ].
 * to enforce Authorized users only to use the app.
*/

/*
 * route for testing to register user from the API.
 */
Route::post('/register', 'APIRegisterController@register');

Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {
    /*
     * route for testing to get the auth user info.
     */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    /*
     * route for testing to get messenger views.
     */
    Route::get('/messenger/{chat_id?}', ['uses' => 'ChatController@Messenger', 'as' => 'chat']);
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