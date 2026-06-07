<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for users with powers.
|
*/

Route::get('/', 'HomeController@getIndex')->name('admin.index');

Route::get('logs', 'HomeController@getLogs')->name('admin.logs');
Route::group(['middleware' => 'admin'], function () {
    Route::get('staff-reward-settings', 'HomeController@getStaffRewardSettings')->name('admin.staff-reward-settings');
    Route::post('staff-reward-settings/{key}', 'HomeController@postEditStaffRewardSetting')->name('admin.staff-reward-settings.update');
});

Route::group(['prefix' => 'users', 'as' => 'admin.users.', 'namespace' => 'Users'], function () {
    // USER LIST
    Route::group(['middleware' => 'power:edit_user_info'], function () {
        Route::get('/', 'UserController@getIndex')->name('index');

        Route::get('{name}/edit', 'UserController@getUser')->name('edit');
        Route::post('{name}/basic', 'UserController@postUserBasicInfo')->name('basic.update');
        Route::post('{name}/alias/{id}', 'UserController@postUserAlias')->name('alias.update');
        Route::post('{name}/account', 'UserController@postUserAccount')->name('account.update');
        Route::post('{name}/birthday', 'UserController@postUserBirthday')->name('birthday.update');
        Route::get('{name}/updates', 'UserController@getUserUpdates')->name('updates');

        Route::get('{name}/ban', 'UserController@getBan')->name('ban');
        Route::get('{name}/ban-confirm', 'UserController@getBanConfirmation')->name('ban.confirm');
        Route::post('{name}/ban', 'UserController@postBan')->name('ban.store');
        Route::get('{name}/unban-confirm', 'UserController@getUnbanConfirmation')->name('unban.confirm');
        Route::post('{name}/unban', 'UserController@postUnban')->name('unban.store');

        Route::get('{name}/deactivate', 'UserController@getDeactivate')->name('deactivate');
        Route::get('{name}/deactivate-confirm', 'UserController@getDeactivateConfirmation')->name('deactivate.confirm');
        Route::post('{name}/deactivate', 'UserController@postDeactivate')->name('deactivate.store');
        Route::get('{name}/reactivate-confirm', 'UserController@getReactivateConfirmation')->name('reactivate.confirm');
        Route::post('{name}/reactivate', 'UserController@postReactivate')->name('reactivate.store');
    });

    // RANKS
    Route::group(['middleware' => 'admin'], function () {
        Route::get('ranks', 'RankController@getIndex')->name('ranks.index');
        Route::get('ranks/create', 'RankController@getCreateRank')->name('ranks.create');
        Route::get('ranks/edit/{id}', 'RankController@getEditRank')->name('ranks.edit');
        Route::get('ranks/delete/{id}', 'RankController@getDeleteRank')->name('ranks.delete');
        Route::post('ranks/create', 'RankController@postCreateEditRank')->name('ranks.store');
        Route::post('ranks/edit/{id?}', 'RankController@postCreateEditRank')->name('ranks.update');
        Route::post('ranks/delete/{id}', 'RankController@postDeleteRank')->name('ranks.destroy');
        Route::post('ranks/sort', 'RankController@postSortRanks')->name('ranks.sort.save');
    });
});

// SETTINGS
Route::group(['prefix' => 'invitations', 'as' => 'admin.invitations.', 'middleware' => 'power:edit_site_settings'], function () {
    Route::get('/', 'InvitationController@getIndex')->name('index');

    Route::post('create', 'InvitationController@postGenerateKey')->name('store');
    Route::post('delete/{id}', 'InvitationController@postDeleteKey')->name('destroy');
});

// FILE MANAGER
Route::group(['prefix' => 'files', 'as' => 'admin.files.', 'middleware' => 'power:edit_site_settings'], function () {
    Route::get('/{folder?}', 'FileController@getIndex')->name('index');

    Route::post('upload', 'FileController@postUploadFile')->name('upload');
    Route::post('move', 'FileController@postMoveFile')->name('move');
    Route::post('rename', 'FileController@postRenameFile')->name('rename');
    Route::post('delete', 'FileController@postDeleteFile')->name('delete');
    Route::post('folder/create', 'FileController@postCreateFolder')->name('folder.create');
    Route::post('folder/delete', 'FileController@postDeleteFolder')->name('folder.delete');
    Route::post('folder/rename', 'FileController@postRenameFolder')->name('folder.rename');
});

