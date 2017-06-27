<?php 
//CRM-Panel


$db = new Cart2CRM();

?>
<div class="wrap">
<h2 class="nav-tab-wrapper">  
	<a class="nav-tab nav-tab-active" href="#"><?php _e('Custom CRM Report','cart2crm')?></a>    
</h2>



<table class='widefat picks'>
	<thead>
    	<tr>
         	<th><?php _e('Customer Data Report Generate','cart2crm');?></th>
        </tr>
	</thead>
</table>
<div class='picker'>
	<form method="post" name="reports">
         		<table class="formac">
	                <tbody>
	                <tr valign="top">
	                <td class="title">Customer Data</td>
	                <td>
	                    <select name="custdata">
	                        <option value="opportunity">Opportunity</option>
	                        <option value="account">Account</option>
	                    </select> 
	                </td>
	                 <td class="title">From Date</td>
	                 <td>
	                    <input type="text" id="fromdate" name="fromdate"/>
	                    <script type="text/javascript">
	                    jQuery(document).ready(function() {
	                        jQuery('#fromdate').datepicker({
	                            dateFormat : 'yy-mm-dd'
	                        });
	                    });
	                    </script>
	                 </td>
	                <td class="title">To Date</td>
	                <td>
	                    <input type="text" id="todate" name="todate"/>
	                    <script type="text/javascript">
	                    jQuery(document).ready(function() {
	                        jQuery('#todate').datepicker({
	                            dateFormat : 'yy-mm-dd'
	                        });
	                    });
	                    </script>
	                 </td>
	                <td class="btnsave">
	                    <input type="submit" value="Download Report" name="cust_report" style="cursor: pointer; cursor: hand;" onclick="return validation()" />
	                </td>
	                </tr>
                 </tbody>
           </table>
	</form>
	
<?php 
if(isset($_REQUEST['cust_report']))
{
	global $wpdb;
	define('PATH', plugin_dir_path(__FILE__));
	$status="";
	$fromdate=$_POST['fromdate'];
	$todate=$_POST['todate'];
	
	if($_POST['custdata']=="opportunity")
	{
		$status="on-hold";
	}
	if($_POST['custdata']=="account")
	{
		$status="completed";
	}
	$wp_user_search=$db->getCustomerReport($status,$fromdate,$todate,1);
	if(!empty($wp_user_search))
	{
		$filename='Reports/report.csv';
		$fh = fopen(PATH.$filename, 'w') or die("can't open file");
		foreach ( $wp_user_search as $userid1 ) {
			$values=array();
			$wp_user=$db->getPostmeta($userid1->id);
			foreach($wp_user as $users1){
				$array_data1[$userid1->id][$users1->meta_key] = $users1->meta_value;
			}
			$name=$array_data1[$userid1->id][_billing_first_name]." ".$array_data1[$userid1->id] [_billing_last_name];
			$email=$array_data1[$userid1->id] [_billing_email];
			$mobile=$array_data1[$userid1->id] [_billing_phone];
			$billingadd= $array_data1[$userid1->id] [_billing_address_1].",".$array_data1[$userid1->id] [_billing_city].",".$array_data1[$userid1->id] [_billing_state] .",".$array_data1[$userid1->id] [_billing_postcode].",".$array_data1[$userid1->id] [_billing_country];
			$shippingadd= $array_data1[$userid1->id] [_shipping_address_1].",".$array_data1[$userid1->id] [_shipping_city].",".$array_data1[$userid1->id] [_shipping_state] .",".$array_data1[$userid1->id] [_shipping_postcode].",".$array_data1[$userid1->id] [_shipping_country];
			$values=array($name,$email,$mobile,$billingadd,$shippingadd);
			fputcsv($fh, $values);
		}
		fclose($fh);
	
		//download csv file.
		ob_clean();
		$file=PATH."Reports/report.csv";//file location
		/*header('Content-Type: application/octet-stream');
		 header('Content-Disposition:attachment;filename="'.basename($file).'"');
		 header('Content-Length:"'.filesize($file).'"');*/
		/*header("Pragma: public");
		 header("Expires: 0");
		 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		 header("Cache-Control: public");
		 header("Content-Type: application/octet-stream");
		 header("Content-Disposition: attachment; filename=".basename($file));
		 header("Content-Transfer-Encoding: binary");
		 header('Content-Length:"'.filesize($file).'"');
		 readfile($file);
		 exit();*/
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);
		exit();
	}
	else
	{
		echo "<div style=' background: none repeat scroll 0 0 red;
                border: 1px solid;
                color: white;
                float: left;
                font-size: 17px;
                margin-top: 10px;
                padding: 10px;
                width: 98%;'>Records not found.</div>";
	}
}
?>
</div>
<table class='widefat picks'>
	<thead>
    	<tr>
        	<th><?php _e('Migration Report Generate','cart2crm');?></th>
        </tr>
    </thead>
</table>
    	<form method="post" name="mrreports">
          	<table class="formac">
                <tbody>
                <tr valign="top">
               
                
                 <td class="title">From Date</td>
                 <td>
                    <input type="text" id="mr_fromdate" name="mr_fromdate"/>
                    <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery('#mr_fromdate').datepicker({
                            dateFormat : 'yy-mm-dd'
                        });
                    });
                    </script>
                 </td>
                <td class="title">To Date</td>
                <td>
                    <input type="text" id="mr_todate" name="mr_todate"/>
                    <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery('#mr_todate').datepicker({
                            dateFormat : 'yy-mm-dd'
                        });
                    });
                    </script>
                 </td>
                <td class="btnsave">
                    <input type="submit" value="Download Report" name="migration_report" style="cursor: pointer; cursor: hand;" onclick="return mrvalidation()" />
                </td>
                </tr>
                </tbody>
            </table>
        </form>  
         <?php 
    if(isset($_POST['migration_report']))
    {
            global $wpdb;
            define('PATH', plugin_dir_path(__FILE__));
            $fromdate=$_POST['mr_fromdate'];
            $todate=$_POST['mr_todate'];		
            $crmtype=1;
           	
			$result=$db->getMigrationReport($crmtype,$fromdate,$todate);
            if(!empty($result))
            {
                $filename='Reports/report.csv';
                $fh = fopen(PATH.$filename, 'w') or die("can't open file");
                foreach ( $result as $row ) {
                        fputcsv($fh, $row);
                }
                fclose($fh);
                
                //download csv file.
                ob_clean();
                $file=PATH."reports/report.csv";//file location
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit();
            }
            else
            {
                echo "<div style=' background: none repeat scroll 0 0 red;
                border: 1px solid;
                color: white;
                float: left;
                font-size: 17px;
                margin-top: 10px;
                padding: 10px;
                width: 98%;'>Records not found.</div>";
            }
        }
  


?>

</div>
<?php 
?>