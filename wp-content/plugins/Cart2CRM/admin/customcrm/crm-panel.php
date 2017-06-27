<?php 
//CRM-Panel
$result=get_option( 'customcrm_logininfo');
?>
<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="?page=crm_connection&amp;tab=SugarCRMConnection"><?php _e('Custom CRM Panel','cart2crm')?></a>    
</h2>
<?php 
//$lwpdb = new wpdb( $user, $pass, $db, $host );
$custom_db = new wpdb($result['user_name'],$result['password'],$result['database_name'],$result['host_name']);
//$custom_db = new wpdb('root','','crm','localhost');
//var_dump($custom_db);
if($custom_db->dbh)
{
?>
<iframe style="width:100%; height: 1400px;" src="<?php if(isset($result['crm_url'])) echo $result['crm_url'];?>" scrolling="no" frameborder="0" ></iframe>
<?php 
}
else
{	
	_e("Connecton establised error",'cart2crm');
}

?>

</div>
<?php 
?>