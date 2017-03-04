<?php 
include 'connect.php';

function sanitize($data) {
    return mysql_real_escape_string($data);
}

function array_sanitize(&$item) {
	$item = mysql_real_escape_string($item);
}

function member_update(){
	mysql_query("UPDATE `members` SET current = '0'"); 
        $member_data= array(); 
	$xml = simplexml_load_file('https://www.govtrack.us/api/v2/role?current=true&format=xml&role_type=senator&role_type=representative&fields=person__id,person__firstname,person__middlename,person__lastname,person__name,title,state,district,role_type,startdate,enddate,party,current,senator_class&limit=600');
	foreach($xml->objects->item as $item) {
	
		$person = $item->person;
					
		if ($item->role_type=='senator'){
		$type = "sen";
		} elseif ($item->role_type=='representative'){
		$type = "rep";
		} else {
		$type = $item->role_type;
		}

		if ($item->senator_class=='class1'){
		$senclass = "1";
		} elseif ($item->senator_class=='class2'){
		$senclass = "2";
		} elseif ($item->senator_class=='class3') {
		$senclass = "3";
		} else {
		$senclass = "0";
		}
		
		if ($item->district=='null'){
		$district = "0";
		} elseif ($item->district=="0"){
		$district = "1";
		} else {
		$district = $item->district;
		}		
					
        $member_data = array(
			'member_id'    		=> $person->id,
			'firstname' 		=> $person->firstname,
			'middlename' 		=> $person->middlename,
			'lastname' 		=> $person->lastname,
			'name'  		=> $person->name,
			'title' 		=> $item->title,
			'state' 		=> $item->state,
			'district' 		=> $district,
			'type'  		=> $type,
			'startdate' 		=> $item->startdate,
			'enddate' 		=> $item->enddate,
			'party' 		=> $item->party,
			'current' 		=> "1",
			'class' 		=> $senclass,
		);					
	    
	    $update = array();
	
	array_walk($member_data, 'array_sanitize');
 
	$fields = '`' . implode('`, `', array_keys($member_data)) . '`';
     $data = '\'' . implode('\', \'', $member_data) . '\'';
     foreach($member_data as $fields_update=>$data_update){
             $update[] = '`' . $fields_update . '` = \'' . $data_update. '\'';}
      mysql_query("INSERT INTO `members`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
          
}
}
member();

  function committee_update(){
        $committees_data= array(); 
	$xml = simplexml_load_file('https://www.govtrack.us/api/v2/committee?format=xml&obsolete=false&limit=600');
	foreach($xml->objects->item as $item) {
		
		$committee=$item->committee;
		
		$code  = $item->id;
                $name  = mysql_real_escape_string($item->name);
        	
        	if ($item->committee_type=="null"){
        	$type="subcommittee";
        	} else {
        	$typle=$item->committee_type;
        	}
        	
        	$committees_data= array(
			'code'    	=> $code,
			'type'    	=> $type,
			'name'    	=> $name,
			);

	
        $update = array();
	array_walk($committees_data, 'array_sanitize');
     $fields = '`' . implode('`, `', array_keys($committees_data)) . '`';
     $data = '\'' . implode('\', \'', $committees_data) . '\'';
	foreach($committees_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
      mysql_query("INSERT INTO `committees`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
}
} 

function committee_member_update(){
        $committees_members_data= array(); 
	$xml = simplexml_load_file('https://www.govtrack.us/api/v2/committee_member?format=xml&limit=4000');
	foreach($xml->objects->item as $item) {
        
		$code  = $item->committee->id;
                $member_id = $item->person->id;
                $role= $item->role_label;
                        
                $committees_members_data= array(
			'committee_id'  => $code,
			'member_id'    	=> $member_id,
			'role'    	=> $role,
			);

        $update = array();
	array_walk($committees_members_data, 'array_sanitize');
	 $fields = '`' . implode('`, `', array_keys($committees_members_data)) . '`';
     $data = '\'' . implode('\', \'', $committees_members_data) . '\'';
	foreach($committees_members_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
      mysql_query("INSERT INTO `committees_members`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
}
}

member_update();
committee_update();
committee_member_update();

?>