<?php

function cart2crm_auto_sync_customcrm()
{
	require_once CRM_PLUGIN_DIR . '/admin/includes/class-customcrm.php';
	$db = new Cart2CRM();
	$custom_connection = new CustomCRM();
		$leads=$db->getLeadsData('cart2crm_leads',1);
		$opportunity=$db->getLeadsData('cart2crm_opportunity',1);
		$account=$db->getLeadsData('cart2crm_account',1);
		$wp_user_search=$db->getExistleadProductId(1);
		$fetch_all_opp_data=$db->getExistopportunityProductId(1);
		$fetch_all_acc_data=$db->getExistaccountProductId(1);
		foreach ( $wp_user_search as $userid1 ) {
			$post_meta=$db->getPostmeta($userid1->id);
			foreach($post_meta as $metadata){
				$array_data[$userid1->id][$metadata->meta_key] = $metadata->meta_value;
			}
			//Insert leads data
			$fields="";
			$values="";
			foreach($leads as $sy){
				$fields.=$sy->sugarcrm.",";
				$values.="'".$array_data[$userid1->id][$sy->order]."',";
			}
			$fields=substr($fields,0,-1);
			$values=substr($values,0,-1);
			$custom_connection->insert_data($fields,$values,$sy->tbname);
			$ls= 1;
			
			
			$op= 0;
			
			$ac= 0;
			
			$wp_user_searchs=$db->getPostModified($userid1->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			$wp_in=$db->addCRMID($userid1->id,$ls,$op,$ac,$date,$cldate,1);
		}
		foreach ( $fetch_all_opp_data as $userid1 ) {
			$wp_user=$db->getPostmeta($userid1->id);
			foreach($wp_user as $users){
				$array_data[$userid1->id][$users->meta_key] = $users->meta_value;
			}
			//Insert leads data
			$fields="";
				$values="";
				foreach($opportunity as $sy){
					$fields.=$sy->opportunity.",";
					$values.="'".$array_data[$userid1->id][$sy->order]."',";
				}
				$fields=substr($fields,0,-1);
				$values=substr($values,0,-1);
				//$custom_connection->customdb->query("insert into ".$sy->tbname." (".$fields.") values (".$values.")");
				$custom_connection->insert_data($fields,$values,$sy->tbname);
			$ls= 0;
				
				
			$op= 1;
				
			$ac= 0;
				
			$wp_user_searchs=$db->getPostModified($userid1->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			$wp_in=$db->addCRMID($userid1->id,$ls,$op,$ac,$date,$cldate,1);
		}
		foreach ( $fetch_all_acc_data as $userid1 ) {
			$wp_user=$db->getPostmeta($userid1->id);
			foreach($wp_user as $users){
				$array_data[$userid1->id][$users->meta_key] = $users->meta_value;
			}
			//Insert leads data
			$fields="";
				$values="";
				foreach($account as $sy){
					$fields.=$sy->accounts.",";
					$values.="'".$array_data[$userid1->id][$sy->order]."',";
				}
				$fields=substr($fields,0,-1);
				$values=substr($values,0,-1);
				//$custom_connection->customdb->query("insert into ".$sy->tbname." (".$fields.") values (".$values.")");
				$custom_connection->insert_data($fields,$values,$sy->tbname);
			$ls= 0;
		
		
			$op= 0;
		
			$ac= 1;
		
			$wp_user_searchs=$db->getPostModified($userid1->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			$wp_in=$db->addCRMID($userid1->id,$ls,$op,$ac,$date,$cldate,1);
		}
}
function cart2crm_auto_sync_sugarcrm()
{
	$sugarcrm_options=get_option( 'sugarcrm_logininfo');
	$db = new Cart2CRM();
	if (!defined('sugarEntry')) define('sugarEntry', true);

	require_once(CRM_PLUGIN_DIR.'/nusoap/nusoap.php');
//require_once('nusoap.php');

$soapclient = new nusoapclient($sugarcrm_options['crm_url'].'soap.php?wsdl',false);

$user_auth = array(
		'user_auth' => array(
				'user_name' => $sugarcrm_options['user_name'],
				'password' => $sugarcrm_options['password'],
				'version' => '0.1'
		),
		'application_name' => '');
	$sugarcrm_conection = $soapclient->call('login',$user_auth);
	$session_id = $sugarcrm_conection['id'];
	$user_guid = $soapclient->call('get_user_id',$session_id);
	
		$leads_data=$db->getLeadsData('cart2crm_leads',2);
		$opportunity_data=$db->getLeadsData('cart2crm_opportunity',2);
		$account_data=$db->getLeadsData('cart2crm_account',2);
		
		$wp_user_search =$db->getExistleadProductId(2);
		$fetch_all_opp_data=$db->getExistopportunityProductId(2);
		$fetch_all_acc_data=$db->getExistaccountProductId(2);
		
		
		foreach ( $wp_user_search as $post ) {
			$syncs_data =array();
			$post_meta=$db->getPostmeta($post->id);
			foreach($post_meta as $metadata){
				$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
			}
			if($db->is_orderlead_exist($post->id,2))
				{
					
					$result = $db->get_from_cart2crm_sugar_id($post->id,2);
					$syncs_data[] = array('name'=>'id',
					'value'=>$result->sugarcrm_insertid);
					
				}
			
			foreach($leads_data as $sy){
				$syncs_data[] = array('name'=>$sy->sugarcrm,'value'=>$array_data1[$post->id][$sy->order]);
			}
			$set_entry_params1 = array(
					'session' => $session_id,
					'module_name' => 'Leads',
					'name_value_list'=>$syncs_data
			);
			$result = $soapclient->call('set_entry',$set_entry_params1);
			
			$ls= 1;
			
			
			$op=0;
			//	echo $op;
			
			$ac= 0;
			//	echo $ac;
		
			$wp_user_searchs=$db->getPostModified($post->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			if(!$db->is_orderlead_exist($post->id,2))
			$wp_in=$db->addCRMID($post->id,$ls,$op,$ac,$date,$cldate,2,$result['id']);
			
			
		}
		foreach ( $fetch_all_opp_data as $post ) {
			$syncs_data =array();	
			$post_meta=$db->getPostmeta($post->id);
				foreach($post_meta as $metadata){
					$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
				}	
				if($db->is_orderopportunity_exist($post->id,2))
				{
					$result = $db->get_from_cart2crm_sugar_opportunity_id($post->id,2);
					$syncs_data[] = array('name'=>'id',
					'value'=>$result->sugarcrm_insertid);
					
				}
				foreach($opportunity_data as $sy){
					$syncs_data[] = array('name'=>$sy->opportunity,'value'=>$array_data1[$post->id][$sy->order]);
				}
				
				
				$set_entry_params1 = array(
						'session' => $session_id,
						'module_name' => 'Opportunities',
						'name_value_list'=>$syncs_data
				);
				$result = $soapclient->call('set_entry',$set_entry_params1);
				
			$ls= 0;
			$op=1;
			$ac= 0;
			$wp_user_searchs=$db->getPostModified($post->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			if(!$db->is_orderopportunity_exist($post->id,2))
			$wp_in=$db->addCRMID($post->id,$ls,$op,$ac,$date,$cldate,2,$result['id']);
		}
		
		foreach ( $fetch_all_acc_data as $post ) {
			$syncs_data =array();
			$post_meta=$db->getPostmeta($post->id);
			foreach($post_meta as $metadata){
				$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
			}
			if($db->is_orderaccount_exist($post->id,2))
				{
					$result = $db->get_from_cart2crm_sugar_account_id($post->id,2);
					$syncs_data[] = array('name'=>'id',
					'value'=>$result->sugarcrm_insertid);
				}
			foreach($leads_data as $sy){
				$syncs_data[] = array('name'=>$sy->sugarcrm,'value'=>$array_data1[$post->id][$sy->order]);
			}
			//echo $ls;
			  foreach($account_data as $sy){
					$syncs_data[] = array('name'=>$sy->accounts,'value'=>$array_data1[$post->id][$sy->order]);
				}
				
				$set_entry_params1 = array(
						'session' => $session_id,
						'module_name' => 'Accounts',
						'name_value_list'=>$syncs_data
				);
				$result = $soapclient->call('set_entry',$set_entry_params1);
			
		
			$ls= 0;
			$op=0;
			$ac= 1;
			$wp_user_searchs=$db->getPostModified($post->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			if(!$db->is_orderaccount_exist($post->id,2))
			$wp_in=$db->addCRMID($post->id,$ls,$op,$ac,$date,$cldate,2,$result['id']);
		}
}
function cart2crm_auto_sync_suitecrm()
{
	$suitecrm_options=get_option( 'suitecrm_logininfo');
	$db = new Cart2CRM();
	if (!defined('sugarEntry')) define('sugarEntry', true);
require_once(CRM_PLUGIN_DIR.'/nusoap/nusoap.php');
$soapclient = new nusoapclient($suitecrm_options['crm_url'].'soap.php?wsdl',false);

$user_auth = array(
		'user_auth' => array(
				'user_name' => $suitecrm_options['user_name'],
				'password' => $suitecrm_options['password'],
				'version' => '0.1'
		),
		'application_name' => '');
	$suitecrm_conection = $soapclient->call('login',$user_auth);
	$session_id = $suitecrm_conection['id'];
	$user_guid = $soapclient->call('get_user_id',$session_id);
	
$leads_data=$db->getLeadsData('cart2crm_leads',3);
		$opportunity_data=$db->getLeadsData('cart2crm_opportunity',3);
		$account_data=$db->getLeadsData('cart2crm_account',3);
		$wp_user_search =$db->getExistleadProductId(3);
		$fetch_all_opp_data=$db->getExistopportunityProductId(3);
		$fetch_all_acc_data=$db->getExistaccountProductId(3);
		
		foreach ( $wp_user_search as $post ) {
			$syncs_data =array();
			$post_meta=$db->getPostmeta($post->id);
			foreach($post_meta as $metadata){
				$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
			}
			if($db->is_orderlead_exist($post->id,3))
				{
					
					$result = $db->get_from_cart2crm_sugar_id($post->id,3);
					$syncs_data[] = array('name'=>'id',
					'value'=>$result->sugarcrm_insertid);
					
				}		
			foreach($leads_data as $sy){
				$syncs_data[] = array('name'=>$sy->sugarcrm,'value'=>$array_data1[$post->id][$sy->order]);
			}
			$set_entry_params1 = array(
					'session' => $session_id,
					'module_name' => 'Leads',
					'name_value_list'=>$syncs_data
			);
			$result = $soapclient->call('set_entry',$set_entry_params1);
				
			$ls= 1;
				
				
			$op=0;
			//	echo $op;
				
			$ac= 0;
			//	echo $ac;
			
			$wp_user_searchs=$db->getPostModified($post->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			if(!$db->is_orderlead_exist($post->id,3))
			$wp_in=$db->addCRMID($post->id,$ls,$op,$ac,$date,$cldate,3,$result['id']);
		}
		foreach ( $fetch_all_opp_data as $post ) {
		$syncs_data =array();
			$post_meta=$db->getPostmeta($post->id);
			foreach($post_meta as $metadata){
				$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
			}
			if($db->is_orderopportunity_exist($post->id,3))
				{
					$result = $db->get_from_cart2crm_sugar_opportunity_id($post->id,3);
					$syncs_data[] = array('name'=>'id',
					'value'=>$result->sugarcrm_insertid);
					
				}
			foreach($opportunity_data as $sy){
				$syncs_data[] = array('name'=>$sy->opportunity,'value'=>$array_data1[$post->id][$sy->order]);
			}
		
		
			$set_entry_params1 = array(
					'session' => $session_id,
					'module_name' => 'Opportunities',
					'name_value_list'=>$syncs_data
			);
			$result = $soapclient->call('set_entry',$set_entry_params1);
		
			$ls= 0;
			$op=1;
			$ac= 0;
			$wp_user_searchs=$db->getPostModified($post->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			if(!$db->is_orderopportunity_exist($post->id,3))
			$wp_in=$db->addCRMID($post->id,$ls,$op,$ac,$date,$cldate,3,$result['id']);
		}
		
		foreach ( $fetch_all_acc_data as $post ) {
			$syncs_data =array();
			$post_meta=$db->getPostmeta($post->id);
			foreach($post_meta as $metadata){
				$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
			}
			if($db->is_orderaccount_exist($post->id,3))
				{
					$result = $db->get_from_cart2crm_sugar_account_id($post->id,3);
					$syncs_data[] = array('name'=>'id',
					'value'=>$result->sugarcrm_insertid);
				}		
			foreach($leads_data as $sy){
				$syncs_data[] = array('name'=>$sy->sugarcrm,'value'=>$array_data1[$post->id][$sy->order]);
			}
			//echo $ls;
			foreach($account_data as $sy){
				$syncs_data[] = array('name'=>$sy->accounts,'value'=>$array_data1[$post->id][$sy->order]);
			}
		
			$set_entry_params1 = array(
					'session' => $session_id,
					'module_name' => 'Accounts',
					'name_value_list'=>$syncs_data
			);
			$result = $soapclient->call('set_entry',$set_entry_params1);
				
		
			$ls= 0;
			$op=0;
			$ac= 1;
			$wp_user_searchs=$db->getPostModified($post->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			if(!$db->is_orderaccount_exist($post->id,3))
			$wp_in=$db->addCRMID($post->id,$ls,$op,$ac,$date,$cldate,3,$result['id']);
		}
}

//AJAX
add_action( 'wp_ajax_cart2crm_connectoin_setting_block', 'cart2crm_connectoin_setting_block' );
add_action( 'wp_ajax_ajax_cart2crm_integration', 'ajax_cart2crm_integration' );
add_action( 'wp_ajax_ajax_cart2crm_cart_integration', 'ajax_cart2crm_cart_integration' );
add_action( 'wp_ajax_ajax_cart2crm_table_field', 'ajax_cart2crm_table_field' );

//SugarCRM
add_action( 'wp_ajax_ajax_cart2crm_sugar_order_integration', 'ajax_cart2crm_sugar_order_integration' );
add_action( 'wp_ajax_ajax_cart2crm_sugar_cart_integration', 'ajax_cart2crm_sugar_cart_integration' );

//SuiteCRM
add_action( 'wp_ajax_ajax_cart2crm_suite_order_integration', 'ajax_cart2crm_suite_order_integration' );
add_action( 'wp_ajax_ajax_cart2crm_suite_cart_integration', 'ajax_cart2crm_suite_cart_integration' );
function cart2crm_connectoin_setting_block()
{
	$current_crm = $_POST['current_crm'];
	update_option( 'current_active_crm',$current_crm );
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
	die();
}

function ajax_cart2crm_integration()
{
	$confirm_order = $_REQUEST['confirm_order'];
	
	$result=get_option( 'customcrm_logininfo');
	
	if($confirm_order == "on")
	{	$confirm_order = "off";}
	else 
	{
		$confirm_order = "on";
		
	}
	
	
	$result['confirm_order'] = $confirm_order;
	
	update_option( 'customcrm_logininfo',$result );
	
	die();
}
function ajax_cart2crm_cart_integration()
{
	$aband_cart = $_REQUEST['aband_cart'];

	$result=get_option( 'customcrm_logininfo');

	if($aband_cart == "on")
	{	$aband_cart = "off";}
	else
	{
		$aband_cart = "on";

	}


	$result['aband_cart'] = $aband_cart;

	update_option( 'customcrm_logininfo',$result );

	die();
}
//Sugar CRM
function ajax_cart2crm_sugar_order_integration()
{
	$confirm_order = $_REQUEST['confirm_order'];

	$result=get_option( 'sugarcrm_logininfo');

	if($confirm_order == "on")
	{	$confirm_order = "off";}
	else
	{
		$confirm_order = "on";

	}


	$result['confirm_order'] = $confirm_order;

	update_option( 'sugarcrm_logininfo',$result );

	die();
}

function ajax_cart2crm_sugar_cart_integration()
{
	$aband_cart = $_REQUEST['aband_cart'];

	$result=get_option( 'sugarcrm_logininfo');

	if($aband_cart == "on")
	{	$aband_cart = "off";}
	else
	{
		$aband_cart = "on";

	}


	$result['aband_cart'] = $aband_cart;

	update_option( 'sugarcrm_logininfo',$result );

	die();
}

//SuiteCRM
function ajax_cart2crm_suite_order_integration()
{
	$confirm_order = $_REQUEST['confirm_order'];

	$result=get_option( 'suitecrm_logininfo');

	if($confirm_order == "on")
	{	$confirm_order = "off";}
	else
	{
		$confirm_order = "on";

	}


	$result['confirm_order'] = $confirm_order;

	update_option( 'suitecrm_logininfo',$result );

	die();
}

function ajax_cart2crm_suite_cart_integration()
{
	$aband_cart = $_REQUEST['aband_cart'];

	$result=get_option( 'suitecrm_logininfo');

	if($aband_cart == "on")
	{	$aband_cart = "off";}
	else
	{
		$aband_cart = "on";

	}


	$result['aband_cart'] = $aband_cart;

	update_option( 'suitecrm_logininfo',$result );

	die();
}

function ajax_cart2crm_table_field()
{
	$tablename = $_REQUEST['select_table'];
	$result=get_option( 'customcrm_logininfo');
	$custom_db = new wpdb($result['user_name'],$result['password'],$result['database_name'],$result['host_name']);
	
	$fields = $custom_db->get_results( "SHOW COLUMNS FROM ".$tablename );
	$html="";
	$html.="<select id='FieldList' name='custom_field'>";
	foreach($fields as $field)
	{
		$html.="<option value='".$field->Field."'>".$field->Field."</option>";
	}
	$html.="</select>";
	echo $html;
	die();
}
function run_install_or_upgrade($table_name, $sql, $db_version)
{
	global $wpdb;

	// Table does not exist, we create it!
	// We use InnoDB and UTF-8 by default
	if ($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name)
	{
		$create = "CREATE TABLE IF NOT EXISTS ".$table_name." ( ".$sql." ) DEFAULT CHARSET=utf8 ;";
		// We use the dbDelta method given by WP!
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($create);
	}
}
function activate()
{
	
	global $wpdb;
	$db_version = WP_CART2CRM_DB_VERSION;

	// leads table
	$sql = "`ID` int(11) NOT NULL AUTO_INCREMENT,
			 		`order` text NOT NULL,
					`sugarcrm` text NOT NULL,
					`tbname` text,
					`crmtype` int(11),
					PRIMARY KEY (`ID`)";
	run_install_or_upgrade($wpdb->prefix.'cart2crm_leads', $sql, $db_version);

	// opportunity table
	$sql = "`ID` int(11) NOT NULL AUTO_INCREMENT,
			 		`order` text NOT NULL,
					`opportunity` text NOT NULL,
					`tbname` text,
					`crmtype` int(11),
					PRIMARY KEY (`ID`)";
	run_install_or_upgrade( $wpdb->prefix.'cart2crm_opportunity', $sql, $db_version);

	// account table
	$sql = "`ID` int(11) NOT NULL AUTO_INCREMENT,
			 		`order` text NOT NULL,
					`accounts` text NOT NULL,
					`tbname` text,
					`crmtype` int(11),
					PRIMARY KEY (`ID`)";
	run_install_or_upgrade($wpdb->prefix.'cart2crm_account', $sql, $db_version);

	// logininfo table
	$sql = "`ID` int(11) NOT NULL ,
			 		`db_host` text ,
					`db_username` text,
					`db_pass` text,
					`db_name` text,
					`login` int(11) NOT NULL,
					`url` text";
	run_install_or_upgrade($wpdb->prefix.'cart2crm_logininfo', $sql, $db_version);

	// sugar_id table
	$sql = "`id` mediumint(12) NOT NULL,
			 		`leadsid` text NOT NULL,
					`oppid`  text NOT NULL,
					`acid` text NOT NULL,
					`date` datetime,
					`cldate` datetime,
					`crmtype` int(11)";
	run_install_or_upgrade($wpdb->prefix.'cart2crm_sugar_id', $sql, $db_version);

	// settings table
	/*
	$sql = "`id` int(11) NOT NULL AUTO_INCREMENT,
			 		`status` int(10) NOT NULL,
					PRIMARY KEY (`id`)";
	run_install_or_upgrade($wpdb->prefix.'cart2crm_settings', $sql, $db_version);

	$wpdb->query("INSERT INTO $wpdb->prefix.'cart2crm_settings' (`status`) VALUES(1), (1), (0), (0), (1), (1);");
	$wpdb->query("INSERT INTO $wpdb->prefix.'cart2crm_logininfo' (`ID`,`login`) VALUES(1,0);");
	
	*/
	$sugar_id =  'sugar_id';
	$table_cart2crm_sugar_id = $wpdb->prefix.'cart2crm_sugar_id';
	if (!in_array($sugar_id, $wpdb->get_col( "DESC " . $table_cart2crm_sugar_id, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_cart2crm_sugar_id  ADD   $sugar_id   bigint PRIMARY KEY AUTO_INCREMENT");}
	
	 $sugarcrm_insertid = ' sugarcrm_insertid';
	if (!in_array($sugarcrm_insertid, $wpdb->get_col( "DESC " . $table_cart2crm_sugar_id, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_cart2crm_sugar_id  ADD   $sugarcrm_insertid   varchar(200)");}
}


function Cart2CRM_option(){
	$options=array("cart2crm_version"=>CART2CRM_VERSION,
			"sugarcrm_logininfo"=>array(),
			"suitecrm_logininfo"=>array(),
			"customcrm_logininfo"=>array(),
			"current_active_crm"=>""			
	);
	return $options;
}

function Cart2CRM_setting_option()
{
	$options=Cart2CRM_option();
	foreach($options as $key=>$val)
	{
		add_option($key,$val);
			
	}
}
?>