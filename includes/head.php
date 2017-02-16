<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description=" content="Congress: The Game is a fantasy-sports style approach to politics. Players draft members of congress to their teams and earn points based on their legislators' actual performance in Washington.">
<meta name="google-site-verification" content="sxJwZXm1fsQQf8_g7-5jm1f-aMZNRXmi1GvPQI3w7QQ" />
<title><?php
$currentFile = $_SERVER["PHP_SELF"];

$parts = explode('/', $currentFile);
$parts = ucwords(substr($parts[count($parts) - 1],0,-4));
if($parts=='Drafthelp') { $parts = 'Draft Help';}
if ($parts=='Index'){
echo 'Congress: The Game - A fantasy sports style approach to politics';
} else {
echo $parts .' - Congress: The Game';
}
?>
</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

</head>