// SITE IMAGES
Route::group(['prefix' => 'images', 'as' => 'admin.images.', 'middleware' => 'power:edit_site_settings'], function () {
    Route::get('/', 'FileController@getSiteImages')->name('index');

    Route::post('upload/css', 'FileController@postUploadCss')->name('upload.css');
    Route::post('upload', 'FileController@postUploadImage')->name('upload');
    Route::post('reset', 'FileController@postResetFile')->name('reset');
});

// DATA
Route::group(['prefix' => 'data', 'as' => 'admin.data.', 'namespace' => 'Data', 'middleware' => 'power:edit_data'], function () {
    // GALLERIES
    Route::get('galleries', 'GalleryController@getIndex')->name('galleries.index');
    Route::get('galleries/create', 'GalleryController@getCreateGallery')->name('galleries.create');
    Route::get('galleries/edit/{id}', 'GalleryController@getEditGallery')->name('galleries.edit');
    Route::get('galleries/delete/{id}', 'GalleryController@getDeleteGallery')->name('galleries.delete');
    Route::post('galleries/create', 'GalleryController@postCreateEditGallery')->name('galleries.store');
    Route::post('galleries/edit/{id?}', 'GalleryController@postCreateEditGallery')->name('galleries.update');
    Route::post('galleries/delete/{id}', 'GalleryController@postDeleteGallery')->name('galleries.destroy');
    Route::post('galleries/sort', 'GalleryController@postSortGallery')->name('galleries.sort.save');

    // CURRENCIES
    Route::get('currencies', 'CurrencyController@getIndex')->name('currencies.index');
    Route::get('currencies/sort', 'CurrencyController@getSort')->name('currencies.sort');
    Route::get('currencies/create', 'CurrencyController@getCreateCurrency')->name('currencies.create');
    Route::get('currencies/edit/{id}', 'CurrencyController@getEditCurrency')->name('currencies.edit');
    Route::get('currencies/delete/{id}', 'CurrencyController@getDeleteCurrency')->name('currencies.delete');
    Route::post('currencies/create', 'CurrencyController@postCreateEditCurrency')->name('currencies.store');
    Route::post('currencies/edit/{id?}', 'CurrencyController@postCreateEditCurrency')->name('currencies.update');
    Route::post('currencies/delete/{id}', 'CurrencyController@postDeleteCurrency')->name('currencies.destroy');
    Route::post('currencies/sort/{type}', 'CurrencyController@postSortCurrency')->where('type', 'user|character')->name('currencies.sort.save');

    // RARITIES
    Route::get('rarities', 'RarityController@getIndex')->name('rarities.index');
    Route::get('rarities/create', 'RarityController@getCreateRarity')->name('rarities.create');
    Route::get('rarities/edit/{id}', 'RarityController@getEditRarity')->name('rarities.edit');
    Route::get('rarities/delete/{id}', 'RarityController@getDeleteRarity')->name('rarities.delete');
    Route::post('rarities/create', 'RarityController@postCreateEditRarity')->name('rarities.store');
    Route::post('rarities/edit/{id?}', 'RarityController@postCreateEditRarity')->name('rarities.update');
    Route::post('rarities/delete/{id}', 'RarityController@postDeleteRarity')->name('rarities.destroy');
    Route::post('rarities/sort', 'RarityController@postSortRarity')->name('rarities.sort.save');

    // SPECIES
    Route::get('species', 'SpeciesController@getIndex')->name('species.index');
    Route::get('species/create', 'SpeciesController@getCreateSpecies')->name('species.create');
    Route::get('species/edit/{id}', 'SpeciesController@getEditSpecies')->name('species.edit');
    Route::get('species/delete/{id}', 'SpeciesController@getDeleteSpecies')->name('species.delete');
    Route::post('species/create', 'SpeciesController@postCreateEditSpecies')->name('species.store');
    Route::post('species/edit/{id?}', 'SpeciesController@postCreateEditSpecies')->name('species.update');
    Route::post('species/delete/{id}', 'SpeciesController@postDeleteSpecies')->name('species.destroy');
    Route::post('species/sort', 'SpeciesController@postSortSpecies')->name('species.sort.save');
    Route::get('subtypes', 'SpeciesController@getSubtypeIndex')->name('subtypes.index');
    Route::get('subtypes/create', 'SpeciesController@getCreateSubtype')->name('subtypes.create');
    Route::get('subtypes/edit/{id}', 'SpeciesController@getEditSubtype')->name('subtypes.edit');
    Route::get('subtypes/delete/{id}', 'SpeciesController@getDeleteSubtype')->name('subtypes.delete');
    Route::post('subtypes/create', 'SpeciesController@postCreateEditSubtype')->name('subtypes.store');
    Route::post('subtypes/edit/{id?}', 'SpeciesController@postCreateEditSubtype')->name('subtypes.update');
    Route::post('subtypes/delete/{id}', 'SpeciesController@postDeleteSubtype')->name('subtypes.destroy');
    Route::post('subtypes/sort', 'SpeciesController@postSortSubtypes')->name('subtypes.sort.save');

    // ITEMS
    Route::get('item-categories', 'ItemController@getIndex')->name('item-categories.index');
    Route::get('item-categories/create', 'ItemController@getCreateItemCategory')->name('item-categories.create');
    Route::get('item-categories/edit/{id}', 'ItemController@getEditItemCategory')->name('item-categories.edit');
    Route::get('item-categories/delete/{id}', 'ItemController@getDeleteItemCategory')->name('item-categories.delete');
    Route::post('item-categories/create', 'ItemController@postCreateEditItemCategory')->name('item-categories.store');
    Route::post('item-categories/edit/{id?}', 'ItemController@postCreateEditItemCategory')->name('item-categories.update');
    Route::post('item-categories/delete/{id}', 'ItemController@postDeleteItemCategory')->name('item-categories.destroy');
    Route::post('item-categories/sort', 'ItemController@postSortItemCategory')->name('item-categories.sort.save');

    Route::get('items', 'ItemController@getItemIndex')->name('items.index');
    Route::get('items/create', 'ItemController@getCreateItem')->name('items.create');
    Route::get('items/edit/{id}', 'ItemController@getEditItem')->name('items.edit');
    Route::get('items/delete/{id}', 'ItemController@getDeleteItem')->name('items.delete');
    Route::post('items/create', 'ItemController@postCreateEditItem')->name('items.store');
    Route::post('items/edit/{id?}', 'ItemController@postCreateEditItem')->name('items.update');
    Route::post('items/delete/{id}', 'ItemController@postDeleteItem')->name('items.destroy');

    Route::get('items/delete-tag/{id}/{tag}', 'ItemController@getDeleteItemTag')->name('items.tag.delete');
    Route::post('items/delete-tag/{id}/{tag}', 'ItemController@postDeleteItemTag')->name('items.tag.destroy');
    Route::get('items/tag/{id}/{tag}', 'ItemController@getEditItemTag')->name('items.tag.edit');
    Route::post('items/tag/{id}/{tag}', 'ItemController@postEditItemTag')->name('items.tag.update');
    Route::get('items/tag/{id}', 'ItemController@getAddItemTag')->name('items.tag.create');
    Route::post('items/tag/{id}', 'ItemController@postAddItemTag')->name('items.tag.store');

    // SHOPS
    Route::get('shops', 'ShopController@getIndex')->name('shops.index');
    Route::get('shops/create', 'ShopController@getCreateShop')->name('shops.create');
    Route::get('shops/edit/{id}', 'ShopController@getEditShop')->name('shops.edit');
    Route::get('shops/delete/{id}', 'ShopController@getDeleteShop')->name('shops.delete');
    Route::post('shops/create', 'ShopController@postCreateEditShop')->name('shops.store');
    Route::post('shops/edit/{id?}', 'ShopController@postCreateEditShop')->name('shops.update');
    Route::post('shops/stock/{id}', 'ShopController@postEditShopStock')->name('shops.stock.update');
    Route::post('shops/delete/{id}', 'ShopController@postDeleteShop')->name('shops.destroy');
    Route::post('shops/sort', 'ShopController@postSortShop')->name('shops.sort.save');

    // FEATURES (TRAITS)
    Route::get('trait-categories', 'FeatureController@getIndex')->name('trait-categories.index');
    Route::get('trait-categories/create', 'FeatureController@getCreateFeatureCategory')->name('trait-categories.create');
    Route::get('trait-categories/edit/{id}', 'FeatureController@getEditFeatureCategory')->name('trait-categories.edit');
    Route::get('trait-categories/delete/{id}', 'FeatureController@getDeleteFeatureCategory')->name('trait-categories.delete');
    Route::post('trait-categories/create', 'FeatureController@postCreateEditFeatureCategory')->name('trait-categories.store');
    Route::post('trait-categories/edit/{id?}', 'FeatureController@postCreateEditFeatureCategory')->name('trait-categories.update');
    Route::post('trait-categories/delete/{id}', 'FeatureController@postDeleteFeatureCategory')->name('trait-categories.destroy');
    Route::post('trait-categories/sort', 'FeatureController@postSortFeatureCategory')->name('trait-categories.sort.save');

    Route::get('traits', 'FeatureController@getFeatureIndex')->name('traits.index');
    Route::get('traits/create', 'FeatureController@getCreateFeature')->name('traits.create');
    Route::get('traits/edit/{id}', 'FeatureController@getEditFeature')->name('traits.edit');
    Route::get('traits/delete/{id}', 'FeatureController@getDeleteFeature')->name('traits.delete');
    Route::get('traits/check-subtype', 'FeatureController@getCreateEditFeatureSubtype')->name('traits.check-subtype');
    Route::post('traits/create', 'FeatureController@postCreateEditFeature')->name('traits.store');
    Route::post('traits/edit/{id?}', 'FeatureController@postCreateEditFeature')->name('traits.update');
    Route::post('traits/delete/{id}', 'FeatureController@postDeleteFeature')->name('traits.destroy');

    // CHARACTER CATEGORIES
    Route::get('character-categories', 'CharacterCategoryController@getIndex')->name('character-categories.index');
    Route::get('character-categories/create', 'CharacterCategoryController@getCreateCharacterCategory')->name('character-categories.create');
    Route::get('character-categories/edit/{id}', 'CharacterCategoryController@getEditCharacterCategory')->name('character-categories.edit');
    Route::get('character-categories/delete/{id}', 'CharacterCategoryController@getDeleteCharacterCategory')->name('character-categories.delete');
    Route::post('character-categories/create', 'CharacterCategoryController@postCreateEditCharacterCategory')->name('character-categories.store');
    Route::post('character-categories/edit/{id?}', 'CharacterCategoryController@postCreateEditCharacterCategory')->name('character-categories.update');
    Route::post('character-categories/delete/{id}', 'CharacterCategoryController@postDeleteCharacterCategory')->name('character-categories.destroy');
    Route::post('character-categories/sort', 'CharacterCategoryController@postSortCharacterCategory')->name('character-categories.sort.save');

    // SUB MASTERLISTS
    Route::get('sublists', 'SublistController@getIndex')->name('sublists.index');
    Route::get('sublists/create', 'SublistController@getCreateSublist')->name('sublists.create');
    Route::get('sublists/edit/{id}', 'SublistController@getEditSublist')->name('sublists.edit');
    Route::get('sublists/delete/{id}', 'SublistController@getDeleteSublist')->name('sublists.delete');
    Route::post('sublists/create', 'SublistController@postCreateEditSublist')->name('sublists.store');
    Route::post('sublists/edit/{id?}', 'SublistController@postCreateEditSublist')->name('sublists.update');
    Route::post('sublists/delete/{id}', 'SublistController@postDeleteSublist')->name('sublists.destroy');
    Route::post('sublists/sort', 'SublistController@postSortSublist')->name('sublists.sort.save');

    // LOOT TABLES
    Route::get('loot-tables', 'LootTableController@getIndex')->name('loot-tables.index');
    Route::get('loot-tables/create', 'LootTableController@getCreateLootTable')->name('loot-tables.create');
    Route::get('loot-tables/edit/{id}', 'LootTableController@getEditLootTable')->name('loot-tables.edit');
    Route::get('loot-tables/delete/{id}', 'LootTableController@getDeleteLootTable')->name('loot-tables.delete');
    Route::get('loot-tables/roll/{id}', 'LootTableController@getRollLootTable')->name('loot-tables.roll');
    Route::post('loot-tables/create', 'LootTableController@postCreateEditLootTable')->name('loot-tables.store');
    Route::post('loot-tables/edit/{id?}', 'LootTableController@postCreateEditLootTable')->name('loot-tables.update');
    Route::post('loot-tables/delete/{id}', 'LootTableController@postDeleteLootTable')->name('loot-tables.destroy');

    // PROMPTS
    Route::get('prompt-categories', 'PromptController@getIndex')->name('prompt-categories.index');
    Route::get('prompt-categories/create', 'PromptController@getCreatePromptCategory')->name('prompt-categories.create');
    Route::get('prompt-categories/edit/{id}', 'PromptController@getEditPromptCategory')->name('prompt-categories.edit');
    Route::get('prompt-categories/delete/{id}', 'PromptController@getDeletePromptCategory')->name('prompt-categories.delete');
    Route::post('prompt-categories/create', 'PromptController@postCreateEditPromptCategory')->name('prompt-categories.store');
    Route::post('prompt-categories/edit/{id?}', 'PromptController@postCreateEditPromptCategory')->name('prompt-categories.update');
    Route::post('prompt-categories/delete/{id}', 'PromptController@postDeletePromptCategory')->name('prompt-categories.destroy');
    Route::post('prompt-categories/sort', 'PromptController@postSortPromptCategory')->name('prompt-categories.sort.save');

    Route::get('prompts', 'PromptController@getPromptIndex')->name('prompts.index');
    Route::get('prompts/create', 'PromptController@getCreatePrompt')->name('prompts.create');
    Route::get('prompts/edit/{id}', 'PromptController@getEditPrompt')->name('prompts.edit');
    Route::get('prompts/delete/{id}', 'PromptController@getDeletePrompt')->name('prompts.delete');
    Route::post('prompts/create', 'PromptController@postCreateEditPrompt')->name('prompts.store');
    Route::post('prompts/edit/{id?}', 'PromptController@postCreateEditPrompt')->name('prompts.update');
    Route::post('prompts/delete/{id}', 'PromptController@postDeletePrompt')->name('prompts.destroy');
});

