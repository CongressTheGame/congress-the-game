<?php 
include 'connect.php';

function bills_update() {
$dirname = "/home3/congreu6/DATA/bills";
$dir = opendir($dirname);

while(false != ($file = readdir($dir)))
        { $files[]=$file; 
        }
          natsort($files);	
	foreach($files as $bill){
            	if(($bill != ".") and ($bill != "..") and ($bill != ".DS_Store")){
			if((strstr($bill,'hc')===false)and (strstr($bill,'hj')===false) and (strstr($bill,'hr')===false) and (strstr($bill,'sc')===false) and (strstr($bill,'sj')===false) and (strstr($bill,'sr')===false)) {  
			$bill_data= array(); 
			$bill = substr($bill, 0, -4);
			$bill_id=$bill;
			$bill = simplexml_load_file('/home3/congreu6/DATA/bills/'.$bill.'.xml');
			
        		$bill_data = array(
				'bill_id'    		=> $bill_id,
				'type'		 	=> (string)$bill['type'],
				'title'			=> mysql_real_escape_string($bill->titles->title),
				'sponsor' 		=> $bill->sponsor['id'],
				'introduced'		=> $bill->introduced['datetime'],
				'enacted'		=> $bill->actions->enacted['datetime'],
			);
			array_walk($bill_data, 'array_sanitize');	
				
     			$bills_data = '\'' . implode('\', \'', $bill_data) . '\'';
     			
			$bills_array[]="(".$bills_data.")";
	                if (count($bills_array) > 100){
                        mysql_query("INSERT INTO `bills`(`bill_id`,`type`,`title`,`sponsor`,`introduced`,`enacted`) VALUES " . implode(", ", $bills_array)." ON DUPLICATE KEY UPDATE `bill_id`=VALUES(`bill_id`),`type`=VALUES(`type`),`title`=VALUES(`title`),`sponsor`=VALUES(`sponsor`),`introduced`=VALUES(`introduced`),`enacted`=VALUES(`enacted`)") or die('BILLS: '.mysql_error());
                       $bills_array = array();
				}}}}
			mysql_query("INSERT INTO `bills`(`bill_id`,`type`,`title`,`sponsor`,`introduced`,`enacted`) VALUES " . implode(", ", $bills_array)." ON DUPLICATE KEY UPDATE `bill_id`=VALUES(`bill_id`),`type`=VALUES(`type`),`title`=VALUES(`title`),`sponsor`=VALUES(`sponsor`),`introduced`=VALUES(`introduced`),`enacted`=VALUES(`enacted`)") or die('BILLS: '.mysql_error());
                       $bills_array = array(); 
                       }
                    
bills_update();
?>                    