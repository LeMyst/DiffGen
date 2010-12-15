<?php
// 08.12.2010 - Started to rework some translations (this will be a hell of work) [Shinryo]
// 10.12.2010 - Okay, won't be so much work as I thought.. All of the translations from the
//              big array in DiffGen1 is already set in msgstringtable.txt and don't have to be
//              translated in client again. [Shinryo]

function TranslateClientInEnglish($exe){
  if ($exe === true) {
    return "[UI]_Translate_Client_In_English_(Recommended)";
  }
  
  // I would like to to use a huge array list with codes and replacements, but it isn't really
  // clean to see what is where and why. So just split them in parts.
  
  //**********************************
  $trans = "Translate Delete Time";
  //**********************************
  $codes = array( 
                  // %d�� %d�� %d�� %d�� %d�� --> Month/Day Hour:Minutes:Seconds
                  "\x25\x64\xBF\xF9\x20\x25\x64\xC0\xCF\x20\x25\x64\xBD\xC3\x20\x25\x64\xBA\xD0\x20\x25\x64\xC3\xCA\x00",
                  // %d�� %d�� %d�� %d�� %d�� %d�� --> Year/Month/Day Hour:Minutes:Seconds
                  "\x25\x64\xB3\xE2\x20\x25\x64\xBF\xF9\x20\x25\x64\xC0\xCF\x20\x25\x64\xBD\xC3\x20\x25\x64\xBA\xD0\x20\x25\x64\xC3\xCA\x20");
                  
  $changes = array(
                  // Delete: %d/%d - %d:%d:%d
                  "\x44\x65\x6C\x65\x74\x65\x3A\x20\x25\x64\x2F\x25\x64\x20\x2D\x20\x25\x64\x3A\x25\x64\x3A\x25\x64",
                  // %d/%d/%d - %d:%d:%d
                  "\x25\x64\x2F\x25\x64\x2F\x25\x64\x20\x2D\x20\x25\x64\x3A\x25\x64\x3A\x25\x64");
  
  foreach($codes as $index => $code) {
    if(strlen($changes[$index])+1 > strlen($code)){
      // Don't die, just report..
      echo "\n\nTranslateClientInEnglish > {$trans}: String length error at index $index\n\n";
      return false;
    }
    
    $offset = $exe->match($codes[$index], "\xAB", 0);
    if($offset === false) {
      echo "\n{$trans}: Failed at index {$index}\n";
      return false;
    }
    $exe->replace($offset, array(0 => $changes[$index]."\x00"));
  }
  
  //**********************************
  $trans = "Translate Message Box";
  //**********************************
  $code = "\xB8\xDE\xBD\xC3\xC1\xF6";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "Message\x00"));
  
  //**********************************
  $trans = "Translate Character Slot Usage";
  //**********************************
  $code = "\x00\x28\xC4\xB3\xB8\xAF\xC5\xCD\x2F\xC3\xD1\x20\xBD\xBD\xB7\xD4\x29\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(1 => "(Used / Total)\x00"));

  // Next entry..
  
  return true;
}
?>