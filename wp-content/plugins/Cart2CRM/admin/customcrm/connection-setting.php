<?php
//Custom Connection Setting
$result=get_option( 'customcrm_logininfo');

?>
<h1><?php _e('Custom CRM Setting','cart2crm');?></h1>
<div class="crm-settings-panel pick">
<p><?php _e('This plugin allows you to integrate your existing CustomCRM to your Custom data.','cart2crm');?></p>
	<div class="custom-crmform">
		<div class="metabox-prefs">
			<form method="post" action="">
				<p><label for="crm_url"><?php _e('CRM URL','cart2crm');?> : </label>
				<input id="crm_url" type="text" size="70" value="<?php if(isset($result['crm_url'])) echo $result['crm_url'];?>" name="crm_url">
				<i><?php _e('e.g. "http://www.mobilewebs.net/sugarcrm/"','cart2crm');?></i>
				</p>
				<p>
				 <label for="host_name"><?php _e('Hostname','cart2crm');?> : </label>
			    <input type="text" name="host_name" id="host_name" value="<?php if(isset($result['host_name'])) echo $result['host_name'];?>" size="25" />
			    </p>
			    <p>
			    <label for="user_name"><?php _e('Username','cart2crm');?> : </label>
			    <input type="text" name="user_name" id="user_name" value="<?php if(isset($result['user_name'])) echo $result['user_name'];?>" size="25" /> 
			    <i>e.g. "admin"</i>
			    </p>
			    <p>
			    <label for="password"><?php _e('Password','cart2crm');?> : </label>
			    <input type="password" name="password" id="password" size="25" value="<?php if(isset($result['password'])) echo $result['password'];?>"/>
			    <i>e.g. "secret"</i>
			    </p>
			    <p>
			    <label for="database_name"><?php _e('DBname','cart2crm');?> : </label>
			    <input type="text" name="database_name" id="database_name" value="<?php if(isset($result['database_name'])) echo $result['database_name'];?>" size="25" />
			    <i>e.g. "crmdb"</i>
			    </p>
			    <p>
			    <label for="autosync"><?php _e('Auto sync','cart2crm');?> : </label>	 
			    <input type="checkbox" name="autosync"  value="1" id="autosync" <?php if(isset($result['autosync']))checked($result['autosync'],1);?> />  ( the order will automatically be synced to CustomCRM. )
			    </p>
			    <br />
			    <input type="hidden" name="crm_name" value="customcrm">
			    <input type="submit" name="save" id="custom-crm-settings-save" value="<?php _e('Save','cart2crm');?>" class="button primary" />
			</form>
		</div>
	</div>
</div>
<?php 
?>