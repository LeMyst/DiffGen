<?php
function Diff(&$exe, $patch) {
	$tick = microtime(true);
	if(function_exists($patch)) {
		global $diff, $fail, $failcount, $passcount;
		echo str_pad($patch, 40, " ") . ": ";
		
		$diffMap = call_user_func($patch, true);
		
		if(!is_array($diffMap)) {	   
		  // Have at least one entry, even if not an array passed, lol..
		  $diffMap = array($diffMap);
		}
		
		// Fail.
		if(count($diffMap) < 1)
		  return;
		
		$newDiff = "";
		foreach($diffMap as $key => $diffName) {
		  // The called function is responsible to return success or failure
		  // and is also responsible for !!detecting replaced byte codes!!
		  if (call_user_func($patch, $exe, $key) === false) {
			$failcount++;
			echo " ##\r\n";
			
			// Place the last returned name (in case of multiple diff)
			// so that it is easier to trace back which diff exactly failed.
			file_put_contents($fail, $diffName . "\r\n", FILE_APPEND);
			$exe->diff();
			return;
		  }
		  
		  $prefix = "byte_".$diffName;
		  $diffs = $exe->diff();
		  
		  foreach ($diffs as $dif) {
			$newDiff .= $prefix . ":" . $dif . "\r\n";
		  }
			
		}
		
		$newDiff .= "\r\n";
		
		// Add all found diffs in the function into the "global diff universe" :)
		$diff .= $newDiff;
		
		$passcount++;
		echo "Done in " . round(microtime(true) - $tick, 5) . "s\n";
	} else {
		die("\nError Missing Function " . $patch . "\n\n\n");
	}
}

function xDiff(&$exe, $patch) {
	$tick = microtime(true);
	if(function_exists($patch)) {
		global $fail, $failcount, $passcount;
		
		
		$diffMap = call_user_func($patch, true);
		
		if (!is_a($diffMap, 'xPatchBase'))
			die("\nPatch '$patch' returned an invalid value!\n\n\n");
		
		$diffName = $diffMap->getName();
		$id = $diffMap->getID();
	  	if (array_key_exists($id, $exe->xDiff)) {
	  		echo "\nID $id is already in use for '" . $exe->xDiff[$id]->getName() . "'\n\n\n";
			die();
		}
	  		
	  	$pad = 40;
	  	if (is_a($diffMap, 'xPatch') && $diffMap->getGroup() > 0)
	  		$pad -= 2;	
	  	echo str_pad($patch, $pad, " ") . ": ";
		
		if (is_a($diffMap, 'xPatchGroup')) { // patch group!
			$exe->xDiff[$id] = $diffMap; //add group to diff list
			echo "\n";
			foreach ($diffMap->getPatchNames() as $d) {
				echo "  ";
				xDiff($exe, $d);
			}
			return;
		}		
		
		$exe->xPatch = $diffMap;
		
		// The called function is responsible to return success or failure
		// and is also responsible for !!detecting replaced byte codes!!
		if (call_user_func($patch, $exe) === false) {
		  $failcount++;
		  echo " ##\r\n";
		  
		  file_put_contents($fail, $diffName . "\r\n", FILE_APPEND);
		  return;
		}
		
		$group = $diffMap->getGroup();
		if ($group) {
			if (!array_key_exists($group, $exe->xDiff))
				die("\nCannot find group for Patch $patch!\n\n\n");
			else
				$exe->xDiff[$group]->addPatch($exe->xPatch);
		} else {
			$exe->xDiff[$id] = $exe->xPatch;
		}

		unset($exe->xPatch);
		
		$passcount++;
		echo "Done in " . round(microtime(true) - $tick, 5) . "s\n";
	} else {
		die("\nError Missing Function " . $patch . "\n\n\n");
	}
}

function TestDiff(&$exe, $patch, $filename) {
	$tick = microtime(true);
	if(function_exists($patch)) {
		$diffMap = call_user_func($patch, true);
		
		if (!is_a($diffMap, 'xPatchBase'))
			die("\nPatch '$patch' returned an invalid value!\n\n\n");
		
		$diffName = $diffMap->getName();
		$id = $diffMap->getID();
	  	if (array_key_exists($id, $exe->xDiff))
	  		die("\nID $id is already in use!\n\n\n");		
	  		
	  	$pad = 30;
	  	if (is_a($diffMap, 'xPatch') && $diffMap->getGroup() > 0)
	  		$pad -= 2;	
	  	echo str_pad($filename, $pad, " ") . ": ";
		
		if (is_a($diffMap, 'xPatchGroup')) { // patch group!
			$exe->xDiff[$id] = $diffMap; //add group to diff list
			echo "\n";
			foreach ($diffMap->getPatchNames() as $d) {
				echo "  ";
				xDiff($exe, $d);
			}
			return;
		}		
		
		$exe->xPatch = $diffMap;
		
		// The called function is responsible to return success or failure
		// and is also responsible for !!detecting replaced byte codes!!
		if(call_user_func($patch, $exe) !== false){
			echo "Done in " . round(microtime(true) - $tick, 5) . "s";
		} else {
			echo "\n";
		}
	} else {
		die("\nError Missing Function " . $patch . "\n\n\n");
	}
}

