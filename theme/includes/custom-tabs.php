<?php
// ------------------
// Register new endpoint (URL) for Manage Subscriptions page
// Note: Re-save Permalinks or it will give 404 error
// function hs_add_manage_subscription_endpoint()
// {
//     add_rewrite_endpoint('manage-subscription', EP_ROOT | EP_PAGES);
// }

// add_action('init', 'hs_add_manage_subscription_endpoint');
// function hs_manage_subscription_query_vars($vars)
// {
//     $vars[] = 'manage-subscription';
//     return $vars;
// }
// add_filter('query_vars', 'hs_manage_subscription_query_vars', 0);
// function hs_add_manage_subscription_link_my_account($items)
// {
//     $items['manage-subscription'] = 'Manage Subscription';
//     return $items;
// }
// add_filter('woocommerce_account_menu_items', 'hs_add_manage_subscription_link_my_account');

// add_action('woocommerce_account_manage-subscription_endpoint', 'hs_manage_subscription_content');
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format

function reorder_account_menu($items)
{
    return array(
        'dashboard' => __('Manage Subscriptions', 'woocommerce'),
        // 'manage-subscription' => __('Manage Subscriptions', 'woocommerce'),
        // 'orders' => __('Multi-Pack Orders', 'woocommerce'),
        // 'multi-pack-orders' => __('Multi-Pack Orders', 'woocommerce'),
        // 'downloads' => __('Downloads', 'woocommerce'),
        'edit-account' => __('Account Information', 'woocommerce'),
        'edit-address' => __('Q&A', 'woocommerce'),
        'customer-logout' => __('Logout', 'woocommerce'),
    );
}
add_filter('woocommerce_account_menu_items', 'reorder_account_menu');
?>