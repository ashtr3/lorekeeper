<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Extensions
    |--------------------------------------------------------------------------
    |
    | This enables/disables a selection of extensions which provide QoL and are
    | broadly applicable, but perhaps not universally, and which are contained
    | in scope enough to be readily opt-in.
    |
    | Extensions with a single value for their setting are enabled/disabled via it
    | and have no additional configuration necessary here. 0 = disabled, 1 = enabled.
    | All of the extensions here are disabled by default.
    |
    | Please refer to the readme for more information on each of these extensions.
    |
    */

    // Navbar News Notif - Juni
    'navbar_news_notif' => env('EXT_NAVBAR_NEWS_NOTIF', 0),

    // Species Trait Index - Mercury
    'species_trait_index' => [
        'enable'       => env('EXT_SPECIES_TRAIT_INDEX', 0),
        'trait_modals' => env('EXT_SPECIES_TRAIT_INDEX_MODALS', 0), // Enables modals when you click on a trait for more info instead of linking to the traits page - Moif
    ],

    // Character Status Badges - Juni
    'character_status_badges' => env('EXT_CHARACTER_STATUS_BADGES', 0),

    // Character TH Profile Link - Juni
    'character_TH_profile_link' => env('EXT_CHARACTER_TH_PROFILE_LINK', 0),

    // Design Update Voting - Mercury
    'design_update_voting' => env('EXT_DESIGN_UPDATE_VOTING', 0),

    // Item Entry Expansion - Mercury
    'item_entry_expansion' => [
        'extra_fields'    => env('EXT_ITEM_ENTRY_EXPANSION_EXTRA_FIELDS', 0),
        'resale_function' => env('EXT_ITEM_ENTRY_EXPANSION_RESALE', 0),
        'loot_tables'     => [
            // Adds the ability to use either rarity criteria for items or item categories with rarity criteria in loot tables. Note that disabling this does not apply retroactively.
            'enable'              => env('EXT_ITEM_ENTRY_EXPANSION_LOOT_TABLES', 0),
            'alternate_filtering' => env('EXT_ITEM_ENTRY_EXPANSION_LOOT_TABLES_ALT_FILTER', 0), // By default this uses more broadly compatible methods to filter by rarity. If you are on Dreamhost/know your DB software can handle searching in JSON, it's recommended to set this to 1 instead.
        ],
    ],

    // Group Traits By Category - Uri
    'traits_by_category' => env('EXT_TRAITS_BY_CATEGORY', 0),

    // Scroll To Top - Uri
    'scroll_to_top' => env('EXT_SCROLL_TO_TOP', 0), // 1 - On, 0 - off

    // Character Reward Expansion - Uri
    'character_reward_expansion' => [
        'expanded'          => env('EXT_CHARACTER_REWARD_EXPANSION', 1),
        'default_recipient' => env('EXT_CHARACTER_REWARD_EXPANSION_RECIPIENT', 0), // 0 to default to the character's owner (if a user), 1 to default to the submission user.
    ],

    // MYO Image Hide/Remove - Mercury
    // Adds an option when approving MYO submissions to hide or delete the MYO placeholder image
    'remove_myo_image' => env('EXT_REMOVE_MYO_IMAGE', 0),

    // Auto-populate New Image Traits - Mercury
    // Automatically adds the traits present on a character's active image to the list when uploading a new image for an extant character.
    'autopopulate_image_features' => env('EXT_AUTOPOP_IMAGE_FEATURES', 0),

    // Staff Rewards - Mercury
    'staff_rewards' => [
        'enabled'     => env('EXT_STAFF_REWARDS', 0),
        'currency_id' => env('EXT_STAFF_REWARDS_CURRENCY', 1),
    ],

    // Organised Traits Dropdown - Draginraptor
    'organised_traits_dropdown' => env('EXT_ORGANISED_TRAITS', 0),

    // Previous & Next buttons on Character pages - Speedy
    // Adds buttons linking to the previous character as well as the next character on all character pages.
    'previous_and_next_characters' => [
        'display' => env('EXT_PREV_NEXT_CHARACTERS', 0),
        'reverse' => env('EXT_PREV_NEXT_CHARACTERS_REVERSE', 0), // By default, 0 has the lower number on the 'Next' side and the higher number on the 'Previous' side, reflecting the default masterlist order. Setting this to 1 reverses this.
    ],

    // Aliases on Userpage - Speedy
    'aliases_on_userpage' => env('EXT_ALIASES_ON_USERPAGE', 0), // By default, does not display the aliases on userpage. Enable to add a small arrow to display these underneath the primary alias.

    // Show All Recent Submissions - Speedy
    'show_all_recent_submissions' => [
        'enable' => env('EXT_SHOW_RECENT_SUBMISSIONS', 0),
        'links'  => [
            'sidebar'      => env('EXT_SHOW_RECENT_SUBMISSIONS_SIDEBAR', 1),      // By default, ON, and will display in the sidebar.
            'indexbutton'  => env('EXT_SHOW_RECENT_SUBMISSIONS_INDEX', 1), // By default, ON, and will display a button on the index.
        ],
        'section_on_front' => env('EXT_SHOW_RECENT_SUBMISSIONS_HOME', 0), // By default, does not display on the front page. Enable to add a block above the footer.
    ],

    // collapsible admin sidebar - Newt
    'collapsible_admin_sidebar' => env('EXT_COLLAPSE_ADMIN_SIDEBAR', 0),

    // use gravatar for user avatars - Newt
    'use_gravatar' => env('EXT_USE_GRAVATAR', 0),

    // Use ReCaptcha to check new user registrations - Mercury
    // Requires site key and secret be set in your .env file!
    'use_recaptcha' => env('EXT_USE_RECAPTCHA', 0),
];
