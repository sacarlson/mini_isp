<?php

 //$results = returnMacAddress("192.168.2.3");
 //echo "results = " . $results;
 //echo "\n<br> ";
 // should see: results = 00:1D:E0:A6:62:C7 

function returnMacAddress($remoteIp) {
 
 
exec("/usr/sbin/arp -n",$arpSplitted);
$ipFound = false;
// Cicle the array to find the match with the remote ip address

foreach ($arpSplitted as $value) {

// Split every arp line, this is done in case the format of the arp
// command output is a bit different than expected

$valueSplitted = split(" ",$value);

foreach ($valueSplitted as $spLine) {

if (preg_match("/$remoteIp/",$spLine)) {

$ipFound = true;

}
// The ip address has been found, now rescan all the string

// to get the mac address

if ($ipFound) {

// Rescan all the string, in case the mac address, in the string
// returned by arp, comes before the ip address

reset($valueSplitted);

foreach ($valueSplitted as $spLine) {

if (preg_match("/[0-9a-f][0-9a-f][:-]".

"[0-9a-f][0-9a-f][:-]".
"[0-9a-f][0-9a-f][:-]".
"[0-9a-f][0-9a-f][:-]".
"[0-9a-f][0-9a-f][:-]".
"[0-9a-f][0-9a-f]/i",$spLine)) {

return $spLine;

}
}
}
$ipFound = false;
}
}
return false;
}

?>
