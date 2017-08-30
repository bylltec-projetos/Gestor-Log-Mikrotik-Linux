<?php
echo "OIDs <pre>";
system("touch /var/www/teste2.php ", $data);
//print_r($result);
echo "</pre>";
echo "Nome: <pre>";
 system("snmpwalk -Os -c public -v 1 177.203.45.16 iso.3.6.1.2.1.1.5.0", $data);
//print_r($result);
echo "</pre>";

echo "OIDs <pre>";
system("snmpwalk -Os -c public -v 1 177.203.45.16 ", $data);
//print_r($result);
echo "</pre>";


//snmpget(177.203.45.16, public);
$syscontact = snmpget("177.203.45.16", "public", "iso.3.6.1.2.1.1.5.0");
echo $syscontact;


?>


<?php
// author: dstjohn at mediacast1.com
// updated: 09-11-2005
// set some vars
$snmpcommunity = 'public'; //snmp community name
$ips = 'bylltec.com.br,177.203.45.16'; //ips or dns to get snmp data from
$system_number = '1';
//end da vars

//start da loop d loop
for ($i = 0; $i <= $system_number; $i++) {
$sysip = explode(",",$ips);

//get system name
$sysname[0] = snmpget($sysip[$i], $snmpcommunity, "sysName.0");
$sysname[1] = eregi_replace("STRING:","",$sysname[0]);
echo 'System Name: '.$sysname[1].'<br>';

//system description
$sysdesc[0] = snmpget($sysip[$i], $snmpcommunity, "sysDescr.0");
$sysdesc[1] = eregi_replace("STRING:","",$sysdesc[0]);
echo 'System Description: '.$sysdesc[1].'<br>';

//system location
$sysloc[0] = snmpget($sysip[$i], $snmpcommunity, "sysLocation.0");
$sysloc[1] = eregi_replace("STRING:","",$sysloc[0]);
echo 'System Location: '.$sysloc[1].'<br>';

//current tcp connections
$tcpcons[0] = snmpget($sysip[$i], $snmpcommunity, "tcpCurrEstab.0");
$tcpcons[1] = eregi_replace("Gauge32:","",$tcpcons[0]);
echo 'Open TCP/IP Connections: '.$tcpcons[1].'<br>';

//get system uptime
$sysuptime[0] = snmpget($sysip[$i], $snmpcommunity, "system.sysUpTime.0");
$sysuptime[1] = eregi_replace("Timeticks:","",$sysuptime[0]);
echo 'System Uptime: Timeticks -'.$sysuptime[1].'<br>';

//windows only
//installed memory
if(eregi('Windows',$sysdesc[1])){
$mem[0] = snmpget($sysip[$i], $snmpcommunity, "HOST-RESOURCES-MIB::hrMemorySize.0");
$mem[1] = eregi_replace("INTEGER:","",$mem[0]);
$mem[2] = eregi_replace("KBytes","",$mem[1]);
echo 'Insalled Memory: '.$mem[2].' KiloBytes<br>';
}

echo '<br><br>';
}//end loop

?>