// PAGES
Route::group(['prefix' => 'pages', 'as' => 'admin.pages.', 'middleware' => 'power:edit_pages'], function () {
    Route::get('/', 'PageController@getIndex')->name('index');
    Route::get('create', 'PageController@getCreatePage')->name('create');
    Route::get('edit/{id}', 'PageController@getEditPage')->name('edit');
    Route::get('delete/{id}', 'PageController@getDeletePage')->name('delete');
    Route::post('create', 'PageController@postCreateEditPage')->name('store');
    Route::post('edit/{id?}', 'PageController@postCreateEditPage')->name('update');
    Route::post('delete/{id}', 'PageController@postDeletePage')->name('destroy');
});

// NEWS
Route::group(['prefix' => 'news', 'as' => 'admin.news.', 'middleware' => 'power:manage_news'], function () {
    Route::get('/', 'NewsController@getIndex')->name('index');
    Route::get('create', 'NewsController@getCreateNews')->name('create');
    Route::get('edit/{id}', 'NewsController@getEditNews')->name('edit');
    Route::get('delete/{id}', 'NewsController@getDeleteNews')->name('delete');
    Route::post('create', 'NewsController@postCreateEditNews')->name('store');
    Route::post('edit/{id?}', 'NewsController@postCreateEditNews')->name('update');
    Route::post('delete/{id}', 'NewsController@postDeleteNews')->name('destroy');
});

