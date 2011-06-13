<?php
echo '
<body bgcolor="white"><meta http-equiv="refresh" content="20"><font size="0.5em" face=arial>';
echo '<form action="">
<input type="submit" value="Update Log" />
</form>';

$file = file_get_contents('C:\xampp\apache\logs\error.log');
$lines = explode("\n", $file);
$lines = array_reverse($lines);
$count = 0;
foreach($lines as $line)
{
	$count++;
	if ($count > 100)
		exit;
    $parts = explode("] ", $line);
	$count2 = 0;
	foreach($parts as $part)
	{ 
		if($count2 == '0' || $count2 =='1')
			echo "<span style='color:grey'>".substr($part, 1)." </span> :: ";
		elseif ( $count2 =='3')
		{
				echo "<span style='color:red'>".str_replace(stristr($part, 'referer'), "", $part)."</span> ";
		}
		$count2++;
	}	
	echo "<br />";
}
exit;
?>