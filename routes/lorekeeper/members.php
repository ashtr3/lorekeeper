<?php

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
|
| Routes for logged in users with a linked dA account.
|
*/

/**************************************************************************************************
    Users
**************************************************************************************************/

Route::group(['prefix' => 'notifications', 'as' => 'notifications.', 'namespace' => 'Users'], function () {
    Route::get('/', 'AccountController@getNotifications')->name('index');
    Route::get('delete/{id}', 'AccountController@getDeleteNotification')->name('delete');
    Route::post('clear', 'AccountController@postClearNotifications')->name('clear');
    Route::post('clear/{type}', 'AccountController@postClearNotifications')->name('clear.type');
});

Route::group(['prefix' => 'account', 'as' => 'account.', 'namespace' => 'Users'], function () {
    Route::get('settings', 'AccountController@getSettings')->name('settings');
    Route::post('profile', 'AccountController@postProfile')->name('profile.update');
    Route::post('password', 'AccountController@postPassword')->name('password.update');
    Route::post('email', 'AccountController@postEmail')->name('email.update');
    Route::post('avatar', 'AccountController@postAvatar')->name('avatar.update');
    Route::post('username', 'AccountController@postUsername')->name('username.update');
    Route::get('aliases', 'AccountController@getAliases')->name('aliases');
    Route::get('make-primary/{id}', 'AccountController@getMakePrimary')->name('aliases.make-primary');
    Route::post('make-primary/{id}', 'AccountController@postMakePrimary')->name('aliases.make-primary.store');
    Route::get('hide-alias/{id}', 'AccountController@getHideAlias')->name('aliases.hide');
    Route::post('hide-alias/{id}', 'AccountController@postHideAlias')->name('aliases.hide.store');
    Route::get('remove-alias/{id}', 'AccountController@getRemoveAlias')->name('aliases.remove');
    Route::post('remove-alias/{id}', 'AccountController@postRemoveAlias')->name('aliases.remove.store');
    Route::post('dob', 'AccountController@postBirthday')->name('birthday.update');

    Route::get('two-factor/confirm', 'AccountController@getConfirmTwoFactor')->name('two-factor.confirm');
    Route::post('two-factor/enable', 'AccountController@postEnableTwoFactor')->name('two-factor.enable');
    Route::post('two-factor/confirm', 'AccountController@postConfirmTwoFactor')->name('two-factor.confirm.store');
    Route::post('two-factor/disable', 'AccountController@postDisableTwoFactor')->name('two-factor.disable');

    Route::get('deactivate', 'AccountController@getDeactivate')->name('deactivate');
    Route::get('deactivate-confirm', 'AccountController@getDeactivateConfirmation')->name('deactivate.confirm');
    Route::post('deactivate', 'AccountController@postDeactivate')->name('deactivate.store');

    Route::get('bookmarks', 'BookmarkController@getBookmarks')->name('bookmarks.index');
    Route::get('bookmarks/create', 'BookmarkController@getCreateBookmark')->name('bookmarks.create');
    Route::get('bookmarks/edit/{id}', 'BookmarkController@getEditBookmark')->name('bookmarks.edit');
    Route::post('bookmarks/create', 'BookmarkController@postCreateEditBookmark')->name('bookmarks.store');
    Route::post('bookmarks/edit/{id}', 'BookmarkController@postCreateEditBookmark')->name('bookmarks.update');
    Route::get('bookmarks/delete/{id}', 'BookmarkController@getDeleteBookmark')->name('bookmarks.delete');
    Route::post('bookmarks/delete/{id}', 'BookmarkController@postDeleteBookmark')->name('bookmarks.destroy');
});

Route::group(['prefix' => 'inventory', 'as' => 'inventory.', 'namespace' => 'Users'], function () {
    Route::get('/', 'InventoryController@getIndex')->name('index');
    Route::post('edit', 'InventoryController@postEdit')->name('edit');
    Route::get('account-search', 'InventoryController@getAccountSearch')->name('account-search');
    Route::get('full-inventory', 'InventoryController@getFullInventory')->name('full');
    Route::get('consolidate-inventory', 'InventoryController@getConsolidateInventory')->name('consolidate');
    Route::post('consolidate', 'InventoryController@postConsolidateInventory')->name('consolidate.store');
    Route::get('selector', 'InventoryController@getSelector')->name('selector');
});

Route::group(['prefix' => 'characters', 'as' => 'characters.', 'namespace' => 'Users'], function () {
    Route::get('/', 'CharacterController@getIndex')->name('index');
    Route::post('sort', 'CharacterController@postSortCharacters')->name('sort');

    Route::get('transfers/{type}', 'CharacterController@getTransfers')->name('transfers.index');
    Route::post('transfer/act/{id}', 'CharacterController@postHandleTransfer')->name('transfers.act');

    Route::get('myos', 'CharacterController@getMyos')->name('myos');
});