// SALES
Route::group(['prefix' => 'sales', 'as' => 'admin.sales.', 'middleware' => 'power:manage_sales'], function () {
    Route::get('/', 'SalesController@getIndex')->name('index');
    Route::get('create', 'SalesController@getCreateSales')->name('create');
    Route::get('edit/{id}', 'SalesController@getEditSales')->name('edit');
    Route::get('delete/{id}', 'SalesController@getDeleteSales')->name('delete');
    Route::post('create', 'SalesController@postCreateEditSales')->name('store');
    Route::post('edit/{id?}', 'SalesController@postCreateEditSales')->name('update');
    Route::post('delete/{id}', 'SalesController@postDeleteSales')->name('destroy');

    Route::get('character/{slug}', 'SalesController@getCharacterInfo')->name('character.info');
});

// SITE SETTINGS
Route::group(['prefix' => 'settings', 'as' => 'admin.settings.', 'middleware' => 'power:edit_site_settings'], function () {
    Route::get('/', 'SettingsController@getIndex')->name('index');
    Route::post('{key}', 'SettingsController@postEditSetting')->name('update');
});

// GRANTS
Route::group(['prefix' => 'grants', 'as' => 'admin.grants.', 'namespace' => 'Users', 'middleware' => 'power:edit_inventories'], function () {
    Route::get('user-currency', 'GrantController@getUserCurrency')->name('user-currency');
    Route::post('user-currency', 'GrantController@postUserCurrency')->name('user-currency.store');

    Route::get('items', 'GrantController@getItems')->name('items');
    Route::post('items', 'GrantController@postItems')->name('items.store');

    Route::get('item-search', 'GrantController@getItemSearch')->name('item-search');
});

