<?php

/*
|--------------------------------------------------------------------------
| Browse Routes
|--------------------------------------------------------------------------
|
| Routes for pages that don't require being logged in to view,
| specifically the information pages.
|
*/

/**************************************************************************************************
    Widgets
**************************************************************************************************/

Route::get('items/{id}', 'Users\InventoryController@getStack')->name('browse.items.stack');
Route::get('items/character/{id}', 'Users\InventoryController@getCharacterStack')->name('browse.items.character.stack');

/**************************************************************************************************
    News
**************************************************************************************************/
// PROFILES
Route::group(['prefix' => 'news', 'as' => 'browse.news.'], function () {
    Route::get('/', 'NewsController@getIndex')->name('index');
    Route::get('{id}.{slug?}', 'NewsController@getNews')->name('show');
    Route::get('{id}.', 'NewsController@getNews')->name('show.bare');
});

/**************************************************************************************************
    Sales
**************************************************************************************************/
// PROFILES
Route::group(['prefix' => 'sales', 'as' => 'browse.sales.'], function () {
    Route::get('/', 'SalesController@getIndex')->name('index');
    Route::get('{id}.{slug?}', 'SalesController@getSales')->name('show');
    Route::get('{id}.', 'SalesController@getSales')->name('show.bare');
});

/**************************************************************************************************
    Users
**************************************************************************************************/
Route::get('/users', 'BrowseController@getUsers')->name('browse.users');
Route::get('/blacklist', 'BrowseController@getBlacklist')->name('browse.blacklist');
Route::get('/deactivated-list', 'BrowseController@getDeactivated')->name('browse.deactivated');

// PROFILES
Route::group(['prefix' => 'user', 'as' => 'browse.user.', 'namespace' => 'Users'], function () {
    Route::get('{name}/gallery', 'UserController@getUserGallery')->name('gallery');
    Route::get('{name}/favorites', 'UserController@getUserFavorites')->name('favorites');
    Route::get('{name}/favorites/own-characters', 'UserController@getUserOwnCharacterFavorites')->name('favorites.own-characters');

    Route::get('{name}', 'UserController@getUser')->name('show');
    Route::get('{name}/aliases', 'UserController@getUserAliases')->name('aliases');
    Route::get('{name}/characters', 'UserController@getUserCharacters')->name('characters');
    Route::get('{name}/sublist/{key}', 'UserController@getUserSublist')->name('sublist');
    Route::get('{name}/myos', 'UserController@getUserMyoSlots')->name('myos');
    Route::get('{name}/inventory', 'UserController@getUserInventory')->name('inventory');
    Route::get('{name}/bank', 'UserController@getUserBank')->name('bank');

    Route::get('{name}/currency-logs', 'UserController@getUserCurrencyLogs')->name('currency-logs');
    Route::get('{name}/item-logs', 'UserController@getUserItemLogs')->name('item-logs');
    Route::get('{name}/ownership', 'UserController@getUserOwnershipLogs')->name('ownership');
    Route::get('{name}/submissions', 'UserController@getUserSubmissions')->name('submissions');
});

/**************************************************************************************************
    Characters
**************************************************************************************************/
Route::get('/masterlist', 'BrowseController@getCharacters')->name('browse.masterlist');
Route::get('/myos', 'BrowseController@getMyos')->name('browse.myos');
Route::get('/sublist/{key}', 'BrowseController@getSublist')->name('browse.sublist');
Route::group(['prefix' => 'character', 'as' => 'browse.character.', 'namespace' => 'Characters'], function () {
    Route::get('{slug}', 'CharacterController@getCharacter')->name('show');
    Route::get('{slug}/profile', 'CharacterController@getCharacterProfile')->name('profile');
    Route::get('{slug}/bank', 'CharacterController@getCharacterBank')->name('bank');
    Route::get('{slug}/inventory', 'CharacterController@getCharacterInventory')->name('inventory');
    Route::get('{slug}/images', 'CharacterController@getCharacterImages')->name('images');

    Route::get('{slug}/currency-logs', 'CharacterController@getCharacterCurrencyLogs')->name('logs.currency');
    Route::get('{slug}/item-logs', 'CharacterController@getCharacterItemLogs')->name('logs.items');
    Route::get('{slug}/ownership', 'CharacterController@getCharacterOwnershipLogs')->name('logs.ownership');
    Route::get('{slug}/change-log', 'CharacterController@getCharacterLogs')->name('logs');
    Route::get('{slug}/submissions', 'CharacterController@getCharacterSubmissions')->name('submissions');

    Route::get('{slug}/gallery', 'CharacterController@getCharacterGallery')->name('gallery');
});
Route::group(['prefix' => 'myo', 'as' => 'browse.myo.', 'namespace' => 'Characters'], function () {
    Route::get('{id}', 'MyoController@getCharacter')->name('show');
    Route::get('{id}/profile', 'MyoController@getCharacterProfile')->name('profile');
    Route::get('{id}/ownership', 'MyoController@getCharacterOwnershipLogs')->name('logs.ownership');
    Route::get('{id}/change-log', 'MyoController@getCharacterLogs')->name('logs');
});

