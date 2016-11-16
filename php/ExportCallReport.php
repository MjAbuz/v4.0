<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    
    for($i=0; $i < count($campaigns);$i++){
        echo $campaigns[$i];echo "<br>";
    } 
    for($i=0; $i < count($inbounds);$i++){
        echo $inbounds[$i];echo "<br>";
    }
    for($i=0; $i < count($lists);$i++){
        echo $lists[$i];echo "<br>";
    }
    for($i=0; $i < count($statuses);$i++){
        echo $statuses[$i];echo "<br>";
    }*/
    
    require_once('./goCRMAPISettings.php');
    
    if($_POST['campaigns'] != NULL)
    $campaigns = $_POST['campaigns'];
    
    if($_POST['inbounds'] != NULL)
    $inbounds = $_POST['inbounds'];
    
    if($_POST['lists'] != NULL)
    $lists = $_POST['lists'];
    
    if($_POST['statuses'] != NULL)
    $statuses = $_POST['statuses'];
    
    $custom_fields = $_POST['custom_fields']; 
    $per_call_notes = $_POST['per_call_notes'];
    $toDate = date('Y-m-d H:i:s', strtotime($_POST['toDate']));
    $fromDate = date('Y-m-d H:i:s', strtotime($_POST['fromDate']));
    
    $campaigns = implode(" ", $campaigns);
    $inbounds = implode(" ", $inbounds);
    $lists = implode(" ", $lists);
    $statuses = implode(" ", $statuses);
    
    $url = gourl."/goReports/goAPI.php"; #URL to GoAutoDial API. (required)
    $postfields["goUser"] = goUser; #Username goes here. (required)
    $postfields["goPass"] = goPass; #Password goes here. (required)
    $postfields["goAction"] = "goGetReports"; #action performed by the [[API:Functions]]. (required)
    $postfields["responsetype"] = responsetype; #json. (required)
    $postfields["pageTitle"] = "call_export_report";
    
    if($campaigns != NULL)
    $postfields["campaigns"] = $campaigns;
    
    if($inbounds != NULL)
    $postfields["inbounds"] = $inbounds;
    
    if($lists != NULL)
    $postfields["lists"] = $lists;
    
    if($statuses != NULL)
    $postfields["statuses"] = $statuses;
    
    $postfields["custom_fields"] = $custom_fields;
    $postfields["per_call_notes"] = $per_call_notes;
    
    if($toDate != NULL)
    $postfields["toDate"] = $toDate;
    
    if($fromDate != NULL)
    $postfields["fromDate"] = $fromDate;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($data);
    
   // echo $lists;
    //var_dump($output);
    
    if($output->result == "success"){
        //$filename = $output->getReports->filename;
        
        $header = implode(",",$output->getReports->header);
        //$header = $output->getReports->header;
        //$row = implode(",",$output->getReports->rows);
        
        //$row = implode(",",$output->getReports->rows);
       // $rows = $output->getReports->rows;
        
        //var_dump($output->getReports->query);
        
        $filename = date()."callreports.csv";
        $fp = fopen($filename, 'w');
        
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        
        echo $header."\n";
        
        $count = 0;
        for($i=0; $i <= count($output->getReports->header); $i++){
            $count_row = $output->getReports->rows[$i];
            for($x=0; $x <= count($count_row); $x++){
                if($x == count($count_row)){
                    echo $count_row[$x]."\n";
                }else{
                    echo $count_row[$x].",";
                }
            }
        }
        //echo $row;
        
        //fputcsv($fp, implode(",",$header));
        
        //for($i=0; $i < count($output->getReports->header); $i++){
        //    echo $output->getReports->header[$i];
        //}
        //
        //echo "\n";
        
        //$data = $output->getReports->rows;
        
        //foreach ($data as $row)
        //{
        //    fputcsv($fp, $row); 
        //}
        
        //for($i=0; $i < count($output->getReports->header); $i++){
        //    fputcsv($fp, $output->getReports->rows[$i]);
        //    echo $output->getReports->rows[$i];
        //    echo "\n";
        //}
        
        fclose($fp);
        exit;
        
        
    }
    
   
?>