<?php 
//CRM-fieldmapping
//CRM type 1 = cusatom
//CRM type 2 = Sugarcrm
$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'Leads';


if($active_tab == 'Opportunity')
{
	$module_name = 'Opportunities';
}
elseif($active_tab == 'Account')
{
	$module_name = 'Accounts';
}
else
{
	$module_name = 'Leads';
}

$sugarcrm_options=get_option( 'sugarcrm_logininfo');

	
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

$db = new Cart2CRM();

$message = "";

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	
	$current_tab = $_REQUEST['tab'];
	if($current_tab == 'Leads')
	{
		$table_name = "cart2crm_leads";
		
	}
	if($current_tab == 'Opportunity')
	{
		$table_name = "cart2crm_opportunity";
	}
	if($current_tab == 'Account')
	{
		$table_name = "cart2crm_account";
	}
	$result=$db->deleteField($table_name,$_REQUEST['id']);
	if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Delete Field Succefully','school-mgt');?></p>
				</div>
		<?php 
			}
	}
if(isset($_REQUEST['save']))
{
	
	$current_tab = $_REQUEST['current_tab'];
	$wc_field = $_REQUEST['wp_order'];
	
	$customcrm_field = $_REQUEST['custom_field'];
	$crmtype = 2;
	if($current_tab == 'Leads')
	{
	$customcrm_data = array('order'=>$wc_field,
							'sugarcrm' =>$customcrm_field,
							
							'crmtype' =>$crmtype);
	$table_name = "cart2crm_leads";
	if($db->isexistInlead($table_name,$customcrm_data))
		echo "Record all ready exist";
	else 
		$result = $db->insert_record($table_name,$customcrm_data);
	
	
	}
	if($current_tab == 'Opportunity')
	{
		$customcrm_data = array('order'=>$wc_field,
				'opportunity' =>$customcrm_field,
				
				'crmtype' =>$crmtype);
		$table_name = "cart2crm_opportunity";
		if($db->isexistInopportunity($table_name,$customcrm_data))
			echo "Record all ready exist";
		else
			$result = $db->insert_record($table_name,$customcrm_data);
	
	}
	if($current_tab == 'Account')
	{
		$customcrm_data = array('order'=>$wc_field,
				'accounts' =>$customcrm_field,
				
				'crmtype' =>$crmtype);
		$table_name = "cart2crm_account";
		if($db->isexistInaccount($table_name,$customcrm_data))
			echo "Record all ready exist";
		else
			$result = $db->insert_record($table_name,$customcrm_data);
	
	}
	
	
} 
if(isset($_REQUEST['sysnc']))
{
	$current_tab = $_REQUEST['current_tab'];
	
	
	if($current_tab == 'Leads')
	{
		$table_name = "cart2crm_leads";
		$leads_data=$db->getLeadsData($table_name,2);
		$array_data1 =array();
		$fetch_all_data=$db->getExistleadProductId(2);
		
		if(!empty($fetch_all_data))
		{
			$syncs_data = array();
			foreach($fetch_all_data as $post)
			{
				
				$post_meta=$db->getPostmeta($post->id);
				foreach($post_meta as $metadata){
					
					$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
				}
				
			
				//echo $db->is_orderlead_exist($post->id);
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
			
				
				$cldate = date('Y-m-d H:i:s');
				
				if(!$db->is_orderlead_exist($post->id,2))
				{
				$wp_in=$db->addLeadsCustomCRM($post->id,1,$cldate,2,$result['id']);
				}
				
				
			}
			
		}
	}
	
	if($current_tab == 'Opportunity')
	{
		
		$table_name = "cart2crm_opportunity";
		$leads_data=$db->getLeadsData($table_name,2);
		$array_data1 =array();
		$fetch_all_data=$db->getExistopportunityProductId(2);
		
		if(!empty($fetch_all_data))
		{
			foreach($fetch_all_data as $post)
			{
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
				foreach($leads_data as $sy){
					$syncs_data[] = array('name'=>$sy->opportunity,'value'=>$array_data1[$post->id][$sy->order]);
				}
				
				$set_entry_params1 = array(
						'session' => $session_id,
						'module_name' => 'Opportunities',
						'name_value_list'=>$syncs_data
				);
			
				$result = $soapclient->call('set_entry',$set_entry_params1);
				$cldate = date('Y-m-d H:i:s');
				if(!$db->is_orderopportunity_exist($post->id,2))
				{
				$wp_in=$db->addOpportunityCustomCRM($post->id,1,$cldate,2,$result['id']);
				}
			}
		}
		
	
	}
	if($current_tab == 'Account')
	{
		$table_name = "cart2crm_account";
		$leads_data=$db->getLeadsData($table_name,2);
		$array_data1 =array();
		$fetch_all_data=$db->getExistaccountProductId(2);
		
		if(!empty($fetch_all_data))
		{
			foreach($fetch_all_data as $post)
			{
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
					$syncs_data[] = array('name'=>$sy->accounts,'value'=>$array_data1[$post->id][$sy->order]);
				}
				$set_entry_params1 = array(
						'session' => $session_id,
						'module_name' => 'Accounts',
						'name_value_list'=>$syncs_data
				);
				
				$result = $soapclient->call('set_entry',$set_entry_params1);
				$cldate = date('Y-m-d H:i:s');	
				if(!$db->is_orderaccount_exist($post->id,2))
				{
					$wp_in=$db->addaccountCustomCRM($post->id,1,$cldate,2,$result['id']);
				}
			}
		}
	
	
	
	}
	if($result['error']['number'] == 0)
	{
		$message = "<div style='background: none repeat scroll 0 0 green;color: white;float: left;font-size: 17px;margin-top: 25px;padding: 10px;width: 98%; margin-bottom: 10px;'>Syncronize your data sucessfully</div>";
	}
	
}
echo $message;
?>