// MASTERLIST
Route::group(['prefix' => 'masterlist', 'as' => 'admin.masterlist.', 'namespace' => 'Characters', 'middleware' => 'power:manage_characters'], function () {
    Route::get('create-character', 'CharacterController@getCreateCharacter')->name('character.create');
    Route::post('create-character', 'CharacterController@postCreateCharacter')->name('character.store');

    Route::get('get-number', 'CharacterController@getPullNumber')->name('get-number');

    Route::get('transfers/{type}', 'CharacterController@getTransferQueue')->name('transfers.index');
    Route::get('transfer/{id}', 'CharacterController@getTransferInfo')->name('transfer.show');
    Route::get('transfer/act/{id}/{type}', 'CharacterController@getTransferModal')->name('transfer.act');
    Route::post('transfer/{id}', 'CharacterController@postTransferQueue')->name('transfer.update');

    Route::get('trades/{type}', 'CharacterController@getTradeQueue')->name('trades.index');
    Route::get('trade/{id}', 'CharacterController@getTradeInfo')->name('trade.show');
    Route::get('trade/act/{id}/{type}', 'CharacterController@getTradeModal')->name('trade.act');
    Route::post('trade/{id}', 'CharacterController@postTradeQueue')->name('trade.update');

    Route::get('create-myo', 'CharacterController@getCreateMyo')->name('myo.create');
    Route::post('create-myo', 'CharacterController@postCreateMyo')->name('myo.store');

    Route::get('check-subtype', 'CharacterController@getCreateCharacterMyoSubtype')->name('check-subtype');
});
Route::group(['prefix' => 'character', 'as' => 'admin.character.grants.', 'namespace' => 'Characters', 'middleware' => 'power:edit_inventories'], function () {
    Route::post('{slug}/grant', 'GrantController@postCharacterCurrency')->name('currency');
    Route::post('{slug}/grant-items', 'GrantController@postCharacterItems')->name('items');
});
Route::group(['prefix' => 'character', 'as' => 'admin.character.', 'namespace' => 'Characters', 'middleware' => 'power:manage_characters'], function () {
    // IMAGES
    Route::get('{slug}/image', 'CharacterImageController@getNewImage')->name('image.create');
    Route::post('{slug}/image', 'CharacterImageController@postNewImage')->name('image.store');
    Route::get('image/subtype', 'CharacterImageController@getNewImageSubtype')->name('image.subtype');

    Route::get('image/{id}/traits', 'CharacterImageController@getEditImageFeatures')->name('image.traits.edit');
    Route::post('image/{id}/traits', 'CharacterImageController@postEditImageFeatures')->name('image.traits.update');
    Route::get('image/traits/subtype', 'CharacterImageController@getEditImageSubtype')->name('image.traits.subtype');

    Route::get('image/{id}/notes', 'CharacterImageController@getEditImageNotes')->name('image.notes.edit');
    Route::post('image/{id}/notes', 'CharacterImageController@postEditImageNotes')->name('image.notes.update');

    Route::get('image/{id}/credits', 'CharacterImageController@getEditImageCredits')->name('image.credits.edit');
    Route::post('image/{id}/credits', 'CharacterImageController@postEditImageCredits')->name('image.credits.update');

    Route::get('image/{id}/reupload', 'CharacterImageController@getImageReupload')->name('image.reupload');
    Route::post('image/{id}/reupload', 'CharacterImageController@postImageReupload')->name('image.reupload.store');

    Route::post('image/{id}/settings', 'CharacterImageController@postImageSettings')->name('image.settings.update');

    Route::get('image/{id}/active', 'CharacterImageController@getImageActive')->name('image.active');
    Route::post('image/{id}/active', 'CharacterImageController@postImageActive')->name('image.active.store');

    Route::get('image/{id}/delete', 'CharacterImageController@getImageDelete')->name('image.delete');
    Route::post('image/{id}/delete', 'CharacterImageController@postImageDelete')->name('image.destroy');

    Route::post('{slug}/images/sort', 'CharacterImageController@postSortImages')->name('images.sort.save');

    // CHARACTER
    Route::get('{slug}/stats', 'CharacterController@getEditCharacterStats')->name('stats.edit');
    Route::post('{slug}/stats', 'CharacterController@postEditCharacterStats')->name('stats.update');

    Route::get('{slug}/description', 'CharacterController@getEditCharacterDescription')->name('description.edit');
    Route::post('{slug}/description', 'CharacterController@postEditCharacterDescription')->name('description.update');

    Route::get('{slug}/profile', 'CharacterController@getEditCharacterProfile')->name('profile.edit');
    Route::post('{slug}/profile', 'CharacterController@postEditCharacterProfile')->name('profile.update');

    Route::get('{slug}/delete', 'CharacterController@getCharacterDelete')->name('delete');
    Route::post('{slug}/delete', 'CharacterController@postCharacterDelete')->name('destroy');

    Route::post('{slug}/settings', 'CharacterController@postCharacterSettings')->name('settings.update');

    Route::post('{slug}/transfer', 'CharacterController@postTransfer')->name('transfer.store');
});
// Might rewrite these parts eventually so there's less code duplication...
Route::group(['prefix' => 'myo', 'as' => 'admin.myo.', 'namespace' => 'Characters', 'middleware' => 'power:manage_characters'], function () {
    // CHARACTER
    Route::get('{id}/stats', 'CharacterController@getEditMyoStats')->name('stats.edit');
    Route::post('{id}/stats', 'CharacterController@postEditMyoStats')->name('stats.update');

    Route::get('{id}/description', 'CharacterController@getEditMyoDescription')->name('description.edit');
    Route::post('{id}/description', 'CharacterController@postEditMyoDescription')->name('description.update');

    Route::get('{id}/profile', 'CharacterController@getEditMyoProfile')->name('profile.edit');
    Route::post('{id}/profile', 'CharacterController@postEditMyoProfile')->name('profile.update');

    Route::get('{id}/delete', 'CharacterController@getMyoDelete')->name('delete');
    Route::post('{id}/delete', 'CharacterController@postMyoDelete')->name('destroy');

    Route::post('{id}/settings', 'CharacterController@postMyoSettings')->name('settings.update');

    Route::post('{id}/transfer', 'CharacterController@postMyoTransfer')->name('transfer.store');
});

