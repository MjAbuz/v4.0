<?php

require_once('CRMDefaults.php');
require_once('LanguageHandler.php');
require_once('goCRMAPISettings.php');
//require_once('DbHandler.php');

$lh = \creamy\LanguageHandler::getInstance();

// check required fields
$validated = 1;
if (!isset($_POST["leadid"])) {
	$validated = 0;
}

if ($validated == 1) {
	$leadid = $_POST["leadid"];
	
    $url = gorul."/goGetLeads/goAPI.php"; #URL to GoAutoDial API. (required)
    $postfields["goUser"] = goUser; #Username goes here. (required)
    $postfields["goPass"] = goPass; #Password goes here. (required)
    $postfields["goAction"] = "goDeleteLead"; #action performed by the [[API:Functions]]. (required)
    $postfields["responsetype"] = responsetype; #json. (required)
    $postfields["lead_id"] = $leadid; #Desired User ID. (required)
    $postfields["hostname"] = $_SERVER['REMOTE_ADDR']; #Default value
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($data);
     
    if ($output->result=="success") {
    # Result was OK!
		ob_clean();
		print CRM_DEFAULT_SUCCESS_RESPONSE;
    } else {
		ob_clean(); 
		$lh->translateText("Unable to Delete Contact");
    }

} else {
	ob_clean(); $lh->translateText("some_fields_missing");
}
?>