Route::group(['prefix' => 'bank', 'as' => 'bank.', 'namespace' => 'Users'], function () {
    Route::get('/', 'BankController@getIndex')->name('index');
    Route::post('transfer', 'BankController@postTransfer')->name('transfer');
});

Route::group(['prefix' => 'trades', 'as' => 'trades.', 'namespace' => 'Users'], function () {
    Route::get('{status}', 'TradeController@getIndex')->where('status', 'open|pending|completed|rejected|canceled')->name('index');
    Route::get('create', 'TradeController@getCreateTrade')->name('create');
    Route::get('{id}/edit', 'TradeController@getEditTrade')->where('id', '[0-9]+')->name('edit');
    Route::post('create', 'TradeController@postCreateTrade')->name('store');
    Route::post('{id}/edit', 'TradeController@postEditTrade')->where('id', '[0-9]+')->name('update');
    Route::get('{id}', 'TradeController@getTrade')->where('id', '[0-9]+')->name('show');

    Route::get('{id}/confirm-offer', 'TradeController@getConfirmOffer')->name('confirm-offer');
    Route::post('{id}/confirm-offer', 'TradeController@postConfirmOffer')->name('confirm-offer.store');
    Route::get('{id}/confirm-trade', 'TradeController@getConfirmTrade')->name('confirm-trade');
    Route::post('{id}/confirm-trade', 'TradeController@postConfirmTrade')->name('confirm-trade.store');
    Route::get('{id}/cancel-trade', 'TradeController@getCancelTrade')->name('cancel-trade');
    Route::post('{id}/cancel-trade', 'TradeController@postCancelTrade')->name('cancel-trade.store');
});

/**************************************************************************************************
    Characters
**************************************************************************************************/
Route::group(['prefix' => 'character', 'as' => 'character.', 'namespace' => 'Characters'], function () {
    Route::get('{slug}/profile/edit', 'CharacterController@getEditCharacterProfile')->name('profile.edit');
    Route::post('{slug}/profile/edit', 'CharacterController@postEditCharacterProfile')->name('profile.update');

    Route::post('{slug}/inventory/edit', 'CharacterController@postInventoryEdit')->name('inventory.update');

    Route::post('{slug}/bank/transfer', 'CharacterController@postCurrencyTransfer')->name('bank.transfer');
    Route::get('{slug}/transfer', 'CharacterController@getTransfer')->name('transfer');
    Route::post('{slug}/transfer', 'CharacterController@postTransfer')->name('transfer.store');
    Route::post('{slug}/transfer/{id}/cancel', 'CharacterController@postCancelTransfer')->name('transfer.cancel');

    Route::post('{slug}/approval', 'CharacterController@postCharacterApproval')->name('approval.store');
    Route::get('{slug}/approval', 'CharacterController@getCharacterApproval')->name('approval');
});
Route::group(['prefix' => 'myo', 'as' => 'myo.', 'namespace' => 'Characters'], function () {
    Route::get('{id}/profile/edit', 'MyoController@getEditCharacterProfile')->name('profile.edit');
    Route::post('{id}/profile/edit', 'MyoController@postEditCharacterProfile')->name('profile.update');

    Route::get('{id}/transfer', 'MyoController@getTransfer')->name('transfer');
    Route::post('{id}/transfer', 'MyoController@postTransfer')->name('transfer.store');
    Route::post('{id}/transfer/{id2}/cancel', 'MyoController@postCancelTransfer')->name('transfer.cancel');

    Route::post('{id}/approval', 'MyoController@postCharacterApproval')->name('approval.store');
    Route::get('{id}/approval', 'MyoController@getCharacterApproval')->name('approval');
});

/**************************************************************************************************
    Submissions
**************************************************************************************************/

Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function () {
    Route::get('submissions/{type}', 'GalleryController@getUserSubmissions')->where('type', 'draft|pending|accepted|rejected')->name('submissions.index');

    Route::post('favorite/{id}', 'GalleryController@postFavoriteSubmission')->name('favorite');

    Route::get('submit/{id}', 'GalleryController@getNewGallerySubmission')->name('submit');
    Route::get('submit/character/{slug}', 'GalleryController@getCharacterInfo')->name('submit.character');
    Route::get('edit/{id}', 'GalleryController@getEditGallerySubmission')->name('edit');
    Route::get('queue/{id}', 'GalleryController@getSubmissionLog')->name('queue');
    Route::post('submit', 'GalleryController@postCreateEditGallerySubmission')->name('store');
    Route::post('edit/{id}', 'GalleryController@postCreateEditGallerySubmission')->name('update');

    Route::post('collaborator/{id}', 'GalleryController@postEditCollaborator')->name('collaborator.update');

    Route::get('archive/{id}', 'GalleryController@getArchiveSubmission')->name('archive');
    Route::post('archive/{id}', 'GalleryController@postArchiveSubmission')->name('archive.store');
});

