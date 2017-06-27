<?php 
//CRM-Panel
$sugarcrm_options=get_option( 'sugarcrm_logininfo');
$db = new Cart2CRM();

if(isset($_REQUEST['sync']))
{
	
	if (!defined('sugarEntry')) define('sugarEntry', true);

require_once(CRM_PLUGIN_DIR.'/nusoap/nusoap.php');
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
	if($_REQUEST['confirm_order'] == 'on' && $_REQUEST['aband_cart'] == "on")
	{
		
		$leads_data=$db->getLeadsData('cart2crm_leads',2);
		$opportunity_data=$db->getLeadsData('cart2crm_opportunity',2);
		$account_data=$db->getLeadsData('cart2crm_account',2);
		
		$wp_user_search =$db->getExistleadProductId(2);
		$fetch_all_opp_data=$db->getExistopportunityProductId(2);
		$fetch_all_acc_data=$db->getExistaccountProductId(2);
		
		
		foreach ( $wp_user_search as $post ) {
			$syncs_data = array();
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
				$syncs_data = array();
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
			$syncs_data = array();
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
		
			
		echo  "<div style='background: none repeat scroll 0 0 green;color: white;float: left;font-size: 17px;margin-top: 25px;padding: 10px;width: 98%; margin-bottom: 10px;'>Syncronize your data sucessfully</div>";
	}
	elseif ($_REQUEST['confirm_order'] == 'off' && $_REQUEST['aband_cart'] == "on")
	{
		$leads_data=$db->getLeadsData('cart2crm_leads',2);
		$opportunity=$db->getLeadsData('cart2crm_opportunity',2);
		$account=$db->getLeadsData('cart2crm_account',2);
		$wp_user_search=$db->getOnoffIntegration('on-hold',2);
		foreach ( $wp_user_search as $post ) {
			$syncs_data = array();
				$post_meta=$db->getPostmeta($post->id);
				foreach($post_meta as $metadata){
					$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
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
		
			$op= 0;
			
			$ac= 0;
			
			$wp_user_searchs=$db->getPostModified($post->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			$wp_in=$db->addCRMID($post->id,$ls,$op,$ac,$date,$cldate,2);
		
			 
		}
		echo  "<div style='background: none repeat scroll 0 0 green;color: white;float: left;font-size: 17px;margin-top: 25px;padding: 10px;width: 98%;'>Syncronize your data sucessfully</div>";
	}
	elseif ($_REQUEST['confirm_order'] == 'on' && $_REQUEST['aband_cart'] == "off")
	{
		$leads_data=$db->getLeadsData('cart2crm_leads',2);
		$opportunity_data=$db->getLeadsData('cart2crm_opportunity',2);
		$account_data=$db->getLeadsData('cart2crm_account',2);
	$wp_user_search=$db->getOnoffIntegration('completed',2);
	
        foreach ( $wp_user_search as $post ) {
			$syncs_data = array();
			$post_meta=$db->getPostmeta($post->id);
				foreach($post_meta as $metadata){
					$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
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
            
            $op= 0; 
            $syncs_ac_data = array();
           foreach($account_data as $sy){
					$syncs_ac_data[] = array('name'=>$sy->accounts,'value'=>$array_data1[$post->id][$sy->order]);
				}
				
				$set_entry_params1 = array(
						'session' => $session_id,
						'module_name' => 'Accounts',
						'name_value_list'=>$syncs_ac_data
				);
				$result = $soapclient->call('set_entry',$set_entry_params1);
            $ac= 1;
            
			$wp_user_searchs=$db->getPostModified($post->id);
            foreach($wp_user_searchs as $dates)
            {
                $date=$dates->post_modified;
            }
            $cldate = date('Y-m-d H:i:s');
			$wp_in=$db->addCRMID($post->id,$ls,$op,$ac,$date,$cldate,2);

             
        }
		
		echo  "<div style='background: none repeat scroll 0 0 green;color: white;float: left;font-size: 17px;margin-top: 25px;padding: 10px;width: 98%;'>Syncronize your data sucessfully</div>";
	}
	else 
		echo "Please any one is on for sync";
	
}
?>
<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="#"><?php _e('Sugar CRM Integarion','cart2crm')?></a>    
</h2>
<div class="pick">
<form method="post" name="ordst">
<h3 class="order">
<p title="<?php _e('A WooCommerce order is created (I.e. a sale): Ability to turn this transfer of data between WooCommerce and SugarCRM On/Off.','cart2crm');?>">
<?php _e('Confirmed Order Sync','cart2crm');?> :</p>
<?php $imgurl=plugins_url( 'Cart2CRM/assets/images/');?>
	<input type="hidden" id="confirm_order1" name="confirm_order" value="<?php if(isset($sugarcrm_options['confirm_order'])) echo $sugarcrm_options['confirm_order']; else echo "on";?>">
		<img style="cursor: pointer; cursor: hand;" 
		src="<?php if(isset($sugarcrm_options['confirm_order'])) echo plugins_url( 'Cart2CRM/assets/images/'.$sugarcrm_options['confirm_order'].'.png'); 
		else echo plugins_url( 'Cart2CRM/assets/images/on.png');?>" id="sugar_confirm_order" customurl="<?php echo $imgurl;?>">

</h3>
<h3 class="cart">
<p title="<?php _e('A WooCommerce abandoned cart occurs (I.e. no sale): Ability to turn this transfer of data between WooCommerce and SugarCRM On/Off.','cart2crm');?>">
<?php _e('Abandoned Cart Sync','cart2crm');?> :</p>
<input id="aband_cart" type="hidden" name="aband_cart" value ="<?php if(isset($sugarcrm_options['aband_cart'])) echo $sugarcrm_options['aband_cart']; else echo "on";?>">
<img id="sugar_aband_cartimg" style="cursor: pointer; cursor: hand;"  customurl="<?php echo $imgurl;?>"
src="<?php if(isset($sugarcrm_options['aband_cart'])) echo plugins_url( 'Cart2CRM/assets/images/'.$sugarcrm_options['aband_cart'].'.png'); else echo plugins_url( 'Cart2CRM/assets/images/on.png');?>">

</h3>
<input class="btnsync" type="submit" style="cursor: pointer; cursor: hand;" name="sync" value="<?php _e('Sync','cart2crm');?>">
</form>
</div>

</div>
<?php 
?>