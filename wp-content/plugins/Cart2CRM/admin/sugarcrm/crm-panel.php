<?php 
//CRM-Panel
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
 $response = $soapclient->call("login",$user_auth);
 
 $sugarcrm_session_id = $response['id'];

   
	
?>
<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="?page=crm_connection&amp;tab=SugarCRMConnection"><?php _e('Sugar CRM Panel','cart2crm')?></a>    
</h2>

<iframe style="width:100%; height: 1400px;" src="<?php echo $sugarcrm_options['crm_url'].'index.php?module=Home&action=index&MSID='.$sugarcrm_session_id;?>" scrolling="no" frameborder="0" ></iframe>




</div>
<?php 
?>