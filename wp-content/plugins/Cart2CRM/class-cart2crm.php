<?php 
if(!class_exists('Cart2CRM'))
{
	
	class Cart2CRM
	{
		//Get all Woocommerce field
		public function getSinglePost()
		{
			global $wpdb;
			return $wpdb->get_results("SELECT a.id FROM $wpdb->posts as a WHERE a.post_type='shop_order' limit 1");
		}
		//woocommerce  post meta name
		public function getPostmeta($userid1)
		{
			global $wpdb;
			return $wpdb->get_results("SELECT a.* FROM $wpdb->postmeta as a WHERE a.post_id='".$userid1."'");
		}
		//Get all Table by database name
		public function getTable($dbname)
		{
			global $wpdb;
			return $wpdb->get_results("SHOW TABLES FROM ".$dbname,ARRAY_N);
		}
		
		//Get Table column by table name
		public function getTableField($tablename)
		{
			global $wpdb;
			return $wpdb->get_results( "SHOW COLUMNS FROM ".$tablename );
		}
		
		public function insert_record($tablenm,$records)
		{
			global $wpdb;
			$table_name = $wpdb->prefix . $tablenm;
			return $result=$wpdb->insert( $table_name, $records);
		
		}
		public function getcustomcrmData($tablenm)
		{
			global $wpdb;
			
			$table_name = $wpdb->prefix . $tablenm;
			return $wpdb->get_results("select * from $table_name where crmtype= 1");
		}
		
		public function getcrmData($tablenm,$crmtype)
		{
			global $wpdb;
				
			$table_name = $wpdb->prefix . $tablenm;
			return $wpdb->get_results("select * from $table_name where crmtype= $crmtype");
		}
		
		public function isexistInlead($table_name,$data)
		{
			global $wpdb;
			$table_name = $wpdb->prefix . $table_name;
			
			$result = $wpdb->get_results("select * from $table_name where `order` = '".$data['order']."' 
					AND sugarcrm = '".$data['sugarcrm']."' AND tbname = '".$data['tbname']."' 
						AND crmtype=".$data['crmtype']);
			if(empty($result))
				return false;
			else
				return true;
		}
		public function isexistInopportunity($table_name,$data)
		{
			global $wpdb;
			$table_name = $wpdb->prefix . $table_name;
				
			$result = $wpdb->get_results("select * from $table_name where `order` = '".$data['order']."'
					AND opportunity = '".$data['opportunity']."' AND tbname = '".$data['tbname']."'
						AND crmtype=".$data['crmtype']);
			if(empty($result))
				return false;
			else
				return true;
		}
		public function isexistInaccount($table_name,$data)
		{
			global $wpdb;
			$table_name = $wpdb->prefix . $table_name;
				
			$result = $wpdb->get_results("select * from $table_name where `order` = '".$data['order']."'
					AND accounts = '".$data['accounts']."' AND tbname = '".$data['tbname']."'
						AND crmtype=".$data['crmtype']);
			if(empty($result))
				return false;
			else
				return true;
		}
		
		public function getLeadsData($table_name,$crmtype)
		{
			global $wpdb;
			$table_name = $wpdb->prefix . $table_name;
			return $wpdb->get_results("select * from $table_name where crmtype=".$crmtype);
		}
		
		//Check is dublicate record in lead
		public function get_from_cart2crm_sugar_id($order_id,$crmtype)
		{
			global $wpdb;	
		$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';
		$query =  'SELECT * FROM '.$table_sugar_id.' WHERE id = '. $order_id.' AND sugarcrm_insertid != "0" 
		AND leadsid = 1 AND  crmtype='.$crmtype;
      //  $query = $wpdb->prepare('SELECT id FROM '.$table_sugar_id.' WHERE id = %s AND sugarcrm_insertid != 0', $order_id);
        $result = $wpdb->get_row( $query );		
			return  $result;
		}
		public function get_from_cart2crm_sugar_opportunity_id($order_id,$crmtype)
		{
			global $wpdb;	
		$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';
		$query =  'SELECT * FROM '.$table_sugar_id.' WHERE id = '. $order_id.' AND sugarcrm_insertid != "0" 
		AND oppid = 1 AND  crmtype='.$crmtype;
      //  $query = $wpdb->prepare('SELECT id FROM '.$table_sugar_id.' WHERE id = %s AND sugarcrm_insertid != 0', $order_id);
        $result = $wpdb->get_row( $query );		
			return  $result;
		}
		public function get_from_cart2crm_sugar_account_id($order_id,$crmtype)
		{
			global $wpdb;	
		$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';
		$query =  'SELECT * FROM '.$table_sugar_id.' WHERE id = '. $order_id.' AND sugarcrm_insertid != "0" 
		AND acid = 1 AND  crmtype='.$crmtype;
      //  $query = $wpdb->prepare('SELECT id FROM '.$table_sugar_id.' WHERE id = %s AND sugarcrm_insertid != 0', $order_id);
        $result = $wpdb->get_row( $query );		
			return  $result;
		}
		public function is_orderlead_exist($order_id,$crmtype)
		{
			global $wpdb;	
		$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';
		$query =  'SELECT id FROM '.$table_sugar_id.' WHERE id = '. $order_id.' AND sugarcrm_insertid != "0" AND leadsid = 1 AND crmtype = '.$crmtype;
      //  $query = $wpdb->prepare('SELECT id FROM '.$table_sugar_id.' WHERE id = %s AND sugarcrm_insertid != 0', $order_id);
        $result = $wpdb->get_var( $query );
		
		if ( !empty($result) )
			return true;
		else
			return false;
		}
		public function is_orderaccount_exist($order_id,$crmtype)
		{
			global $wpdb;	
		$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';
		$query =  'SELECT id FROM '.$table_sugar_id.' WHERE id = '. $order_id.' AND sugarcrm_insertid != "0" AND acid = 1 AND crmtype ='.$crmtype;
      //  $query = $wpdb->prepare('SELECT id FROM '.$table_sugar_id.' WHERE id = %s AND sugarcrm_insertid != 0', $order_id);
        $result = $wpdb->get_var( $query );
		
		if ( !empty($result) )
			return true;
		else
			return false;
		}
		public function is_orderopportunity_exist($order_id,$crmtype)
		{
			global $wpdb;	
		$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';
		$query =  'SELECT id FROM '.$table_sugar_id.' WHERE id = '. $order_id.' AND sugarcrm_insertid != "0" AND oppid = 1 AND crmtype ='.$crmtype;
      //  $query = $wpdb->prepare('SELECT id FROM '.$table_sugar_id.' WHERE id = %s AND sugarcrm_insertid != 0', $order_id);
        $result = $wpdb->get_var( $query );
		
		if ( !empty($result) )
			return true;
		else
			return false;
		}
		
		
		public function getExistleadProductId($crmtype)
		{
			global $wpdb;
			$table_post = $wpdb->prefix .'posts';
			$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';
			
			 
			return $wpdb->get_results("SELECT a.id FROM $table_post as a WHERE a.post_type='shop_order' AND a.post_status = 'wc-on-hold'");
		
		}
		//Check is dublicate record in opportunity
		public function getExistopportunityProductId($crmtype)
		{
			global $wpdb;
			$table_post = $wpdb->prefix .'posts';
			$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';			
				
			return $wpdb->get_results("SELECT a.id FROM $table_post as a WHERE a.post_type='shop_order' AND a.post_status = 'wc-processing'");
		
		}
		//Check is dublicate record in account
		public function getExistaccountProductId($crmtype)
		{
			global $wpdb;
			$table_post = $wpdb->prefix .'posts';
			$table_sugar_id = $wpdb->prefix . 'cart2crm_sugar_id';
				
				
			return $wpdb->get_results("SELECT a.id FROM $table_post as a WHERE a.post_type='shop_order' AND a.post_status = 'wc-completed'");
		
		}
		// Leads
		public function addLeadsCustomCRM($postid,$lid,$cldate,$crmtype,$result_id = 0)
		{
			global $wpdb;
			$table_name = $wpdb->prefix .'cart2crm_sugar_id';
			$query = "insert into  $table_name (id,leadsid,cldate,crmtype,sugarcrm_insertid) values ('".$postid."',".$lid.",'".$cldate."',".$crmtype.",'".$result_id."')";		
			$add_data['id'] = $postid;
			$add_data['leadsid'] = $lid;
			$add_data['cldate'] = $cldate;
			$add_data['crmtype'] = $crmtype;
			$add_data['sugarcrm_insertid'] = $result_id;
			
			return $wpdb->insert($table_name,$add_data);
		}
		//Opportunity
		public function addopportunityCustomCRM($postid,$lid,$cldate,$crmtype,$result_id=0)
		{
			global $wpdb;
			$table_name = $wpdb->prefix .'cart2crm_sugar_id';
			
			return $wpdb->query("insert into  $table_name (id,oppid,cldate,crmtype,sugarcrm_insertid) values ('".$postid."',".$lid.",'".$cldate."',".$crmtype.",'".$result_id."')");
		}
		//account
		public function addaccountCustomCRM($postid,$lid,$cldate,$crmtype,$result_id=0)
		{
			global $wpdb;
			$table_name = $wpdb->prefix .'cart2crm_sugar_id';
			
			return $wpdb->query("insert into  $table_name (id,acid,cldate,crmtype,sugarcrm_insertid) values ('".$postid."',".$lid.",'".$cldate."',".$crmtype.",'".$result_id."')");
		}
		
		public function deleteField($table_name,$id)
		{
			global $wpdb;
			$table_name = $wpdb->prefix .$table_name;
			return $wpdb->query("DELETE FROM $table_name WHERE ID = ".$id);
		}
		
		//Integration ONON
		public function getOnonIntegration($crmtype)
		{
			global $wpdb;
			$table_sugar_id = $wpdb->prefix.'cart2crm_sugar_id';
			$table_posts = $wpdb->prefix.'posts';
			return $wpdb->get_results("SELECT DISTINCT  id FROM $table_posts WHERE `post_type` = 'shop_order'  
					 and id not in(select id from $table_sugar_id where crmtype= $crmtype)");
		}
		
		//Integration ONOFF, OFFON
		public function getOnoffIntegration($status,$crmtype)
		{
			global $wpdb;
			$table_sugar_id = $wpdb->prefix.'cart2crm_sugar_id';
			$table_posts = $wpdb->prefix.'posts';
			return $wpdb->get_results("SELECT DISTINCT  id FROM $table_posts WHERE `post_type` = 'shop_order'  
					and post_status LIKE '%$status%'   and id not in(select id from $table_sugar_id where crmtype= $crmtype)");
		}
		
		public function addCRMID($userid1,$ls,$op,$ac,$date,$cldate,$crmtype,$crm_resulrid=0)
		{
			
			global $wpdb;
			$table_name = $wpdb->prefix .'cart2crm_sugar_id';
			return $wpdb->query("insert into $table_name (id,leadsid,oppid,acid,date,cldate,crmtype,sugarcrm_insertid) values ('".$userid1."','".$ls."','".$op."','".$ac."','".$date."','".$cldate."',".$crmtype.",'".$crm_resulrid."')");
		}
		
		public function getPostModified($userid1)
		{
			global $wpdb;
			return $wpdb->get_results("select post_modified from $wpdb->posts where ID = '".$userid1."'");
		}
		
		// Reports
		public function getCustomerReport($status,$fromdate,$todate,$crmtype)
		{
			global $wpdb;
			$table_sugar_id = $wpdb->prefix.'cart2crm_sugar_id';
			$table_posts = $wpdb->prefix.'posts';
			return $wpdb->get_results("SELECT DISTINCT id FROM $table_posts WHERE `post_type` = 'shop_order'  
					and post_status LIKE '%$status%'  and id in
					(select id from  $table_sugar_id where crmtype = $crmtype AND cldate between '".$fromdate."' and '".$todate."')");
		}
		
		public function getMigrationReport($crmtype,$fromdate,$todate)
		{
			global $wpdb;
			$table_sugar_id = $wpdb->prefix.'cart2crm_sugar_id';
			return $wpdb->get_results("select id,leadsid,oppid,acid,date,cldate from
					$table_sugar_id where crmtype=".$crmtype." and cldate between '".$fromdate."' and '".$todate."'",ARRAY_N);
		}
	}
}
?>