Route::group(['prefix' => 'submissions', 'as' => 'submissions.', 'namespace' => 'Users'], function () {
    Route::get('/', 'SubmissionController@getIndex')->name('index');
    Route::get('new', 'SubmissionController@getNewSubmission')->name('create');
    Route::get('new/character/{slug}', 'SubmissionController@getCharacterInfo')->name('new.character');
    Route::get('new/prompt/{id}', 'SubmissionController@getPromptInfo')->name('new.prompt');
    Route::post('new', 'SubmissionController@postNewSubmission')->name('store');
    Route::post('new/{draft}', 'SubmissionController@postNewSubmission')->where('draft', 'draft')->name('store.draft');
    Route::get('draft/{id}', 'SubmissionController@getEditSubmission')->name('draft.edit');
    Route::post('draft/{id}', 'SubmissionController@postEditSubmission')->name('draft.update');
    Route::post('draft/{id}/{submit}', 'SubmissionController@postEditSubmission')->where('submit', 'submit')->name('draft.submit');
    Route::post('draft/{id}/delete', 'SubmissionController@postDeleteSubmission')->name('draft.delete');
    Route::post('draft/{id}/cancel', 'SubmissionController@postCancelSubmission')->name('draft.cancel');
});

Route::group(['prefix' => 'claims', 'as' => 'claims.', 'namespace' => 'Users'], function () {
    Route::get('/', 'SubmissionController@getClaimsIndex')->name('index');
    Route::get('new', 'SubmissionController@getNewClaim')->name('create');
    Route::post('new', 'SubmissionController@postNewClaim')->name('store');
    Route::post('new/{draft}', 'SubmissionController@postNewClaim')->where('draft', 'draft')->name('store.draft');
    Route::get('draft/{id}', 'SubmissionController@getEditClaim')->name('draft.edit');
    Route::post('draft/{id}', 'SubmissionController@postEditClaim')->name('draft.update');
    Route::post('draft/{id}/{submit}', 'SubmissionController@postEditClaim')->where('submit', 'submit')->name('draft.submit');
    Route::post('draft/{id}/delete', 'SubmissionController@postDeleteClaim')->name('draft.delete');
    Route::post('draft/{id}/cancel', 'SubmissionController@postCancelClaim')->name('draft.cancel');
});

Route::group(['prefix' => 'reports', 'as' => 'reports.', 'namespace' => 'Users'], function () {
    Route::get('/', 'ReportController@getReportsIndex')->name('index');
    Route::get('new', 'ReportController@getNewReport')->name('create');
    Route::post('new', 'ReportController@postNewReport')->name('store');
    Route::get('view/{id}', 'ReportController@getReport')->name('show');
});

Route::group(['prefix' => 'designs', 'as' => 'designs.', 'namespace' => 'Characters'], function () {
    Route::get('{type?}', 'DesignController@getDesignUpdateIndex')->where('type', 'draft|pending|approved|rejected')->name('index');
    Route::get('{id}', 'DesignController@getDesignUpdate')->name('show');

    Route::get('{id}/comments', 'DesignController@getComments')->name('comments');
    Route::post('{id}/comments', 'DesignController@postComments')->name('comments.store');

    Route::get('{id}/image', 'DesignController@getImage')->name('image');
    Route::post('{id}/image', 'DesignController@postImage')->name('image.store');

    Route::get('{id}/addons', 'DesignController@getAddons')->name('addons');
    Route::post('{id}/addons', 'DesignController@postAddons')->name('addons.store');

    Route::get('{id}/traits', 'DesignController@getFeatures')->name('traits');
    Route::post('{id}/traits', 'DesignController@postFeatures')->name('traits.store');
    Route::get('traits/subtype', 'DesignController@getFeaturesSubtype')->name('traits.subtype');

    Route::get('{id}/confirm', 'DesignController@getConfirm')->name('confirm');
    Route::post('{id}/submit', 'DesignController@postSubmit')->name('submit');

    Route::get('{id}/delete', 'DesignController@getDelete')->name('delete');
    Route::post('{id}/delete', 'DesignController@postDelete')->name('destroy');
});

/**************************************************************************************************
    Shops
**************************************************************************************************/

Route::group(['prefix' => 'shops', 'as' => 'shops.'], function () {
    Route::post('buy', 'ShopController@postBuy')->name('buy');
    Route::get('history', 'ShopController@getPurchaseHistory')->name('history');
});

/**************************************************************************************************
    Comments
**************************************************************************************************/
Route::group(['prefix' => 'comments', 'as' => 'comments.', 'namespace' => 'Comments'], function () {
    Route::post('make/{model}/{id}', 'CommentController@store')->name('store');
    Route::delete('/{comment}', 'CommentController@destroy')->name('destroy');
    Route::post('edit/{comment}', 'CommentController@update')->name('update');
    Route::post('/{comment}', 'CommentController@reply')->name('reply');
    Route::post('/{id}/feature', 'CommentController@feature')->name('feature');
    Route::post('/{id}/like/{action}', 'CommentController@like')->name('like');
    Route::get('/liked', 'CommentController@getLikedComments')->name('liked');
});