function unpack_rgz($rgz){
	$exe = trim($rgz,"rgz") . "exe";
	echo "unpacking " . basename($rgz) . " => ".basename($exe)."\r\n";
	// ungzip
	$gz = file_get_contents($rgz);
	$ungz = gzdecode2($gz);
	// unrat															// name				| size
	$type = substr($ungz,0,1);										  // type				| 1 byte
	$fnlen = hexdec(bin2hex(substr($ungz,1,1)));						// filenameLength	  | 1 byte
	$fn = substr($ungz,2,$fnlen);									   // filename			| filenameLength bytes
	$flen = unpack("L",substr($ungz,$fnlen+2,4)); $flen = $flen[1];	 // length			  | 4 bytes
	$unrat = substr($ungz,$fnlen+6,$flen);							  // data				| length bytes
	file_put_contents($exe,$unrat);
	unset($unrat);
	unlink($rgz);
	return $exe;
}

function gzdecode2($data) {
  $g=tempnam('/tmp','ff');
  @file_put_contents($g,$data);
  ob_start();
  readgzfile($g);
  $d=ob_get_clean();
  return $d;
}

function include_directory($dir) {
	global $patches;
	$it = new RecursiveDirectoryIterator($dir);
	$count = 0;
	foreach(new RecursiveIteratorIterator($it) as $filename => $cur) {
		if( preg_match('/#/', $filename) ) {
			continue;   // skip files begining with #
		}
		if( preg_match('/.php$/', $filename) ) {
			require_once($filename);
			$filename = basename($filename,".php");
			if(!function_exists($filename)) {
				echo "### File Without Function - $filename\n";
			} else {
				$count++;
				echo "Loaded $filename					 \r";
				$patches[] = $filename;
			}
			usleep(5000);   // visual effect
		}
	}
	echo "Loaded $count Patches						  \n\n";
}

function GetFTP($client, $limit, $all=false) {
	echo "############# CONNECTING ###############\n\n";
	$ftp_server = "ragnarok.nowcdn.co.kr";
	$ftp_port = 20021;
	$ftp_user = "anonymous";
	$ftp_pass = "";
	// set up a connection or die
	$conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server"); 
	// try to login
	if (!@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
		die("\nCouldn't login as $ftp_user\n");
	}
	echo "############# CONNECTED  ###############\n\n";
	ftp_pasv($conn_id, true);
	ftp_chdir($conn_id, "Patch");
	$buff = ftp_nlist($conn_id, '');
	$filelist = array();
	foreach ($buff as $ftpfile) {
		if(strpos($ftpfile, $client)){
			$filelist[] = $ftpfile;
		}
	}
	if($all == true) {
		echo "############ DOWNLOADING.. #############\n";
		$client_count = sizeof($filelist)-1;
		for($j=$client_count; $j>=0; $j--) {
			$locfile = "Clients/Pattern_Test_Clients/$filelist[$j]";
			$exe = substr($locfile, 0, -3) . "exe";
			if(is_file($exe)){
				//if(filesize($exe) > 4000000){ // catch 
					continue;
				//}
			}
			//echo $locfile. "\n";
			$fs = ftp_size($conn_id, $filelist[$j]); 
			$file = ftp_nb_get($conn_id, $locfile, $filelist[$j], FTP_BINARY);
			while($file == FTP_MOREDATA) {
				clearstatcache();
				$downloaded = filesize($locfile);
				if ( $downloaded> 0 ){
					$i = round(($downloaded/$fs)*100, 0);
					echo "\r\t$i% " . basename($locfile);
				}
				$file = ftp_nb_continue($conn_id); 
			}
			if ($file != FTP_FINISHED) {
				echo "\nThere was an error downloading the file...\n";
			} else {
				echo "\rSuccessfully downloaded ".basename($locfile)."\n";
			}
			$locfile = unpack_rgz($locfile);
		}
		return;
	}
	for($i=sizeof($filelist)-$limit; $i<sizeof($filelist); $i++) {
		$locfile = "Clients/" . substr($filelist[$i], 0, -4) . ".exe";
		$size = ftp_size($conn_id, $filelist[$i]);
		if (file_exists($locfile)) {
			echo "$i #: $filelist[$i] - $size\n";
		} else {
			echo "$i  : $filelist[$i] - $size\n";
		}
	}
	echo "\nGenerate Diff for: ";
	$choice = trim(fgets(STDIN));
	if(!isset($filelist[$choice])) {
		die("\nfailure with client choice\n");
	}
	$locfile = "Clients/$filelist[$choice]";
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
		echo "\nThere was an error downloading the file...\n";
	} else {
		echo "\nSuccessfully downloaded to $locfile\n";
	}
	return $locfile;
}
?>