<?php
date_default_timezone_set('Asia/Seoul');
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "Core/xDiff.php";
require_once "Core/RObin.php";
require_once "Core/func.php";

$patches = array(); //                                       o
include_directory("Patches"); // load ALL THE PATCHES ,(o_O)/

echo"                                                         ______ \n";
echo"             _____  __   ___   ___ _______              |      |\n";
echo"            |     \|__|.'  _|.'  _|     __|.-----.-----.|__    |\n";
echo"            |  --  |  ||   _||   _|    |  ||  -__|     ||.   __|\n";
echo"            |_____/|__||__|  |__| |_______||_____|__|__||______|\n\n";

echo "##### Main Menu #####                            \n";
echo "0 => Local clients folder (can just press enter) \n";
echo "1 => Gravity kRO ftp (RagexeRE)                  \n";
echo "2 => Gravity kRO ftp (Ragexe)                    \n";
echo "m => Mass Test a Patch                           \n";
echo "f => Fetch all clients for Mass Test             \n";
echo "Which option shall be done: ";
$menu = trim(fgets(STDIN));

if($menu == "1"){
	$targets[] = GetFTP("RagexeRE.rgz", 50);
}elseif($menu == "2"){
	$targets[] = GetFTP("agexe.rgz", 50);
}elseif($menu == "f"){
	GetFTP("agexe", 150, true);
	die();
}elseif($menu == "m"){
	echo "\n";
	//print_r($patches);
	foreach($patches as $id => $patch){
		if($id < 10)
			echo "$id  => $patch\n";
		else
			echo "$id => $patch\n";
	}
	echo "Test which patch: ";
	$choice = trim(fgets(STDIN));
	if(!isset($patches[$choice]))
		die();
	$patch = $patches[$choice];
	echo "\n";
	$clients = glob("Clients/Pattern_Test_Clients/{*.exe,*.rgz}", GLOB_BRACE );
	if(sizeof($clients) == 0)
		die("Fetch some clients into the test folder first\n");
	
	foreach ($clients as $i => $client) {
		$filename = basename($client);
		$target = basename($client);
		//echo "$i => $filename\n";
		$src = new RObin();
		$src->load($client,false); // true = show client section/header information
		TestDiff($src, $patch, $filename);
	}
	die();
} else {
	echo "########################################\n\n";
	$clients = glob("Clients/{*.exe,*.rgz}", GLOB_BRACE );
	if(sizeof($clients) == 0)
		die("Place clients inside the Clients folder\n");
	
	foreach ($clients as $i => $client) {
		$filename = basename($client);
		echo "$i => $filename\n";
	}
	
	echo "all => All clients\n";
	
	echo "\nGenerate Diff for: ";
	$choice = trim(fgets(STDIN));
	if (!isset($clients[$choice]) && $choice != "all"){
		die("Bad Choice\n");
	}
	
	$targets = array();
	
	if($choice != "all")
		// Create only one entry.
		$targets[] = $clients[$choice];
	else
		// Create a reference and parse all entrys.
		$targets = &$clients;
}

foreach($targets as $target) {
	// Detect RGZ compressed client files and unpack
	if (stristr(basename($target),"rgz")){
		$target = unpack_rgz($target);
	}
	echo "########################################\n\n";

	// Fails will be saved in Fail folder
	$fail = "./Fails/" . basename($target, ".exe") . " Failed.txt";
	$failcount = 0;
	$passcount = 0;
	if(file_exists($fail))
		unlink($fail);

	// Diff will be saved to the Diffs folder with the same name, but with .diff extension
	$diffpath = "./Diffs/" . basename($target, ".exe") . ".xdiff";

	$src = new RObin();
	$src->load($target,true); // true = show client section/header information

	// Use PE timestamp now; previous method is fine for generating the title, but here it's just wrong
	//$clientdate2 = $src->clientdate();
	//$clientdate = substr($clientdate2,0,4) . "-" . substr($clientdate2,4,2) . "-" . substr($clientdate2,6,2);

	//$crc = "OCRC:" . substr(hexdec(sprintf("%x",crc32(file_get_contents($target )))), -8) . "\r\n";
	//$diff = "$crc" . "BLURB:[ $clientdate kRO  v1.0 - By Diff Team ]\r\n";
	echo "Generating Diff for: " . basename($target, ".exe") . "\r\n\r\n";
	$starttime = microtime(true);

	// Apply all patches :)
	foreach ($patches as $patch) {
		xDiff($src, $patch);
	}

	$src->writeDiffFile($diffpath);

	//file_put_contents($diffpath, $diff);
	$totaltime = microtime(true) - $starttime;
	echo "\npatches passed : $passcount\n";
	echo "patches failed : $failcount\n";
	echo "Diff saved to:  $diffpath (Process Time: " . round($totaltime, 3) . "s)\n";
}

?>
