<?php
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	

if(!user_session_validate())
	sendHeader("403", null, null, 1);
	
$w=$_REQUEST['workspace'];
if (!user_isAdmin(user_getUsername(), $w)){
	sendHeader("401", null, null, 1);
}

// returns an array of all the timestamps between the start timestamp and current date. 
function gaps($start, $interval){
	$gaps[] = $start;
	$temp=$start;
	while($temp < mktime()){
		$temp = $temp + $interval;
		$gaps[] = $temp;
  	}
	return $gaps;
}

function handleSQL($SQL, $format, $goBack, $interval){
	$results = mysql_query($SQL);
	$count = 0;
	while($result=mysql_fetch_assoc($results)){
			$dates[] .= $result['Date'];
			$hits[$result['Date']] = $result['numRows'];
	}
	$a = gaps(mktime()-$goBack, $interval);
	foreach ($a as $time){
		if(!@in_array(date($format, $time), $dates)){
			$hits[date($format, $time)] = 0;
 			$dates[] = date($format, $time);
		}
	}
	sort($dates);
	foreach($dates as $date){
		if($date!="")
			$str .= "{ date:'".$date."', hits:".$hits[$date]." },";	
	}
	return substr($str,0,strlen($str)-1);	
}

if ($_REQUEST['graph']=="hour"){
	// last 24 hours
	$SQL = "SELECT DATE_FORMAT(time, '%d-%k') AS Date, COUNT(*) AS numRows FROM workspace_view  where time >SUBDATE(now() , INTERVAL 10 HOUR) AND workspace='".$w."' and username!='".$user['username']."' GROUP BY Date order by time limit 10";
	echo handleSQL($SQL, "d-H", 86400, 3600);
	// 3600 second in an hour.
	// 86400 second in a day.
}
if ($_REQUEST['graph']=="minute"){
	// last 20 min
 	$SQL = "SELECT DATE_FORMAT(time, '%k:%i') AS Date,  COUNT(*) AS numRows FROM workspace_view  where time >SUBDATE(now() , INTERVAL 20 minute) AND workspace='".$w."'  and username!='".$user['username']."' GROUP BY Date order by time asc limit 20";
	echo handleSQL($SQL, "H:i", 1200, 60);
	// 3600 second in an hour.
	// 86400 second in a day.
}
if ($_REQUEST['graph']=="day"){
	// last 7 days
	$SQL = "SELECT DATE_FORMAT(time, '%Y-%m-%d') AS Date,  COUNT(*) AS numRows FROM workspace_view  where time >CURRENT_DATE() - INTERVAL 7 DAY AND workspace='".$w."' and username!='".$user['username']."'  GROUP BY Date order by time limit 15";
	echo handleSQL($SQL, "Y-m-d", 604800, 3600);
}
if ($_REQUEST['graph']=="month"){
	// last 5 months
	$SQL = "SELECT DATE_FORMAT(time, '%m/%Y') AS Date,  COUNT(*) AS numRows FROM workspace_view  where time >CURRENT_DATE() - INTERVAL 12 MONTH AND workspace='".$w."'  and username!='".$user['username']."' GROUP BY Date order by time limit 200";
	echo handleSQL($SQL, "m/Y", 9592000, 3600);
}

?>