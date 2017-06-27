<?php 
//CRM-Panel
$suitecrm_options=get_option( 'suitecrm_logininfo');
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
$sugarcrm_conection = $soapclient->call('login',$user_auth); 

$session_id = $sugarcrm_conection['id'];
/* if (!defined('sugarEntry')) define('sugarEntry', true);
$options = array(
    "location" => $suite_crm_option['crm_url'] .'soap.php',
    "uri" => $suite_crm_option['crm_url'],
    "trace" => 1
    );
    
    $user_auth = array(
    "user_name" => $suite_crm_option['user_name'],
    "password" => md5($suite_crm_option['password']),
    "version" => '0.1'
    );
   
    $soapClient = new SoapClient(NULL, $options);
  var_dump($soapClient); */
    //$response = $soapClient->login($user_auth,"admin");
    $suitecrm_session_id = $session_id;
   
   // $result = $soapClient->seamless_login($suitecrm_session_id);
	
?>
<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="?page=crm_connection&amp;tab=SugarCRMConnection"><?php _e('Suite CRM Panel','cart2crm')?></a>    
</h2>

<iframe style="width:100%; height: 1400px;" src="<?php echo $suitecrm_options['crm_url'].'index.php?module=Home&action=index&MSID='.$suitecrm_session_id;?>" scrolling="no" frameborder="0" ></iframe>




</div>
<?php 
?>