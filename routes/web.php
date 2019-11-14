<?php

Route::get('/test', function () {
    // dd(Auth::user());
    // dd(get_agents_info());
    // dd(parseDate("04-07-2019"));
    // dd(authUser());
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mat', function () {
    return view('layouts/material-dashboard');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
