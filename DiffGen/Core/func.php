<?php
function Diff(&$exe, $patch) {
    $tick = microtime(true);
    if(function_exists($patch)) {
        global $diff, $fail, $failcount, $passcount, $patterndebug;
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

function unpack_rgz($rgz){
    $exe = trim($rgz,"rgz") . "exe";
    echo "unpacking " . basename($rgz) . "\n\n";
    // ungzip
    $gz = file_get_contents($rgz);
    $ungz = gzdecode($gz);
    // unrat                                                            // name                | size
    $type = substr($ungz,0,1);                                          // type                | 1 byte
    $fnlen = hexdec(bin2hex(substr($ungz,1,1)));                        // filenameLength      | 1 byte
    $fn = substr($ungz,2,$fnlen);                                       // filename            | filenameLength bytes
    $flen = unpack("L",substr($ungz,$fnlen+2,4)); $flen = $flen[1];     // length              | 4 bytes
    $unrat = substr($ungz,$fnlen+6,$flen);                              // data                | length bytes
    file_put_contents($exe,$unrat);
    unset($unrat);
    unlink($rgz);
    return $exe;
}

function gzdecode($data) {
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
                echo "Loaded $filename                     \r";
                $patches[] = $filename;
            }
            usleep(5000);   // visual effect
        }
    }
    echo "Loaded $count Patches                          \n\n";
}

function GetFTP($client) {
    echo "############# CONNECTING ###############\n\n";
    $ftp_server = "125.141.215.106";
    $ftp_user = "ragadmin";
    $ftp_pass = "icsragadmin!@";
    // set up a connection or die
    $conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 
    // try to login
    if (!@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
        die("\nCouldn't login as $ftp_user\n");
    }
    ftp_pasv($conn_id, true);
    ftp_chdir($conn_id, "Patch");
    $buff = ftp_nlist($conn_id, '');
    foreach ($buff as $ftpfile) {
        if(strpos($ftpfile, $client)){
            $filelist[] = $ftpfile;
        }
    }
    for($i=sizeof($filelist)-20; $i<sizeof($filelist); $i++){
        $locfile = "Clients/" . substr($filelist[$i], 0, -4) . ".exe";
        if (file_exists($locfile)) {
            echo "$i #: $filelist[$i]\n";
        } else {
            echo "$i  : $filelist[$i]\n";
        }
    }
    fwrite(STDOUT, "\nGenerate Diff for: ");
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