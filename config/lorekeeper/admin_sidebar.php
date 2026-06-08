<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Sidebar Links
    |--------------------------------------------------------------------------
    |
    | Admin panel sidebar links.
    | Add links here to have them show up in the admin panel.
    | Users that do not have the listed power will not be able to
    | view the links in that section.
    |
    */

    'Admin'      => [
        'power' => 'admin',
        'links' => [
            [
                'name'   => 'User Ranks',
                'route'  => 'admin.users.ranks.index',
                'active' => 'admin.users.ranks.*',
            ],
            [
                'name'   => 'Admin Logs',
                'route'  => 'admin.logs',
            ],
            [
                'name'   => 'Staff Reward Settings',
                'route'  => 'admin.staff-reward-settings',
            ],
        ],
    ],
    'Reports'    => [
        'power' => 'manage_reports',
        'links' => [
            [
                'name'   => 'Report Queue',
                'route'  => 'admin.reports.index.status',
                'active' => 'admin.reports.index.status',
                'params' => ['status' => 'pending'],
            ],
        ],
    ],
    'News' => [
        'power' => 'manage_news',
        'links' => [
            [
                'name'   => 'News',
                'route'  => 'admin.news.index',
            ],
        ],
    ],
    'Sales' => [
        'power' => 'manage_sales',
        'links' => [
            [
                'name'   => 'Sales',
                'route'  => 'admin.sales.index',
            ],
        ],
    ],
    'Pages'       => [
        'power' => 'edit_pages',
        'links' => [
            [
                'name'   => 'Pages',
                'route'  => 'admin.pages.index',
            ],
        ],
    ],
    'Users'      => [
        'power' => 'edit_user_info',
        'links' => [
            [
                'name'   => 'User Index',
                'route'  => 'admin.users.index',
            ],
            [
                'name'   => 'Invitation Keys',
                'route'  => 'admin.invitations.index',
            ],
        ],
    ],
    'Queues'     => [
        'power' => 'manage_submissions',
        'links' => [
            [
                'name'   => 'Gallery Submissions',
                'route'  => 'admin.gallery.submissions.index',
                'active' => 'admin.gallery.submissions.*',
            ],
            [
                'name'   => 'Gallery Currency Awards',
                'route'  => 'admin.gallery.currency.index',
                'active' => 'admin.gallery.currency.*',
            ],
            [
                'name'   => 'Prompt Submissions',
                'route'  => 'admin.submissions.index',
                'active' => 'admin.submissions.*',
            ],
            [
                'name'   => 'Claim Submissions',
                'route'  => 'admin.claims.index',
                'active' => 'admin.claims.*',
            ],
        ],
    ],
    'Grants'     => [
        'power' => 'edit_inventories',
        'links' => [
            [
                'name'   => 'Currency Grants',
                'route'  => 'admin.grants.user-currency',
            ],
            [
                'name'   => 'Item Grants',
                'route'  => 'admin.grants.items',
            ],
        ],
    ],
    'Masterlist' => [
        'power' => 'manage_characters',
        'links' => [
            [
                'name'   => 'Create Character',
                'route'  => 'admin.masterlist.character.create',
            ],
            [
                'name'   => 'Create MYO Slot',
                'route'  => 'admin.masterlist.myo.create',
            ],
            [
                'name'   => 'Character Transfers',
                'route'  => 'admin.masterlist.transfers.index',
                'active' => 'admin.masterlist.transfers.*',
                'params' => ['type' => 'incoming'],
            ],
            [
                'name'   => 'Character Trades',
                'route'  => 'admin.masterlist.trades.index',
                'active' => 'admin.masterlist.trades.*',
                'params' => ['type' => 'incoming'],
            ],
            [
                'name'   => 'Design Updates',
                'route'  => 'admin.designs.index',
                'params' => ['type' => 'design-approvals', 'status' => 'pending'],
            ],
            [
                'name'   => 'MYO Approvals',
                'route'  => 'admin.designs.index',
                'params' => ['type' => 'myo-approvals', 'status' => 'pending'],
            ],
        ],
    ],
    'Data'       => [
        'power' => 'edit_data',
        'links' => [
            [
                'name'   => 'Galleries',
                'route'  => 'admin.data.galleries.index',
                'active' => 'admin.data.galleries.*',
            ],
            [
                'name'   => 'Character Categories',
                'route'  => 'admin.data.character-categories.index',
                'active' => 'admin.data.character-categories.*',
            ],
            [
                'name'   => 'Sub Masterlists',
                'route'  => 'admin.data.sublists.index',
                'active' => 'admin.data.sublists.*',
            ],
            [
                'name'   => 'Rarities',
                'route'  => 'admin.data.rarities.index',
                'active' => 'admin.data.rarities.*',
            ],
            [
                'name'   => 'Species',
                'route'  => 'admin.data.species.index',
                'active' => 'admin.data.species.*',
            ],
            [
                'name'   => 'Subtypes',
                'route'  => 'admin.data.subtypes.index',
                'active' => 'admin.data.subtypes.*',
            ],
            [
                'name'   => 'Traits',
                'route'  => 'admin.data.traits.index',
                'active' => 'admin.data.traits.*',
            ],
            [
                'name'   => 'Shops',
                'route'  => 'admin.data.shops.index',
                'active' => 'admin.data.shops.*',
            ],
            [
                'name'   => 'Currencies',
                'route'  => 'admin.data.currencies.index',
                'active' => 'admin.data.currencies.*',
            ],
            [
                'name'   => 'Prompts',
                'route'  => 'admin.data.prompts.index',
                'active' => 'admin.data.prompts.*',
            ],
            [
                'name'   => 'Loot Tables',
                'route'  => 'admin.data.loot-tables.index',
                'active' => 'admin.data.loot-tables.*',
            ],
            [
                'name'   => 'Items',
                'route'  => 'admin.data.items.index',
                'active' => 'admin.data.items.*',
            ],
        ],
    ],
    'Raffles'    => [
        'power' => 'manage_raffles',
        'links' => [
            [
                'name'   => 'Raffles',
                'route'  => 'admin.raffles.index',
                'active' => 'admin.raffles.*',
            ],
        ],
    ],
    'Settings'   => [
        'power' => 'edit_site_settings',
        'links' => [
            [
                'name'   => 'Site Settings',
                'route'  => 'admin.settings.index',
                'active' => 'admin.settings.*',
            ],
            [
                'name'   => 'Site Images',
                'route'  => 'admin.images.index',
                'active' => 'admin.images.*',
            ],
            [
                'name'   => 'File Manager',
                'route'  => 'admin.files.index',
                'active' => 'admin.files.*',
            ],
        ],
    ],
];
