<?php
function Diff($src, $exe, $patch, $extra = false) {
	$tick = microtime(true);
	if(function_exists($patch)) {
		global $diff, $fail, $failcount, $patterndebug;
		if(!$patterndebug) {
			echo str_pad($patch, 50, " ") . ": ";
		}
		if (call_user_func($patch, $exe, $extra) === false) {
			$failcount++;
			echo " ##\r\n";
			file_put_contents($fail, call_user_func($patch, true, $extra) . "\r\n", FILE_APPEND);
			$exe->diff();
			return;
		}
		$diff .= "\r\n";
		$prefix = "byte_" . call_user_func($patch, true, $extra);
		$diffs = $exe->diff();
		foreach ($diffs as $dif) {
			$diff .= $prefix . ":" . $dif . "\r\n";
		}
		echo "Done in " . round(microtime(true) - $tick, 5) . "s\r\n";
	} else {
		die("\nError Missing Function " . $patch . "\r\n\n\n");
	}
}

function DiffColor($src, $exe, $patch) {
	$tick = microtime(true);
	global $diff, $colors_name, $colors_numbers, $fail, $failcount;
	echo str_pad($patch, 50, " ") . ": ";
	if (call_user_func(array("Patches", $patch), $exe) === false){
		$failcount++;
		echo " ##\r\n";
		file_put_contents($fail, call_user_func(array("Patches", $patch), true) . "\r\n", FILE_APPEND);
		return;
	}
	$diff .= "\r\n";
	$prefix = "byte_" . call_user_func(array("Patches", $patch), true);
	$diffs = $exe->diff();
	$diffs = explode(":", $diffs[0]);
	$pos = hexdec($diffs[0]);
	for($j = 0; $j < count($colors_name); $j++){
		$splitcolor = str_split($colors_numbers[$j], 2);
		for($k = 0; $k < 3; $k++){
			if (ord($src->exe[$pos+$k]) != hexdec($splitcolor[$k]))
				$diff .= $prefix . "_(" . $colors_name[$j] . "):" . strtoupper(dechex($pos+$k)) . ":" . ord($src->exe[$pos+$k]) . ":" . hexdec($splitcolor[$k]) . "\r\n";
		}
	}
	echo "Done in " . round(microtime(true) - $tick, 3) . "s\r\n";
}

function gzdecode($data) {
  $g=tempnam('/tmp','ff');
  @file_put_contents($g,$data);
  ob_start();
  readgzfile($g);
  $d=ob_get_clean();
  return $d;
}

function DiffAutos($src, $exe, $patch) {
	$tick = microtime(true);
	global $diff, $autos_name, $fail, $failcount;
	echo str_pad($patch, 50, " ") . ": ";
	if (call_user_func(array("Patches", $patch), $exe) === false) {
		$failcount++;
		echo "Failed\r\n\n";
		file_put_contents($fail, call_user_func(array("Patches", $patch), true) . "\r\n", FILE_APPEND);
		return;
	}
	$diff .= "\r\n";
	$prefix = "byte_" . call_user_func(array("Patches", $patch), true);
	$diffs = $exe->diff();
	$i = 0;
	foreach ($diffs as $dif) {
		$diff .= $prefix . $autos_name[$i] . ":" . $dif . "\r\n";
		$i++;
	}
	echo "Done in " . round(microtime(true) - $tick, 3) . "s\r\n";
}

function include_directory($dir) {
	$it = new RecursiveDirectoryIterator($dir);
	foreach(new RecursiveIteratorIterator($it) as $filename => $cur) {
		if( preg_match('/.php$/', $filename) ) {
			echo "Loaded $filename\n";
			require_once($filename);
		}
	}
}

function GetFTP() {
	echo "############# CONNECTING ###############\n\n";
	$ftp_server = "125.141.215.106";
	$ftp_user = "ragadmin";
	$ftp_pass = "icsragadmin!@";
	// set up a connection or die
	$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 
	// try to login
	if (!@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
		echo "Couldn't login as $ftp_user\n";
	}
	ftp_pasv($conn_id, true);
	ftp_chdir($conn_id, "Patch");
	$buff = ftp_nlist($conn_id, '');
	foreach ($buff as $ftpfile) {
		if(strpos($ftpfile , "RagexeRE.rgz") || strpos($ftpfile , "Ragexe.rgz")){
			$filelist[] = $ftpfile;
		}
	}
	for($i=sizeof($filelist)-40; $i<sizeof($filelist); $i++){
		if(strpos($filelist[$i] , "RagexeRE.rgz")){
			$locfile = "Clients/kRO/RagexeRE/" . substr($filelist[$i], 0, -4) . ".exe";
		} elseif(strpos($filelist[$i] , "Ragexe.rgz")){
			$locfile = "Clients/kRO/Ragexe/" . substr($filelist[$i], 0, -4) . ".exe";
		}
		if (file_exists($locfile)) {
			echo "$i #: $filelist[$i]\n";
		} else {
			echo "$i  : $filelist[$i]\n";
		}
	}
	fwrite(STDOUT, "\nGenerate Diff for: ");
	$choice = trim(fgets(STDIN));
	if(strpos($filelist[$choice] , "RagexeRE.rgz")){
		$locfile = "Clients/kRO/RagexeRE/$filelist[$choice]";
	} elseif(strpos($filelist[$choice] , "Ragexe.rgz")){
		$locfile = "Clients/kRO/Ragexe/$filelist[$choice]";
	}
	echo "############ DOWNLOADING.. #############\n";
	$fs = ftp_size($conn_id, $filelist[$choice]); 
	$file = ftp_nb_get($conn_id, $locfile, $filelist[$choice], FTP_BINARY);
	while ($file == FTP_MOREDATA) { 
		clearstatcache();
		$downloaded = filesize($locfile);
		if ( $downloaded> 0 ){
			$i = round(($downloaded/$fs)*100, 0);
			echo "\r\t $i% Downloaded";
		}
		$file = ftp_nb_continue($conn_id); 
	}
	if ($file != FTP_FINISHED) {
		echo "\nThere was an error downloading the file...";
	} else {
		echo "\nSuccessfully downloaded to $locfile\n";
	}
	return $locfile;
}
?>