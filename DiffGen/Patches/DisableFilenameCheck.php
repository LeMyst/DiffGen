<?php
    function DisableFilenameCheck($exe) {
    if ($exe === true) {
      return new xPatch(13, 'Disable RagexeRE Filename Check', 'Fix');
    }
    
    $codeA =     "\xE8\xAB\xAB\xAB\xFF";       // call    sub_707420
    $codeB =     "\x39\xAB\xAB\xAB\xAB\x00"    // cmp     Langtype, ebp
                ."\x75\xAB"                    // jnz     short loc_73FE94
                ."\xE8\xAB\xAB\xFF\xFF"        // call    sub_73DFB0
                ."\x84\xC0";                   // test    al, al
                
    $offset = $exe->match($codeA.$codeB, "\xAB");

    $jmpPos = 11;
    
    if ($offset === false) {
      // Try to search for register XORing
      $offset = $exe->code($codeA."\xAB\xAB".$codeB, "\xAB");
      if ($offset === false) {
        echo "Failed in part 1";
        return false;
      }
      $jmpPos += 2;
    }
    
    $exe->replace($offset, array($jmpPos => "\xEB"));
    return true;
  }
?>