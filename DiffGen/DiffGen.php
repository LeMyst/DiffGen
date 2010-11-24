<?php
date_default_timezone_set('Asia/Seoul');
error_reporting(E_ALL);
ini_set("display_errors", 1); 
require_once "Core/RObin.php";
require_once "Core/func.php";
include_directory("Patches");

echo"                                                         ______ \n";
echo"             _____  __   ___   ___ _______              |      |\n";
echo"            |     \|__|.'  _|.'  _|     __|.-----.-----.|__    |\n";
echo"            |  --  |  ||   _||   _|    |  ||  -__|     ||.   __|\n";
echo"            |_____/|__||__|  |__| |_______||_____|__|__||______|\n\n";



// lets add some ftp support [Yom]
echo "0  : Local clients folder (can just press enter)\n";
echo "1  : Gravity kRO ftp\n";
fwrite(STDOUT, "Where do we get clients: ");
$localftp = trim(fgets(STDIN));

if($localftp){
    $clients[0] = GetFTP();
    $choice = 0;
} else {
    echo "########################################\n\n";
    $clients = glob("Clients/{*.exe,*.rgz}", GLOB_BRACE );
    if(sizeof($clients) == 0) die("Place clients inside the Clients folder\n");
    echo "#  : All files in the folder\r\n";
    foreach ($clients as $i => $client) {
        list($ignore, $filename) = explode("/", $client);
        echo "$i  : $filename\n";
    }
    fwrite(STDOUT, "\nGenerate Diff for: ");
    $choice = trim(fgets(STDIN));
}

if (!isset($clients[$choice])){
    die("Bad Choice\n");
}

list($ignore, $filename) = explode("/", $clients[$choice]);
if (stristr(basename($clients[$choice]),"rgz")){
    echo basename($clients[$choice]) . " file is gzip compressed\n\n";
    // ungzip
    $gz = file_get_contents($clients[$choice]);
    $ungz = gzdecode($gz);
    // unrat                                                            // name                | size
    $type = substr($ungz,0,1);                                          // type                | 1 byte
    $fnlen = hexdec(bin2hex(substr($ungz,1,1)));                        // filenameLength      | 1 byte
    $fn = substr($ungz,2,$fnlen);                                       // filename            | filenameLength bytes
    $flen = unpack("L",substr($ungz,$fnlen+2,4)); $flen = $flen[1];     // length              | 4 bytes
    $unrat = substr($ungz,$fnlen+6,$flen);                              // data                | length bytes
    file_put_contents(trim($clients[$choice],"rgz")."exe",$unrat);
    unset($unrat);
    unlink($clients[$choice]);
    $clients[$choice] = trim($clients[$choice],"rgz")."exe";
}
echo "########################################\n\n";

$starttime = microtime(true);

// Target file name
$target = $clients[$choice];
// Fails will be saved in Fail folder
$fail = "./Fails/" . basename($target, ".exe") . " Failed.txt";
$failcount = 0;
$passcount = 0;
if(file_exists($fail)) unlink($fail);

// Diff will be saved to the Diffs folder with the same name, but with .diff extension
$diffpath = "./Diffs/" . basename($target, ".exe") . ".diff";

$src = new RObin();
$src->load($target);
$srcc = $src;

// Use PE timestamp now; previous method is fine for generating the title, but here it's just wrong
$clientdate2 = $src->clientdate();
$clientdate = substr($clientdate2,0,4) . "-" . substr($clientdate2,4,2) . "-" . substr($clientdate2,6,2);

$crc = "OCRC:" . substr(hexdec(sprintf("%x",crc32(file_get_contents($target )))), -8) . "\r\n";
$diff = "$crc" . "BLURB:[ $clientdate kRO  v1.0 - By Diff Team ]\r\n";
echo "\nGenerating diff for: " . basename($target, ".exe") . "\r\n\r\n";

include_once "Core/kRO.php";
    
file_put_contents($diffpath, $diff);
$totaltime = microtime(true) - $starttime;
echo "\npatches passed : $passcount\n";
echo "patches failed : $failcount\n";
echo "Diff saved to:  $diffpath (Process Time: " . round($totaltime, 3) . "s)\n";

?>