// RAFFLES
Route::group(['prefix' => 'raffles', 'as' => 'admin.raffles.', 'middleware' => 'power:manage_raffles'], function () {
    Route::get('/', 'RaffleController@getRaffleIndex')->name('index');
    Route::get('edit/group/{id?}', 'RaffleController@getCreateEditRaffleGroup')->name('group.edit');
    Route::post('edit/group/{id?}', 'RaffleController@postCreateEditRaffleGroup')->name('group.update');
    Route::get('edit/raffle/{id?}', 'RaffleController@getCreateEditRaffle')->name('edit');
    Route::post('edit/raffle/{id?}', 'RaffleController@postCreateEditRaffle')->name('update');

    Route::get('view/{id}', 'RaffleController@getRaffleTickets')->name('tickets.index');
    Route::post('view/ticket/{id}', 'RaffleController@postCreateRaffleTickets')->name('tickets.store');
    Route::post('view/ticket/delete/{id}', 'RaffleController@postDeleteRaffleTicket')->name('tickets.destroy');

    Route::get('roll/raffle/{id}', 'RaffleController@getRollRaffle')->name('roll');
    Route::post('roll/raffle/{id}', 'RaffleController@postRollRaffle')->name('roll.store');
    Route::get('roll/group/{id}', 'RaffleController@getRollRaffleGroup')->name('group.roll');
    Route::post('roll/group/{id}', 'RaffleController@postRollRaffleGroup')->name('group.roll.store');
});

