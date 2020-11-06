<?php

Route::get('/', 'IndexController@index');
Route::post('/allow-messages', 'IndexController@allowMessages');
Route::post('/setup-confirmation', 'IndexController@setupConfirmation');
Route::post('/get-cheapest-ticket', 'IndexController@getCheapestTicket');

Route::post('/save-admin', 'AdminController@save');
Route::post('/save-chat', 'ChatController@save');
Route::post('/save-user-api-request', 'UserController@saveRequest');

Route::post('/save-groups', 'GroupController@save');
Route::post('/check-group-enable', 'GroupController@checkGroupEnable');

Route::post('/get-tags', 'TagController@all');
Route::post('/save-tags', 'TagController@save');
Route::post('/remove-tags', 'TagController@remove');

Route::post('/get-requests', 'RequestController@all');
Route::post('/delete-request', 'RequestController@delete');

Route::post('/bot', 'VkGroupEventsListenerController@bot');
