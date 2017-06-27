<?php 
//CRM-fieldmapping
//CRM type 1 = cusatom
//CRM type 0 = Sugarcrm
$iscustom_connection = 0;
require_once CRM_PLUGIN_DIR . '/admin/includes/class-customcrm.php';
$customcrm_result=get_option( 'customcrm_logininfo');
$db = new Cart2CRM();
$custom_connection = new CustomCRM();
if($custom_connection->iscustom_connection())
{
	$iscustom_connection = 1;
}

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
	$customcrm_table = $_REQUEST['custom_tbname'];
	$customcrm_field = $_REQUEST['custom_field'];
	$crmtype = 1;
	if($current_tab == 'Leads')
	{
	$customcrm_data = array('order'=>$wc_field,
							'sugarcrm' =>$customcrm_field,
							'tbname' =>$customcrm_table,
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
				'tbname' =>$customcrm_table,
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
				'tbname' =>$customcrm_table,
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
		$leads_data=$db->getLeadsData($table_name,1);
		$array_data1 =array();
		$fetch_all_data=$db->getExistleadProductId(1);
		
		if(!empty($fetch_all_data))
		{
			foreach($fetch_all_data as $post)
			{
				$post_meta=$db->getPostmeta($post->id);
				foreach($post_meta as $metadata){
					
					$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
				}
				
				$fields="";
				$values="";
				foreach($leads_data as $sy){
					$fields.=$sy->sugarcrm.",";
					$values.="'".$array_data1[$post->id][$sy->order]."',";
				}
				$fields=substr($fields,0,-1);
				$values=substr($values,0,-1);
				//$custom_connection->customdb->query("insert into ".$sy->tbname." (".$fields.") values (".$values.")");
				$custom_connection->insert_data($fields,$values,$sy->tbname);
				//$lid= $customdb->insert_id;
				$cldate = date('Y-m-d H:i:s');
				$wp_in=$db->addLeadsCustomCRM($post->id,1,$cldate,1);
			}
		}
		
	
	}
	if($current_tab == 'Opportunity')
	{
		$table_name = "cart2crm_opportunity";
		$leads_data=$db->getLeadsData($table_name,1);
		$array_data1 =array();
		$fetch_all_data=$db->getExistopportunityProductId(1);
		//var_dump($leads_data);
		if(!empty($fetch_all_data))
		{
			foreach($fetch_all_data as $post)
			{
				$post_meta=$db->getPostmeta($post->id);
				foreach($post_meta as $metadata){
					$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
				}
	
				$fields="";
				$values="";
				foreach($leads_data as $sy){
					$fields.=$sy->opportunity.",";
					$values.="'".$array_data1[$post->id][$sy->order]."',";
				}
				$fields=substr($fields,0,-1);
				$values=substr($values,0,-1);
				//$custom_connection->customdb->query("insert into ".$sy->tbname." (".$fields.") values (".$values.")");
				$custom_connection->insert_data($fields,$values,$sy->tbname);
				//echo "<BR> TABLE ".$sy->tbname;
				//$lid= $customdb->insert_id;
				$cldate = date('Y-m-d H:i:s');
				$wp_in=$db->addOpportunityCustomCRM($post->id,1,$cldate,1);
			}
		}
	
	
	
	
	}
	if($current_tab == 'Account')
	{
		$table_name = "cart2crm_account";
		$leads_data=$db->getLeadsData($table_name,1);
		$array_data1 =array();
		$fetch_all_data=$db->getExistaccountProductId(1);
	
		if(!empty($fetch_all_data))
		{
			foreach($fetch_all_data as $post)
			{
				$post_meta=$db->getPostmeta($post->id);
				foreach($post_meta as $metadata){
					$array_data1[$post->id][$metadata->meta_key] = $metadata->meta_value;
				}
	
				$fields="";
				$values="";
				foreach($leads_data as $sy){
					$fields.=$sy->accounts.",";
					$values.="'".$array_data1[$post->id][$sy->order]."',";
				}
				$fields=substr($fields,0,-1);
				$values=substr($values,0,-1);
				//$custom_connection->customdb->query("insert into ".$sy->tbname." (".$fields.") values (".$values.")");
				$custom_connection->insert_data($fields,$values,$sy->tbname);
				//$lid= $customdb->insert_id;
				$cldate = date('Y-m-d H:i:s');
				$wp_in=$db->addaccountCustomCRM($post->id,1,$cldate,1);
			}
		}
	
	
	
	
	}
	echo  "<div style='background: none repeat scroll 0 0 green;color: white;float: left;font-size: 17px;margin-top: 25px;padding: 10px;width: 98%; margin-bottom: 10px;'>Syncronize your data sucessfully</div>";
	
}
?>
<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="#"><?php _e('Custom Field Mapping','cart2crm')?></a>    
</h2>
<?php 
//$lwpdb = new wpdb( $user, $pass, $db, $host );
$custom_db = new wpdb($customcrm_result['user_name'],$customcrm_result['password'],$customcrm_result['database_name'],$customcrm_result['host_name']);
//$custom_db = new wpdb('root','','crm','localhost');


