<?php

echo PHP_EOL."Cleaning up remaining files...";
@unlink("./temp/geoip.csv.zip");
@unlink("./temp/GeoIPCountryWhois.csv");

echo PHP_EOL."Downloading [GeoIP-Country Free] from maxmind.com ...";
shell_exec("wget -O ./temp/geoip.csv.zip http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip");

echo PHP_EOL."Extracting GeoIP...";
shell_exec("cd ./temp/; unzip geoip.csv.zip");

echo PHP_EOL."Parsing...";
$final = array();
$handle = fopen("./temp/GeoIPCountryWhois.csv",'r');
while( ($data = fgetcsv($handle,0,",")) !== false) {
  $final[] = array('cc'=>$data[4],'start'=>$data[2],'end'=>$data[3]);
}

echo PHP_EOL."Building SQL...";
$count = 25;
$sql = '';
$temp = '';
foreach($final as $part) {
  $count++;
  if($count >= 25) {
    $count = 0;
    if(!empty($temp)) $sql .= PHP_EOL.substr($temp,0,-1).';';
    $newtemp = true;
    $temp = 'INSERT INTO `ps_geoip_ip` (`cc`, `start`, `end`) VALUES';
  }
  else $newtemp = false;
  $temp .= " ('".$part['cc']."','".$part['start']."','".$part['end']."'),";
}
if($newtemp) $sql .= PHP_EOL.$temp.';';
file_put_contents('../upload/install/mysql/maxmind.sql',$sql);

echo PHP_EOL."Done!!!".PHP_EOL;
?>