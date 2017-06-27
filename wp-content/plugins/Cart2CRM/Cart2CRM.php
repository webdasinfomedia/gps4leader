<?php
/*
Plugin Name: Cart2CRM
Plugin URI: http://www.mojoomla.com/
Description: Provide the ability to connect and synchronize customer data and order data between a WordPress (using the WooCommerce plugin) to SugarCRM and SuiteCRM account.
Version: 7.0
Author: Mojoomla
Author URI: http://www.mojoomla.com
*/
define( 'CRM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( 'CRM_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

define( 'CRM_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define('WP_CART2CRM_DB_VERSION', '1.0.0'); 
define('CART2CRM_VERSION', '2.0');  
//define( 'CRM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );       
require_once CRM_PLUGIN_DIR . '/class-cart2crm.php';       
require_once CRM_PLUGIN_DIR . '/cart2crm-function.php';

if(is_admin())
{
	// Specific WP actions...
	register_activation_hook(CRM_PLUGIN_BASENAME, 'activate');
	//register_uninstall_hook(__FILE__,  'uninstall');
	add_action('admin_init','Cart2CRM_setting_option');
	require_once CRM_PLUGIN_DIR . '/admin/admin.php';
	
}

function cart2crm_script()
{
	wp_enqueue_style( 'cart2crm-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
	wp_enqueue_script('cart2crm-jquery', plugins_url( '/assets/js/cart2crm-admin.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	wp_localize_script( 'cart2crm-jquery', 'cart2crm', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_style('cart2crm-datrepicker-css', plugins_url( '/assets/css/jquery-ui.css', __FILE__));
	
	wp_enqueue_script('jquery-ui-datepicker');
	
	
}
add_action( 'admin_enqueue_scripts', 'cart2crm_script' );

/*
//On plugin activation schedule our daily database backup
//register_activation_hook( __FILE__, 'wi_create_daily_backup_schedule' );
function wi_create_daily_backup_schedule(){
	//Use wp_next_scheduled to check if the event is already scheduled
	

	//If $timestamp == false schedule daily backups since it hasn't been done previously
	
		//Schedule the event for right now, then to repeat daily using the hook 'wi_create_daily_backup'
		wp_schedule_event( time(), '1min', 'wi_create_daily_backup' );
	
}

//Hook our function , wi_create_backup(), into the action wi_create_daily_backup
add_action( 'wi_create_daily_backup', 'wi_create_backup' );
function wi_create_backup(){
	echo " hello";
}
*/
// add custom interval
function cart2crm_cron_add_minute( $schedules ) {
	// Adds once every minute to the existing schedules.
	$schedules['everyminute'] = array(
			'interval' => 10,
			'display' => __( 'Once Every Minute' )
	);
	return $schedules;
}

add_filter( 'cron_schedules', 'cart2crm_cron_add_minute' );
// create a scheduled event (if it does not exist already)
function cart2crm_cronstarter_activation() {
	$current_crm = get_option('current_active_crm');
	if($current_crm == 'sugarcrm')
	{
		$autosync = get_option('sugarcrm_logininfo');
	}
	if($current_crm == 'suitecrm')
	{	$autosync = get_option('suitecrm_logininfo'); }
	if($current_crm == 'customcrm')
	{	$autosync = get_option('customcrm_logininfo'); }
	//echo $current_crm;
	if(isset($autosync['autosync']) && $autosync['autosync'] == 1)
	{	
		//echo "yes auto sync";
		//if(!wp_next_scheduled('cart2crmcronjob'))
		//{
			
			wp_schedule_event( time(), 'hourly', 'cart2crmcronjob' );
			
		//}
	}
	else 
	{
		//echo "Not auto sync";
		$timestamp = wp_next_scheduled ('cart2crmcronjob');
		// unschedule previous event if any
		//echo $timestamp;
		wp_unschedule_event ($timestamp, 'cart2crmcronjob');
		wp_clear_scheduled_hook('cart2crmcronjob');
	}
	//cart2crm_auto_sync_suitecrm();
	// $cron_jobs = get_option( 'cron' );
	 // var_dump($cron_jobs);
}

// and make sure it's called whenever WordPress loads
add_action('wp', 'cart2crm_cronstarter_activation');

// unschedule event upon plugin deactivation
function cart2crm_cronstarter_deactivate() {
	// find out when the last event was scheduled
	$timestamp = wp_next_scheduled ('cart2crmcronjob');
	// unschedule previous event if any
	wp_unschedule_event ($timestamp, 'cart2crmcronjob');
}
register_deactivation_hook (__FILE__, 'cart2crm_cronstarter_deactivate');

// here's the function we'd like to call with our cron job
function cart2crm_repeat_function() {

	// do here what needs to be done automatically as per your schedule
	// in this example we're sending an email

	// components for our email
	//global $wpdb;
	//$table_name = $wpdb->prefix .'cart2crm_sugar_id';
	//$wpdb->query("insert into  $table_name (id,acid,crmtype) values (1,'1',2)");
	$current_crm = get_option('current_active_crm');
	if($current_crm == 'sugarcrm')
	{
		$autosync = get_option('sugarcrm_logininfo');
		//echo "Do it";
		cart2crm_auto_sync_sugarcrm();
	}
	if($current_crm == 'suitecrm')
	{	
		$autosync = get_option('suitecrm_logininfo');
		cart2crm_auto_sync_suitecrm();
		
	}
	if($current_crm == 'customcrm')
	{	
		$autosync = get_option('customcrm_logininfo'); 
		cart2crm_auto_sync_customcrm();
	}
	
	
}

// hook that function onto our scheduled event:
add_action ('cart2crmcronjob', 'cart2crm_repeat_function');
?>