if($custom_db->dbh)
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
					</td>
					<td><img src="<?php echo plugins_url('Cart2CRM/assets/images/map.png') ?>" class="maps"/></td>
					<td class="title"><?php _e('Customcrm','cart2crm');?></td>
					<td>
				        <?php
						$tables=$db->getTable($customcrm_result['database_name']);
				        ?>
				        <select name="custom_tbname" id="select_table" class="regular-text" >
				        <option value="">---Select Table---</option>
				        <?php 
				        foreach($tables as $table)
				        {
				            echo '<option value="'.$table[0].'">'.$table[0].'</option>';
				        }
				        ?></select>
			        <td>
			        <td>
				        <div id="fieldlist">
					        <select name="custom_field">
					        <option value=""></option>
					        </select>
				        </div>
			        </td>
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
	        	<th colspan="4"><?php _e('Opportunity fields','cart2crm');?></th>
	        </tr>
	        </thead>
	        <tbody>
		        <tr>
			        <th><?php _e('Woocommerce Fields','cart2crm');?></th>
			        <th><?php _e('CustomCRM Fields','cart2crm');?></th>
			        <th><?php _e('Table Name','cart2crm');?></th>
			        <th><?php _e('Delete','cart2crm');?></th>
		        </tr>	
		      <?php 
		        	$table_name = "cart2crm_opportunity";
		        	$record = $db->getcustomcrmData($table_name);
		        	foreach($record as $retrive_data)
		        	{
		        		?>
		        			       	<tr>
		        			       		<td><?php echo $retrive_data->order;?></td>
		        			       		<td><?php echo $retrive_data->opportunity;?></td>
		        			       		<td><?php echo $retrive_data->tbname;?></td>
		        			       		<td><a href="?page=crm_field_mapping&tab=Opportunity&action=delete&id=<?php echo $retrive_data->ID;?>">
		        			       		<img class="dele" src="<?php echo plugins_url('Cart2CRM/assets/images/delete.png')?> "  style="cursor: pointer; cursor: hand;" 
		        			       		 onclick="return confirm('<?php _e('Are you sure you want to delete this record?','cart2crm');?>');"/>
		        			       		<?php //_e('Delete','cart2crm');?></a></td>
		        			       	</tr>
		        			       	<?php 
		        			       }
		        }
		        elseif($active_tab == 'Account')
		        {?>
	        <thead>
	        <tr>
	        	<th colspan="4"><?php _e('Account fields','cart2crm');?></th>
	        </tr>
	        </thead>
	        <tbody>
		        <tr>
			        <th><?php _e('Woocommerce Fields','cart2crm');?></th>
			        <th><?php _e('CustomCRM Fields','cart2crm');?></th>
			        <th><?php _e('Table Name','cart2crm');?></th>
			        <th><?php _e('Delete','cart2crm');?></th>
		        </tr>	
		      <?php 
		        	$table_name = "cart2crm_account";
		        	$record = $db->getcustomcrmData($table_name);
		        	foreach($record as $retrive_data)
		        	{
		        		?>
		        			       	<tr>
		        			       		<td><?php echo $retrive_data->order;?></td>
		        			       		<td><?php echo $retrive_data->accounts;?></td>
		        			       		<td><?php echo $retrive_data->tbname;?></td>
		        			       		<td><a href="?page=crm_field_mapping&tab=Account&action=delete&id=<?php echo $retrive_data->ID;?>">
		        			       		<img class="dele" src="<?php echo plugins_url('Cart2CRM/assets/images/delete.png')?> "  style="cursor: pointer; cursor: hand;" 
		        			       		 onclick="return confirm('<?php _e('Are you sure you want to delete this record?','cart2crm');?>');"/>
		        			       		<?php// _e('Delete','cart2crm');?></a></td>
		        			       	</tr>
		        			       	<?php 
		        			       }
		        }
		        else
		        {?>
	        <thead>
	        <tr>
	        	<th colspan="4"><?php _e('Leads fields','cart2crm');?></th>
	        </tr>
	        </thead>
	        <tbody>
		        <tr>
			        <th><?php _e('Woocommerce Fields','cart2crm');?></th>
			        <th><?php _e('CustomCRM Fields','cart2crm');?></th>
			        <th><?php _e('Table Name','cart2crm');?></th>
			        <th><?php _e('Delete','cart2crm');?></th>
		        </tr>	
		      <?php 
		        	$table_name = "cart2crm_leads";
		        	$record = $db->getcustomcrmData($table_name);
		        	foreach($record as $retrive_data)
		        	{
		        		?>
		        			       	<tr>
		        			       		<td><?php echo $retrive_data->order;?></td>
		        			       		<td><?php echo $retrive_data->sugarcrm;?></td>
		        			       		<td><?php echo $retrive_data->tbname;?></td>
		        			       		<td><a href="?page=crm_field_mapping&tab=Leads&action=delete&id=<?php echo $retrive_data->ID;?>">
		        			       		<img class="dele" src="<?php echo plugins_url('Cart2CRM/assets/images/delete.png')?> "  style="cursor: pointer; cursor: hand;" 
		        			       		 onclick="return confirm('<?php _e('Are you sure you want to delete this record?','cart2crm');?>');"/>
		        			       		<?php //_e('Delete','cart2crm');?>
		        			       		</a></td>
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