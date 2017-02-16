<?php 
function update_bills(){	
 
$dirname = "/Applications/MAMP/htdocs/bills/update";
$dir = opendir($dirname);
 
while(false != ($file = readdir($dir)))
        { $files[]=$file;
        }
          natsort($files);	
 
          foreach($files as $bill){
            if(($bill != ".") and ($bill != "..") and ($bill != ".DS_Store") and (strstr($bill,'hc')===false)and (strstr($bill,'hj')===false) and (strstr($bill,'hr')===false) and (strstr($bill,'sc')===false) and (strstr($bill,'sj')===false) and (strstr($bill,'sr')===false))
                {  
			$bill_data= array(); 
//TO GET BASIC INFORMATION ABOUT THE BILL			
	$bill = simplexml_load_file('http://www.govtrack.us/data/us/112/bills/'.$bill);
        $bill_data = array(
			'bill_id'    	=> $bill['type'].$bill['number'],
			'type'		 	=> (string)$bill['type'],
			'title'			=> mysql_real_escape_string($bill->titles->title),
			'sponsor' 		=> $bill->sponsor['id'],
			'introduced'	=> $bill->introduced['datetime'],
			'enacted'		=> $bill->actions->enacted['datetime']
		);
 
        $update = array();
	array_walk($bill_data, 'array_sanitize');	
//TO GET INFORMATION RELATED TO COSPONSORS OF BILL	
		$cosponsor_data = array();
	   foreach($bill->cosponsors->cosponsor as $cosponsor){
		$cosponsor_data = array (
			'bill_id'    	=> 	$bill['type'].$bill['number'],
			'type'		 	=> (string)$bill['type'],
			'cosponsor_id'	=>	(string)$cosponsor['id'],
			'joined'		=>	(string)$cosponsor['joined'],
			'enacted'		=> (string)$bill->actions->enacted['datetime']
		);  
			$cosponsors_fields = '`' . implode('`, `', array_keys($cosponsor_data)) . '`';
     		$cosponsors_data = '(\'' . implode('\', \'', $cosponsor_data) . '\')';
     		foreach($cosponsor_data as $cosponsor_fields_update=>$cosponsor_data_update){
            	$cosponsor_update[] = '`' . $cosponsor_fields_update . '` = \'' . $cosponsor_data_update. '\'';
			}				$cosponsor_array[]=$cosponsors_data;	

		}
			   
//TO GET INFORMATION RELATED TO VOTES ON THE BILL AND ROLL CALL VOTE DETAILS		   
	   foreach($bill->actions->vote as $vote){
		   if((string)$vote['result'] = 'pass'){
			   if((string)$vote['how']='roll'){
				   $rollname = (string)$vote['where'].substr((string)$vote['datetime'],0,4)."-".(string)$vote['roll'];
				 	$roll = simplexml_load_file('http://www.govtrack.us/data/us/112/rolls/'.$rollname.'.xml');
				foreach($roll->voter as $voter){	
					$roll_data = array(		
			'roll_id'    	=> (string)$rollname,
			'bill_id'		=> (string)$roll->bill['type'].(string)$roll->bill['number'],
			'member_id'		=> (int)$voter['id'],
			'datetime'		=> (string)$roll['datetime'],
			'vote'	 		=> (string)$voter['vote'],
			'result'	 	=> (string)$roll->result,
		);
			$rolls_fields = '`' . implode('`, `', array_keys($roll_data)) . '`';
     		$rolls_data = '(\'' . implode('\', \'', $roll_data) . '\')';
     		foreach($roll_data as $rolls_fields_update=>$rolls_data_update){
            	$roll_update[] = '`' . $rolls_fields_update . '` = \'' . $rolls_data_update. '\'';}
				$rolls_array[] = $rolls_data;
					}
			   }
		$vote_data = array();
		$vote_data = array(
		    'bill_id'		=> $bill['type'].$bill['number'],
		    'how' 			=> (string)$vote['how'],
			'type'			=> (string)$vote['type'],
			'roll'			=> (string)$vote['roll'],
			'datetime'		=> (string)$vote['datetime'],
			'where'			=> (string)$vote['where'],
			'result'		=> (string)$vote['result'],
			'state'			=> (string)$vote['state'], 
			);  
			$votes_fields = '`' . implode('`, `', array_keys($vote_data)) . '`';
     		$votes_data = '(\'' . implode('\', \'', $vote_data) . '\')';
     		foreach($vote_data as $votes_fields_update=>$votes_data_update){
            	$votes_update[] = '`' . $votes_fields_update . '` = \'' . $votes_data_update. '\'';}
				$votes_array[]=$votes_data;
					   }
	   }
//TO UPDATE THE BASIC BILL INFORMATION	   

			$bills_fields = '`' . implode('`, `', array_keys($bill_data)) . '`';
     		$bills_data = '(\'' . implode('\', \'', $bill_data) . '\')';
     		$bills_array[]=$bills_data;	
				}
				}
			$cosponsors_array = implode(',', $cosponsor_array);
			$rolls_array = implode(',', $rolls_array);
			$votes_array = implode(',', $votes_array);
			$bills_array = implode(',', $bills_array);
 
			mysql_query("INSERT INTO `cosponsors`($cosponsors_fields) VALUES $cosponsors_array ON DUPLICATE KEY UPDATE bill_id=VALUES(bill_id),type=VALUES(type),cosponsor_id=VALUES(cosponsor_id),joined=VALUES(joined),enacted=VALUES(enacted)") or die('COSPONSORS: '.mysql_error());
			
			mysql_query("INSERT INTO `rolls`($rolls_fields) VALUES $rolls_array ON DUPLICATE KEY UPDATE roll_id=VALUES(roll_id),bill_id=VALUES(bill_id),member_id=VALUES(member_id),datetime=VALUES(datetime),vote=VALUES(vote),result=VALUES(result)") or die('ROLLS: '.mysql_error());
			
			mysql_query("INSERT INTO `votes`($votes_fields) VALUES $votes_array ON DUPLICATE KEY UPDATE bill_id=VALUES(bill_id),how=VALUES(how),type=VALUES(type),roll=VALUES(roll),datetime=VALUES(datetime),`where`=VALUES(`where`),result=VALUES(result),state=VALUES(state)") or die('VOTES: '.mysql_error());
 
			mysql_query("INSERT INTO `bills`($bills_fields) VALUES $bills_array ON DUPLICATE KEY UPDATE bill_id=VALUES(bill_id),type=VALUES(type),title=VALUES(title),sponsor=VALUES(sponsor),introduced=VALUES(introduced),enacted=VALUES(enacted)") or die('BILLS: '.mysql_error());
 
} 



