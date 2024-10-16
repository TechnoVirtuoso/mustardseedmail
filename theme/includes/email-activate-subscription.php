<?php

function processEmails($user_id, $customer)
{
	$subscriptions = wcs_get_subscriptions(
		array(
			'customer_id' => $user_id,
			'status' => 'on-hold',
		)
	);

	if ($subscriptions) {
		foreach ($subscriptions as $subscription) {
			if ($subscription->get_status() === "on-hold") {
				send_custom_email($subscription->get_id(), "Subscription Not Activated", 'get_not_activated_email_html');
			}
		}
	}
}

function process_all_users_subscriptions_activation_email()
{
	$customer_query = new WP_User_Query(
		array(
			'fields' => 'ID',
		)
	);
	$customers_ = $customer_query->get_results();
	foreach ($customers_ as $customer_id) {
		$customer = new WC_Customer($customer_id);
		processEmails($customer_id, $customer);
	}
}
// Add custom interval for 48 hours
function custom_48_hour_interval($schedules) {
    $schedules['every_48_hours'] = array(
        'interval' => 172800, // 48 hours in seconds
        'display' => __('Every 48 hours')
    );
    return $schedules;
}
add_filter('cron_schedules', 'custom_48_hour_interval');

// Schedule the event to run every 48 hours
function cron_job_scheduled_for_subscription_email_activation() {
    if (!wp_next_scheduled('subscription_email_activation_scheduling_hook')) {
        wp_schedule_event(time(), 'every_48_hours', 'subscription_email_activation_scheduling_hook');
    }
}
add_action('wp', 'cron_job_scheduled_for_subscription_email_activation');

add_action('subscription_email_activation_scheduling_hook', 'process_all_users_subscriptions_activation_email');