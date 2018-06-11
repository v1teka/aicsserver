<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/list', 'InvBaseController@ShowFull');

Route::any('/jsonport', 'InvBaseController@Update');

Route::any('/register', 'InvBaseController@Register');

Route::any('/information', function () {
    return view('onebutton');
});

Route::any('/direct', 'InvBaseController@Info');

Route::any('/map', 'InvBaseController@Draw');

Route::any('/online', 'InvBaseController@isOnline');

Route::any('/arp', 'InvBaseController@arpScan');

Route::post('/getmap', 'InvBaseController@getMap');