/**************************************************************************************************
    World
**************************************************************************************************/

Route::group(['prefix' => 'world', 'as' => 'browse.world.'], function () {
    Route::get('/', 'WorldController@getIndex')->name('index');

    Route::get('currencies', 'WorldController@getCurrencies')->name('currencies');
    Route::get('rarities', 'WorldController@getRarities')->name('rarities');
    Route::get('species', 'WorldController@getSpecieses')->name('species');
    Route::get('subtypes', 'WorldController@getSubtypes')->name('subtypes');
    Route::get('species/{id}/traits', 'WorldController@getSpeciesFeatures')->name('species.traits');
    Route::get('species/{speciesId}/trait/{id}', 'WorldController@getSpeciesFeatureDetail')->where(['id' => '[0-9]+', 'speciesId' => '[0-9]+'])->name('species.trait');
    Route::get('item-categories', 'WorldController@getItemCategories')->name('item-categories');
    Route::get('items', 'WorldController@getItems')->name('items');
    Route::get('items/{id}', 'WorldController@getItem')->name('item');
    Route::get('trait-categories', 'WorldController@getFeatureCategories')->name('trait-categories');
    Route::get('traits', 'WorldController@getFeatures')->name('traits');
    Route::get('character-categories', 'WorldController@getCharacterCategories')->name('character-categories');
});

Route::group(['prefix' => 'prompts', 'as' => 'browse.prompts.'], function () {
    Route::get('/', 'PromptsController@getIndex')->name('index');
    Route::get('prompt-categories', 'PromptsController@getPromptCategories')->name('categories');
    Route::get('prompts', 'PromptsController@getPrompts')->name('list');
    Route::get('{id}', 'PromptsController@getPrompt')->name('show');
});

Route::group(['prefix' => 'shops', 'as' => 'browse.shops.'], function () {
    Route::get('/', 'ShopController@getIndex')->name('index');
    Route::get('{id}', 'ShopController@getShop')->where(['id' => '[0-9]+'])->name('show');
    Route::get('{id}/{stockId}', 'ShopController@getShopStock')->where(['id' => '[0-9]+', 'stockId' => '[0-9]+'])->name('stock');
});

/**************************************************************************************************
    Site Pages
**************************************************************************************************/
Route::get('credits', 'PageController@getCreditsPage')->name('browse.credits');
Route::get('info/{key}', 'PageController@getPage')->name('browse.page');

/**************************************************************************************************
    Raffles
**************************************************************************************************/
Route::group(['prefix' => 'raffles', 'as' => 'browse.raffles.'], function () {
    Route::get('/', 'RaffleController@getRaffleIndex')->name('index');
    Route::get('view/{id}', 'RaffleController@getRaffleTickets')->name('show');
});

/**************************************************************************************************
    Submissions
**************************************************************************************************/
Route::group(['prefix' => 'submissions', 'as' => 'browse.submissions.', 'namespace' => 'Users'], function () {
    Route::get('view/{id}', 'SubmissionController@getSubmission')->name('show');
});
Route::group(['prefix' => 'claims', 'as' => 'browse.claims.', 'namespace' => 'Users'], function () {
    Route::get('view/{id}', 'SubmissionController@getClaim')->name('show');
});

/**************************************************************************************************
    Comments
**************************************************************************************************/
Route::get('comment/{id}', 'PermalinkController@getComment')->name('browse.comment');

/**************************************************************************************************
    Galleries
**************************************************************************************************/
Route::group(['prefix' => 'gallery', 'as' => 'browse.gallery.'], function () {
    Route::get('/', 'GalleryController@getGalleryIndex')->name('index');
    Route::get('all', 'GalleryController@getAll')->name('all');
    Route::get('{id}', 'GalleryController@getGallery')->name('show');
    Route::get('view/{id}', 'GalleryController@getSubmission')->name('submission');
    Route::get('view/favorites/{id}', 'GalleryController@getSubmissionFavorites')->name('submission.favorites');
});

/**************************************************************************************************
    Reports
**************************************************************************************************/
Route::group(['prefix' => 'reports', 'as' => 'browse.reports.', 'namespace' => 'Users'], function () {
    Route::get('/bug-reports', 'ReportController@getBugIndex')->name('bugs');
});