<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="#"><?php _e('SugarCRM Field Mapping','cart2crm')?></a>    
</h2>
<?php 
//$lwpdb = new wpdb( $user, $pass, $db, $host );

//$custom_db = new wpdb('root','','crm','localhost');


if($sugarcrm_conection)
{

 if( isset( $_GET[ 'tab' ] ) ) {  
        $active_tab = $_GET[ 'tab' ];  
    } // end if  
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'Leads'; ?>
    <h2 class="nav-tab-wrapper">  
    <a href="?page=crm_field_mapping&tab=Leads" class="nav-tab <?php echo $active_tab == 'Leads' ? 'nav-tab-active' : ''; ?>">Leads</a>  
    <a href="?page=crm_field_mapping&tab=Opportunity" class="nav-tab <?php echo $active_tab == 'Opportunity' ? 'nav-tab-active' : ''; ?>">Opportunity</a>  
    <a href="?page=crm_field_mapping&tab=Account" class="nav-tab <?php echo $active_tab == 'Account' ? 'nav-tab-active' : ''; ?>">Account</a>  
    </h2>
     <form method="post" >
     <input type="hidden" name = "current_tab" value = "<?php echo $active_tab;?>">
     
     	<table class="customformlead">
     		<tbody>
     			<tr valign="top">
     				<td class="title"><?php _e('Woocommerce','cart2crm');?></td>
     				<td>
     					<?php global $wpdb;
						$wp_user_search1=$db->getSinglePost();
						if(empty($wp_user_search1))
						{
							echo "<P class='cart2crm_order'> : ";
							_e('No Any Record Found','cart2crm');
							echo "</P>";
						}
						else{
						?>
     					<select name="wp_order" id="wordpress_users" class="regular-text"  >
     					<?php global $wpdb;
						$wp_user_search1=$db->getSinglePost();
				        foreach ($wp_user_search1 as $userid2) {
							$wp_user=$db->getPostmeta($userid2->id);
				            foreach($wp_user as $users){
				                $array_data[$userid2->id][] = $users->meta_key;
				                echo '<option value="'.$users->meta_key.'">'. $users->meta_key.'</option>';
				            }
				        } ?>
				        </select>
				        <?php }?>
					</td>
					<td><img src="<?php echo plugins_url('Cart2CRM/assets/images/map.png') ?>" class="maps"/></td>
					<td class="title"><?php _e('Sugarcrm','cart2crm');?></td>
					<td>
				       
				        <select name="custom_field" id="select_table" class="regular-text" >
					        <option value="">---Select field---</option>
					       <?php 
					       		$accountParams = array('session' => $session_id, 'module_name' => $module_name,'query' => "", 'order_by' => '', 'deleted' => 0);
								$accountId = $soapclient->call('get_entry_list', $accountParams);
								$ac = $accountId['field_list'];
						        foreach($ac as $a){
						            echo '<option value="'.$a['name'].'">'.$a['name'].'</option>';
						        }
					        ?>
				        </select>
			        <td>
			       
			        <td class="btnsave">
			        	<input type="submit" style="cursor: pointer; cursor: hand;" name="save" value="<?php _e('Save','cart2crm');?>">
			        </td>
     			</tr>
     			<tr>
     			<td class="btnsave"><input type="submit" name="sysnc" value="<?php _e('Sync Now','cart2crm');?>"></td>
     			</tr>
     		</tbody>
     	</table>
     </form>
     <div class="all_field_list">
     
        <table class="widefat" style="margin-top:5px">
          <?php 
		        if($active_tab == 'Opportunity')
		        {?>
	        <thead>
	        <tr>
	        	<th colspan="3"><?php _e('Opportunity fields','cart2crm');?></th>
	        </tr>
	        </thead>
	        <tbody>
		        <tr>
			        <th><?php _e('Woocommerce Fields','cart2crm');?></th>
			        <th><?php _e('SugarCRM Fields','cart2crm');?></th>
			       
			        <th><?php _e('Delete','cart2crm');?></th>
		        </tr>	
		      <?php 
		        	$table_name = "cart2crm_opportunity";
		        	$record = $db->getcrmData($table_name,2);
		        	foreach($record as $retrive_data)
		        	{
		        		?>
		        			       	<tr>
		        			       		<td><?php echo $retrive_data->order;?></td>
		        			       		<td><?php echo $retrive_data->opportunity;?></td>
		        			       		
		        			       		<td><a href="?page=crm_field_mapping&tab=Opportunity&action=delete&id=<?php echo $retrive_data->ID;?>">
		        			       		<img class="dele" src="<?php echo plugins_url('Cart2CRM/assets/images/delete.png')?> "  style="cursor: pointer; cursor: hand;" 
		        			       		 onclick="return confirm('<?php _e('Are you sure you want to delete this record?','cart2crm');?>');"/>
		        			       		<?php //_e('Delete','cart2crm');?>
		        			       		</a></td>
		        			       	</tr>
		        			       	<?php 
		        			       }
		        }
		        elseif($active_tab == 'Account')
		        {?>
	        <thead>
	        <tr>
	        	<th colspan="3"><?php _e('Account fields','cart2crm');?></th>
	        </tr>
	        </thead>
	        <tbody>
		        <tr>
			        <th><?php _e('Woocommerce Fields','cart2crm');?></th>
			        <th><?php _e('SugarCRM Fields','cart2crm');?></th>
			       
			        <th><?php _e('Delete','cart2crm');?></th>
		        </tr>	
		      <?php 
		        	$table_name = "cart2crm_account";
		        	$record = $db->getcrmData($table_name,2);
		        	foreach($record as $retrive_data)
		        	{
		        		?>
		        			       	<tr>
		        			       		<td><?php echo $retrive_data->order;?></td>
		        			       		<td><?php echo $retrive_data->accounts;?></td>
		        			       		
		        			       		<td><a href="?page=crm_field_mapping&tab=Account&action=delete&id=<?php echo $retrive_data->ID;?>">
		        			       		<img class="dele" src="<?php echo plugins_url('Cart2CRM/assets/images/delete.png')?> "  style="cursor: pointer; cursor: hand;"
		        			       		 onclick="return confirm('<?php _e('Are you sure you want to delete this record?','cart2crm');?>');"/>
		        			       		<?php // _e('Delete','cart2crm');?>
		        			       		</a></td>
		        			       	</tr>
		        			       	<?php 
		        			       }
		        }
		        else
		        {?>
	        <thead>
	        <tr>
	        	<th colspan="3"><?php _e('Leads fields','cart2crm');?></th>
	        </tr>
	        </thead>
	        <tbody>
		        <tr>
			        <th><?php _e('Woocommerce Fields','cart2crm');?></th>
			        <th><?php _e('SugarCRM Fields','cart2crm');?></th>
			       
			        <th><?php _e('Delete','cart2crm');?></th>
		        </tr>	
		      <?php 
		        	$table_name = "cart2crm_leads";
		        	$record = $db->getcrmData($table_name,2);
		        	foreach($record as $retrive_data)
		        	{
		        		?>
		        			       	<tr>
		        			       		<td><?php echo $retrive_data->order;?></td>
		        			       		<td><?php echo $retrive_data->sugarcrm;?></td>
		        			       		
		        			       		<td><a href="?page=crm_field_mapping&tab=Leads&action=delete&id=<?php echo $retrive_data->ID;?>">
		        			       		<img class="dele" src="<?php echo plugins_url('Cart2CRM/assets/images/delete.png')?> "  style="cursor: pointer; cursor: hand;"
		        			       		 onclick="return confirm('<?php _e('Are you sure you want to delete this record?','cart2crm');?>');"/>
		        			       		<?php // _e('Delete','cart2crm');?></a></td>
		        			       	</tr>
		        			       	<?php 
		        			       }
		        }
		        
		       
		        ?>          
	        </tbody>
        </table>
	</div>
<?php 
}
else
{	
	_e("Connection establised error",'cart2crm');
}

?>

</div>
<?php 
?>