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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//List People
Route::get('people', 'PeopleController@index');

//List a Person
Route::get('person/{id}', 'PeopleController@show');
Route::get('person/fname/{first_name}', 'PeopleController@showFirstName');
Route::get('person/lname/{last_name}', 'PeopleController@showLastName');

//Create person record
Route::post('person', 'PeopleController@store');

//Update a person record
Route::put('person', 'PeopleController@store');

//Delete a person record
Route::delete('person/{id}', 'PeopleController@destroy');

