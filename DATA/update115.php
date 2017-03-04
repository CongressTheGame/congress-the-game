<?php

include 'connect.php';

function sanitize($data) {
    return mysql_real_escape_string($data);
}

function array_sanitize(&$item) {
	$item = mysql_real_escape_string($item);
}

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
                       
function cosponsors_update(){

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

		$cosponsor_data = array();
	   foreach($bill->cosponsors->cosponsor as $cosponsor){
		$cosponsor_data = array (
			'bill_id'    	=> 	$bill_id,
			'type'		 	=> (string)$bill['type'],
			'cosponsor_id'	=>	(string)$cosponsor['id'],
			'joined'		=>	(string)$cosponsor['joined'],
			'enacted'		=> (string)$bill->actions->enacted['datetime']
		);  
		array_walk($cosponsor_data, 'array_sanitize');	

     		$cosponsors_data = '\'' . implode('\', \'', $cosponsor_data) . '\'';
			$cosponsors_array[]="(".$cosponsors_data.")";
		        if (count($cosponsors_array) > 500){
                        mysql_query("INSERT INTO `cosponsors`(`bill_id`,`type`,`cosponsor_id`,`joined`,`enacted`) VALUES " . implode(", ", $cosponsors_array)." ON DUPLICATE KEY UPDATE `bill_id`=VALUES(`bill_id`),`type`=VALUES(`type`),`cosponsor_id`=VALUES(`cosponsor_id`),`joined`=VALUES(`joined`),`enacted`=VALUES(`enacted`)") or die('COSPONSORS: '.mysql_error());
                        $cosponsors_array = array();
				}}}}}
			mysql_query("INSERT INTO `cosponsors`(`bill_id`,`type`,`cosponsor_id`,`joined`,`enacted`) VALUES " . implode(", ", $cosponsors_array)." ON DUPLICATE KEY UPDATE `bill_id`=VALUES(`bill_id`),`type`=VALUES(`type`),`cosponsor_id`=VALUES(`cosponsor_id`),`joined`=VALUES(`joined`),`enacted`=VALUES(`enacted`)") or die('COSPONSORS: '.mysql_error());
                        $cosponsors_array = array();
}	

function votes_update(){

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

		$vote_data = array();

foreach($bill->actions->vote as $vote){
		   
		   if((string)$vote['result'] = 'pass' && ((string)$vote['state']==='PASS_OVER:HOUSE'|(string)$vote['state']==='PASS_BACK:HOUSE'|(string)$vote['state']==='PASS_OVER:SENATE'|(string)$vote['state']==='PASS_BACK:SENATE'|(string)$vote['state']==='PASSED:BILL'|(string)$vote['type']==='conference')){
			 	   
		$vote_data = array(
			'bill_id'    		=> $bill_id,
		    	'how' 			=> (string)$vote['how'],
			'type'			=> (string)$vote['type'],
			'roll'			=> (string)$vote['roll'],
			'datetime'		=> (string)$vote['datetime'],
			'where'			=> (string)$vote['where'],
			'result'		=> (string)$vote['result'],
			'state'			=> (string)$vote['state'], 
			);  
			array_walk($vote_data, 'array_sanitize');	

     		$vote_data= '\'' . implode('\', \'', $vote_data) . '\'';
			$vote_array[]="(".$vote_data.")";
		        if (count($vote_array) > 20){
                        mysql_query("INSERT INTO `votes`(`bill_id`,`how`,`type`,`roll`,`datetime`,`where`,`result`,`state`) VALUES " . implode(", ", $vote_array)." ON DUPLICATE KEY UPDATE `bill_id`=VALUES(`bill_id`),`how`=VALUES(`how`),`type`=VALUES(`type`),`roll`=VALUES(`roll`),`datetime`=VALUES(`datetime`),`where`=VALUES(`where`),`result`=VALUES(`result`),`state`=VALUES(`state`)") or die('VOTES: '.mysql_error());
                        $vote_array= array();
     				}}}}}}
                        mysql_query("INSERT INTO `votes`(`bill_id`,`how`,`type`,`roll`,`datetime`,`where`,`result`,`state`) VALUES " . implode(", ", $vote_array)." ON DUPLICATE KEY UPDATE `bill_id`=VALUES(`bill_id`),`how`=VALUES(`how`),`type`=VALUES(`type`),`roll`=VALUES(`roll`),`datetime`=VALUES(`datetime`),`where`=VALUES(`where`),`result`=VALUES(`result`),`state`=VALUES(`state`)") or die('VOTES: '.mysql_error());
                        $vote_array= array();			   
	  
}

function rolls_update(){

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

		$roll_data= array();

foreach($bill->actions->vote as $vote){
		   
		   if((string)$vote['result'] = 'pass' && ((string)$vote['state']==='PASS_OVER:HOUSE'|(string)$vote['state']==='PASS_BACK:HOUSE'|(string)$vote['state']==='PASS_OVER:SENATE'|(string)$vote['state']==='PASS_BACK:SENATE'|(string)$vote['state']==='PASSED:BILL'|(string)$vote['type']==='conference')){
			   if((string)$vote['how']='roll'){
				   $rollname = (string)$vote['where'].substr((string)$vote['datetime'],0,4)."-".(string)$vote['roll'];
				 	$roll = simplexml_load_file('/home3/congreu6/DATA/rolls/'.$rollname.'.xml');
				foreach($roll->voter as $voter){	
					$roll_data = array(		
			'roll_id'    		=> (string)$rollname,
			'bill_id'		=> (string)$roll->bill['type'].(string)$roll->bill['number'],
			'member_id'		=> (int)$voter['id'],
			'datetime'		=> (string)$roll['datetime'],
			'vote'	 		=> (string)$voter['vote'],
			'result'	 	=> (string)$roll->result,
			);
			array_walk($vote_data, 'array_sanitize');	

     		$roll_data= '\'' . implode('\', \'', $roll_data) . '\'';
			$roll_array[]="(".$roll_data.")";
		        if (count($roll_array) > 500){
                        mysql_query("INSERT INTO `rolls`(`roll_id`,`bill_id`,`member_id`,`datetime`,`vote`,`result`) VALUES " . implode(", ", $roll_array)." ON DUPLICATE KEY UPDATE `roll_id`=VALUES(`roll_id`),`bill_id`=VALUES(`bill_id`),`member_id`=VALUES(`member_id`),`datetime`=VALUES(`datetime`),`vote`=VALUES(`vote`),`result`=VALUES(`result`)") or die('ROLL: '.mysql_error());
                        $roll_array= array();
     				}}}}}}}}
                        mysql_query("INSERT INTO `rolls`(`roll_id`,`bill_id`,`member_id`,`datetime`,`vote`,`result`) VALUES " . implode(", ", $roll_array)." ON DUPLICATE KEY UPDATE `roll_id`=VALUES(`roll_id`),`bill_id`=VALUES(`bill_id`),`member_id`=VALUES(`member_id`),`datetime`=VALUES(`datetime`),`vote`=VALUES(`vote`),`result`=VALUES(`result`)") or die('ROLL: '.mysql_error());
                        $roll_array= array();			   
	 
}
bills_update();
cosponsors_update();
votes_update();	  
rolls_update();
?>