<?php 
if(! class_exists('CustomCRM'))
{
	class CustomCRM
	{
		public $customdb = null;
		public function __construct()
		{
			$result=get_option( 'customcrm_logininfo');
			$this->customdb = new wpdb($result['user_name'],$result['password'],$result['database_name'],$result['host_name']);
		}
		public function get_all_data($tablenm)
		{
			global $wpdb;
			$table_name =  $tablenm;
			return $retrieve_subjects = $this->customdb->get_results( "SELECT * FROM $table_name");
		
		}
		public function iscustom_connection()
		{
			if($this->customdb->dbh)
				return true;
			else
				return false;
		}
		
		public function insert_data($fields,$values,$table_name)
		{
			
			$retrieve_subjects = $this->customdb->query("insert into $table_name (".$fields.") values (".$values.")");
		}
		
	}
}
?>