// SUBMISSIONS
Route::group(['prefix' => 'submissions', 'as' => 'admin.submissions.', 'middleware' => 'power:manage_submissions'], function () {
    Route::get('/', 'SubmissionController@getSubmissionIndex')->name('index');
    Route::get('/{status}', 'SubmissionController@getSubmissionIndex')->where('status', 'pending|approved|rejected')->name('index.status');
    Route::get('edit/{id}', 'SubmissionController@getSubmission')->name('edit');
    Route::post('edit/{id}/{action}', 'SubmissionController@postSubmission')->where('action', 'approve|reject|cancel')->name('update');
});

// CLAIMS
Route::group(['prefix' => 'claims', 'as' => 'admin.claims.', 'middleware' => 'power:manage_submissions'], function () {
    Route::get('/', 'SubmissionController@getClaimIndex')->name('index');
    Route::get('/{status}', 'SubmissionController@getClaimIndex')->where('status', 'pending|approved|rejected')->name('index.status');
    Route::get('edit/{id}', 'SubmissionController@getClaim')->name('edit');
    Route::post('edit/{id}/{action}', 'SubmissionController@postSubmission')->where('action', 'approve|reject|cancel')->name('update');
});

// SUBMISSIONS
Route::group(['prefix' => 'gallery', 'as' => 'admin.gallery.', 'middleware' => 'power:manage_submissions'], function () {
    Route::get('/submissions', 'GalleryController@getSubmissionIndex')->name('submissions.index');
    Route::get('/submissions/{status}', 'GalleryController@getSubmissionIndex')->where('status', 'pending|accepted|rejected')->name('submissions.index.status');
    Route::get('/currency', 'GalleryController@getCurrencyIndex')->name('currency.index');
    Route::get('/currency/{status}', 'GalleryController@getCurrencyIndex')->where('status', 'pending|valued')->name('currency.index.status');
    Route::post('edit/{id}/{action}', 'GalleryController@postEditSubmission')->where('action', 'accept|reject|comment|move|value')->name('submissions.update');
});

