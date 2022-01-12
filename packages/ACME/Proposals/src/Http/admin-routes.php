<?php

Route::group([
        'prefix'        => 'admin/proposals',
        'middleware'    => ['web', 'admin']
    ], function () {

        Route::get('', 'ACME\Proposals\Http\Controllers\Admin\ProposalsController@index')->defaults('_config', [
            'view' => 'proposals::admin.index',
        ])->name('admin.proposals.index');

        Route::get('/create', 'ACME\Proposals\Http\Controllers\Admin\ProposalsController@index')->defaults('_config', [
            'view' => 'proposals::admin.new-client',
        ])->name('admin.proposals.new-client');

     

});