function test_bills(){	
 
$dirname = "/Applications/MAMP/htdocs/bills/";
$dir = opendir($dirname);
$file= '/Applications/MAMP/htdocs/billsbillsbills.txt';
$fh = fopen($file, 'w');


while(false != ($file = readdir($dir)))
        { $files[]=$file;
        }
          natsort($files);	
		  
          foreach($files as $bill){
            if(($bill != ".") and ($bill != "..") and ($bill != ".DS_Store")){
				if((strstr($bill,'hc')===false)and (strstr($bill,'hj')===false) and (strstr($bill,'hr')===false) and (strstr($bill,'sc')===false) and (strstr($bill,'sj')===false) and (strstr($bill,'sr')===false))
                {  
				
			$bill_data= array(); 
	$bill = simplexml_load_file('http://www.govtrack.us/data/us/112/bills/'.$bill);
        $bill_data = array(
			'bill_id'    	=> $bill['type'].$bill['number'],
			'title'			=> mysql_real_escape_string($bill->titles->title),
			'type'		 	=> (string)$bill['type'],
			'sponsor' 		=> $bill->sponsor['id'],
			'introduced'	=> $bill->introduced['datetime'],
			'enacted'		=> $bill->actions->enacted['datetime']
		);
 
        $update = array();
	array_walk($bill_data, 'array_sanitize');	
				
			$bills_fields = implode(',', array_keys($bill_data));
     		$bills_data = implode('|', $bill_data) . "\r\n";
				
			$bills_array[]=$bills_data;	
				}}}
				$bills_array = implode($bills_array);
			fwrite($fh,"$bills_array");

fwrite($fh, $string);
fclose($fh);		  
}	  

function text_files(){	
 
$dirname = "/Applications/MAMP/htdocs/bills/";
$dir = opendir($dirname);



while(false != ($file = readdir($dir)))
        { $files[]=$file;
        }
          natsort($files);	
		  
          foreach($files as $bill){
            if(($bill != ".") and ($bill != "..") and ($bill != ".DS_Store")){
				if((strstr($bill,'hc')===false)and (strstr($bill,'hj')===false) and (strstr($bill,'hr')===false) and (strstr($bill,'sc')===false) and (strstr($bill,'sj')===false) and (strstr($bill,'sr')===false))
                {  
				$bill=substr($bill,0,-4);
			$file= '/Applications/MAMP/htdocs/bill/'.$bill.'.txt';
            $fh = fopen($file, 'w');
			fwrite($fh,"");
            fclose($fh);		  

				}}}

}	  
?>