// REPORTS
Route::group(['prefix' => 'reports', 'as' => 'admin.reports.', 'middleware' => 'power:manage_reports'], function () {
    Route::get('/', 'ReportController@getReportIndex')->name('index');
    Route::get('/{status}', 'ReportController@getReportIndex')->where('status', 'pending|assigned|assigned-to-me|closed')->name('index.status');
    Route::get('edit/{id}', 'ReportController@getReport')->name('edit');
    Route::post('edit/{id}/{action}', 'ReportController@postReport')->where('action', 'assign|close')->name('update');
});

// DESIGN APPROVALS
Route::group(['prefix' => 'designs', 'as' => 'admin.designs.', 'middleware' => 'power:manage_characters'], function () {
    Route::get('edit/{id}/{action}', 'DesignController@getDesignConfirmation')->where('action', 'cancel|approve|reject')->name('edit');
    Route::post('edit/{id}/{action}', 'DesignController@postDesign')->where('action', 'cancel|approve|reject')->name('update');
    Route::post('vote/{id}/{action}', 'DesignController@postVote')->where('action', 'approve|reject')->name('vote');
});
Route::get('{type}/{status}', 'DesignController@getDesignIndex')->where('type', 'myo-approvals|design-approvals')->where('status', 'pending|approved|rejected')->name('admin.designs.index');
