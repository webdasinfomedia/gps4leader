<?php
add_action( 'admin_menu', 'Cart2CRM_menu' );
function Cart2CRM_menu() {
	add_menu_page( 'Cart2CRM', __('Cart2CRM','cart2crm'), 'manage_options', 'cart2crm', 'cart2crm_home',plugins_url( 'Cart2CRM/assets/images/sugar.png' ));

	add_submenu_page('cart2crm', 'Connection', __( 'Connection', 'cart2crm' ), 'administrator', 'cart2crm', 'cart2crm_home');

	add_submenu_page('cart2crm', 'CRM Panel', __( 'CRM Panel', 'cart2crm' ), 'administrator', 'crm_panel', 'cart2crm_panel');	
	add_submenu_page('cart2crm', 'Field Mapping', __( 'Field Mapping', 'cart2crm' ), 'administrator', 'crm_field_mapping', 'cart2crm_field_mapping');
	add_submenu_page('cart2crm', 'Integration', __( 'Integration', 'cart2crm' ), 'administrator', 'crm_integration', 'cart2crm_integration');
	add_submenu_page('cart2crm', 'Reports', __( 'Reports', 'cart2crm' ), 'administrator', 'crm_report', 'cart2crm_report');

	
	

}

function cart2crm_home()
{
	require_once CRM_PLUGIN_DIR. '/admin/includes/crm-connection.php';
}
function cart2crm_panel()
{
	$current_crm=get_option( 'current_active_crm');
	if($current_crm == "")
	{
		_e("No Any Select CRM","cart2crm");
	}
	elseif($current_crm == 'sugarcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/sugarcrm/crm-panel.php';
	}
	elseif ($current_crm == 'suitecrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/suitecrm/crm-panel.php';
	}
	elseif ($current_crm == 'customcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/customcrm/crm-panel.php';
	}
}
function cart2crm_integration()
{
$current_crm=get_option( 'current_active_crm');
	if($current_crm == "")
	{
		_e("No Any Select CRM","cart2crm");
	}
	elseif($current_crm == 'sugarcrm')
	{
		
		require_once CRM_PLUGIN_DIR.'/admin/sugarcrm/crm-integration.php';
	}
	elseif ($current_crm == 'suitecrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/suitecrm/crm-integration.php';
	}
	elseif ($current_crm == 'customcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/customcrm/crm-integration.php';
	}
}
function cart2crm_field_mapping()
{ 
	$current_crm=get_option( 'current_active_crm');
	if($current_crm == "")
	{
		_e("No Any Select CRM","cart2crm");
	}
	elseif($current_crm == 'sugarcrm')
	{
	
		require_once CRM_PLUGIN_DIR.'/admin/sugarcrm/crm-fieldmapping.php';
	}
	elseif ($current_crm == 'suitecrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/suitecrm/crm-fieldmapping.php';
	}
	elseif ($current_crm == 'customcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/customcrm/crm-fieldmapping.php';
	}
	
}
function cart2crm_report()
{
$current_crm=get_option( 'current_active_crm');
	if($current_crm == "")
	{
		_e("No Any Select CRM","cart2crm");
	}
	elseif($current_crm == 'sugarcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/sugarcrm/crm-report.php';
	}
	elseif ($current_crm == 'suitecrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/suitecrm/crm-report.php';
	}
	elseif ($current_crm == 'customcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/customcrm/crm-report.php';
	}
}
?>