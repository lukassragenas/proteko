<?php

Route::group([
        'prefix'     => 'proposals',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'ACME\Proposals\Http\Controllers\Shop\ProposalsController@index')->defaults('_config', [
            'view' => 'proposals::shop.index',
        ])->name('shop.proposals.index');

});