<?php 
//CRM-Panel
$result=get_option( 'customcrm_logininfo');
require_once CRM_PLUGIN_DIR . '/admin/includes/class-customcrm.php';
$customcrm_result=get_option( 'customcrm_logininfo');
$db = new Cart2CRM();
$custom_connection = new CustomCRM();
if(isset($_REQUEST['sync']))
{
	if($_REQUEST['confirm_order'] == 'on' && $_REQUEST['aband_cart'] == "on")
	{
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
		echo  "<div style='background: none repeat scroll 0 0 green;color: white;float: left;font-size: 17px;margin-top: 25px;padding: 10px;width: 98%; margin-bottom: 10px;'>";
		_e("Syncronize your data sucessfully","cart2crm");
		echo "</div>";
	}
	elseif ($_REQUEST['confirm_order'] == 'off' && $_REQUEST['aband_cart'] == "on")
	{
		$leads=$db->getLeadsData('cart2crm_leads',1);
		$opportunity=$db->getLeadsData('cart2crm_opportunity',1);
		$account=$db->getLeadsData('cart2crm_account',1);
		$wp_user_search=$db->getOnoffIntegration('on-hold',1);
		
		foreach ( $wp_user_search as $userid1 ) {
			$wp_user=$db->getPostmeta($userid1->id);
			foreach($wp_user as $users){
				$array_data[$userid1->id][$users->meta_key] = $users->meta_value;
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
		echo  "<div style='background: none repeat scroll 0 0 green;color: white;float: left;font-size: 17px;margin-top: 25px;padding: 10px;width: 98%;'>Syncronize your data sucessfully</div>";
	}
	elseif ($_REQUEST['confirm_order'] == 'on' && $_REQUEST['aband_cart'] == "off")
	{
		$leads=$db->getLeadsData('cart2crm_leads',1);
		$opportunity=$db->getLeadsData('cart2crm_opportunity',1);
		$account=$db->getLeadsData('cart2crm_account',1);
		$wp_user_search=$db->getOnoffIntegration('completed',1);
		
		foreach ( $wp_user_search as $userid1 ) {
			$wp_user=$db->getPostmeta($userid1->id);
			foreach($wp_user as $users){
				$array_data[$userid1->id][$users->meta_key] = $users->meta_value;
			}
			//Insert leads data
			
			$ls= 0;
			
			$op= 0;
			
			//Insert acount data
			$fields3="";
			$values3="";
			foreach($account as $sy3){
				$fields3.=$sy3->accounts.",";
				$values3.="'".$array_data[$userid1->id][$sy3->order]."',";
			}
			$fields3=substr($fields3,0,-1);
			$values3=substr($values3,0,-1);
			$custom_connection->insert_data($fields,$values,$sy3->tbname);
			$ac= 1;
			
			$wp_user_searchs=$db->getPostModified($userid1->id);
			foreach($wp_user_searchs as $dates)
			{
				$date=$dates->post_modified;
			}
			$cldate = date('Y-m-d H:i:s');
			$wp_in=$db->addCRMID($userid1->id,$ls,$op,$ac,$date,$cldate,1);
		}
		echo  "<div style='background: none repeat scroll 0 0 green;color: white;float: left;font-size: 17px;margin-top: 25px;padding: 10px;width: 98%;'>Syncronize your data sucessfully</div>";
	}
	else 
		echo "Please any one is on for sync";
	
}
?>
<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="#"><?php _e('Custom CRM Integarion','cart2crm')?></a>    
</h2>
<div class="pick">
<form method="post" name="ordst">
<h3 class="order">
<p title="<?php _e('A WooCommerce order is created (I.e. a sale): Ability to turn this transfer of data between WooCommerce and SugarCRM On/Off.','cart2crm');?>">
<?php _e('Confirmed Order Sync','cart2crm');?> :</p>
<?php $imgurl=plugins_url( 'Cart2CRM/assets/images/');?>
	<input type="hidden" id="confirm_order1" name="confirm_order" value="<?php if(isset($result['confirm_order'])) echo $result['confirm_order']; else echo "on";?>">
		<img style="cursor: pointer; cursor: hand;" 
		src="<?php if(isset($result['confirm_order'])) echo plugins_url( 'Cart2CRM/assets/images/'.$result['confirm_order'].'.png'); else echo plugins_url( 'Cart2CRM/assets/images/on.png');?>" id="confirm_order" customurl="<?php echo $imgurl;?>">

</h3>
<h3 class="cart">
<p title="<?php _e('A WooCommerce abandoned cart occurs (I.e. no sale): Ability to turn this transfer of data between WooCommerce and SugarCRM On/Off.','cart2crm');?>">
<?php _e('Abandoned Cart Sync','cart2crm');?> :</p>
<input id="aband_cart" type="hidden" name="aband_cart" value ="<?php if(isset($result['aband_cart'])) echo $result['aband_cart']; else echo "on";?>">
<img id="aband_cartimg" style="cursor: pointer; cursor: hand;"  customurl="<?php echo $imgurl;?>"
src="<?php if(isset($result['aband_cart'])) echo plugins_url( 'Cart2CRM/assets/images/'.$result['aband_cart'].'.png'); else echo plugins_url( 'Cart2CRM/assets/images/on.png');?>">

</h3>
<input class="btnsync" type="submit" style="cursor: pointer; cursor: hand;" name="sync" value="<?php _e('Sync','cart2crm');?>">
</form>
</div>

</div>
<?php 
?>