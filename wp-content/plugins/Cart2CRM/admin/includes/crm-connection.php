<?php
//crm-connection integration
$current_crm=get_option( 'current_active_crm');

if(isset($_REQUEST['save']))
{
	
	if(isset($_REQUEST['crm_name']) && $_REQUEST['crm_name'] == 'customcrm')
	{
		$custm_crm_option = array();
		$result=get_option( 'customcrm_logininfo');
		$custm_crm_option['crm_url'] = trim($_REQUEST['crm_url']);
		$custm_crm_option['host_name'] = trim($_REQUEST['host_name']);
		$custm_crm_option['user_name'] = $_REQUEST['user_name'];
		$custm_crm_option['password'] = $_REQUEST['password'];
		$custm_crm_option['database_name'] = $_REQUEST['database_name'];
		if(isset($_REQUEST['autosync']))	
			$custm_crm_option['autosync'] = $_REQUEST['autosync'];
		else 
		$custm_crm_option['autosync'] = 0;
		
		//print_r($custm_crm_option);
		$result=update_option( 'customcrm_logininfo',$custm_crm_option );
		
	}
	if(isset($_REQUEST['crm_name']) && $_REQUEST['crm_name'] == 'sugarcrm')
	{
		$sugar_crm_option = array();
		$result=get_option( 'sugarcrm_logininfo');
		$sugar_crm_option['crm_url'] = trim($_REQUEST['crm_url']);
		
		$sugar_crm_option['user_name'] = $_REQUEST['user_name'];
		$sugar_crm_option['password'] = $_REQUEST['password'];
		if(isset($_REQUEST['autosync']))		
			$sugar_crm_option['autosync'] = $_REQUEST['autosync'];
		else 
			$sugar_crm_option['autosync'] = 0;
	
		//print_r($sugar_crm_option);
		$result=update_option( 'sugarcrm_logininfo',$sugar_crm_option );
	
	}
	if(isset($_REQUEST['crm_name']) && $_REQUEST['crm_name'] == 'suitecrm')
	{
		$sugar_crm_option = array();
		$result=get_option( 'suitecrm_logininfo');
		$suite_crm_option['crm_url'] = trim($_REQUEST['crm_url']);
	
		$suite_crm_option['user_name'] = $_REQUEST['user_name'];
		$suite_crm_option['password'] = $_REQUEST['password'];
	
		
		if(isset($_REQUEST['autosync']))
			$suite_crm_option['autosync'] = $_REQUEST['autosync'];
		else
			$suite_crm_option['autosync'] = 0;
		//print_r($sugar_crm_option);
		$result=update_option( 'suitecrm_logininfo',$suite_crm_option );
		
	
	}
	?>
	<div id="message" class="updated below-h2">
					<p><?php _e("Connection Save Successfully",'school-mgt');?></p>
				</div>
	<?php 
	
}
?>
<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="?page=crm_connection&amp;tab=SugarCRMConnection"><?php _e('General Setting','cart2crm')?></a>    
</h2>
<h3><?php _e('CRM Connection Setting','cart2crm');?></h3>
<table class="form-table">
<tr valign="top">
<th class="titledesc" scope="row" width="150px"><label for="current_crm"><?php _e('Select CRM','cart2crm')?></label></th>
<td>
	<select name="current_crm" id="current_crm">
		<option value = ""> <?php _e('Select CRM','cart2crm');?></option>
		<option value = "sugarcrm" <?php selected($current_crm,'sugarcrm')?>> <?php _e('SugarCRM','cart2crm');?></option>
		<option value = "suitecrm" <?php selected($current_crm,'suitecrm')?>> <?php _e('SuiteCRM','cart2crm');?></option>
		<option value = "customcrm" <?php selected($current_crm,'customcrm')?>> <?php _e('CustomCRM','cart2crm');?></option>
		
	</select>
</td>
</tr>
</table>

<div id="crm_setting_block">
<?php 
if(isset($_REQUEST['save']))
{
	
	$current_crm = $_REQUEST['crm_name'];
	if($current_crm == 'sugarcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/sugarcrm/connection-setting.php';
	}
	elseif ($current_crm == 'suitecrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/suitecrm/connection-setting.php';
	}
	elseif ($current_crm == 'customcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/customcrm/connection-setting.php';
	}
}
else 
{
	
	if($current_crm == 'sugarcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/sugarcrm/connection-setting.php';
	}
	elseif ($current_crm == 'suitecrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/suitecrm/connection-setting.php';
	}
	elseif ($current_crm == 'customcrm')
	{
		require_once CRM_PLUGIN_DIR.'/admin/customcrm/connection-setting.php';
	}
}
?>
</div>
</div>
<?php

?>