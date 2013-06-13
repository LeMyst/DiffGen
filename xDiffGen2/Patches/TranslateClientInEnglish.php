<?php
// 08.12.2010 - Started to rework some translations (this will be a hell of work) [Shinryo]
// 10.12.2010 - Okay, won't be so much work as I thought.. All of the translations from the
//              big array in DiffGen1 is already set in msgstringtable.txt and don't have to be
//              translated in client again. [Shinryo]

function TranslateClientInEnglish($exe){
  if ($exe === true) {
    return new xPatch(44, 'Translate Client In English', 'UI', 0, 'This will translate some of the hardcoded Korean phrases to English');
  }
  
  // I would like to to use a huge array list with codes and replacements, but it isn't really
  // clean to see what is where and why. So just split them in parts.
  
  //**********************************
  $trans = "Translate Delete Time";
  //**********************************
  $codes = array( 
                  // %d월 %d일 %d시 %d분 %d초 --> Month/Day Hour:Minutes:Seconds
                  "\x25\x64\xBF\xF9\x20\x25\x64\xC0\xCF\x20\x25\x64\xBD\xC3\x20\x25\x64\xBA\xD0\x20\x25\x64\xC3\xCA\x00",
                  // %d년 %d월 %d일 %d시 %d분 %d초 --> Year/Month/Day Hour:Minutes:Seconds
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
  
  //**********************************
  $trans = "Translate Cash Shop Points";
  //**********************************
  $code = "\x00\x54\x6F\x74\x61\x6C\x20\x3A\x20\x25\x64\x20\xC4\xB3\xBD\xC3\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(1 => "Total : %d Points\x00"));
  
  //**********************************
  $trans = "Translate Taekwon Job";
  //**********************************

  if ($exe->clientdate() <= 20130605)
	$code = "\xBA\x00\x00\xB9\xAB\xAB\xC0\x00\x75\x59";
  else
    $code = "\xC3\x00\x00\xB9\xAB\xAB\xC9\x00\x75\x59";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(8 => "\xEB"));
  
  //**********************************
  $trans = "Item Inventory";
  //**********************************

  $code = "\xBE\xC6\xC0\xCC\xC5\xDB\x20\xBA\xF1\xB1\xB3";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x49\x74\x65\x6d\x20\x43\x6d\x70\x61\x72\x65"));
    
  //**********************************
  $trans = "Enter search string";
  //**********************************

  $code = "\x00\xB0\xCB\xBB\xF6\x20\xB4\xDC\xBE\xEE\x20\xBC\xB3\xC1\xA4\x20\x28\x45\x78\x3A\x20\xB4\xDC\xBE\xEE"
		 ."\x20\xB4\xDC\xBE\xEE\x20\x2E\x2E\x2E\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x45\x6e\x74\x65\x72\x20\x73\x65\x61\x72\x63\x68\x20\x73\x74\x72\x69\x6e\x67\x2e\x2e\x2e"));
 	
  //**********************************
  $trans = "Find";
  //**********************************

  $code = "\x00\xB0\xCB\xBB\xF6\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x46\x69\x6e\x64\x00"));
    	
  //**********************************
  $trans = "Back to navigation";
  //**********************************

  $code = "\x00\xB1\xE6\x20\xC1\xA4\xBA\xB8\x00\xB8\xF1\xC7\xA5\xB7\xCE\x20\xBE\xC8\xB3\xBB\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x42\x61\x63\x6b\x20\x74\x6f\x20\x4e\x61\x76\x69\x67\x61\x74\x69\x6f\x6e\x00\x00"));
    	
  //**********************************
  $trans = "View List";
  //**********************************

  $code = "\x00\xB0\xCB\xBB\xF6\x20\xC1\xA4\xBA\xB8\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x56\x69\x65\x77\x20\x4c\x69\x73\x74\x00"));
 
  //**********************************
  $trans = "Toggle Minimap";
  //**********************************

  $code = "\x00\xBA\xB8\xB1\xE2\x20\xB8\xF0\xB5\xE5\x20\xBA\xAF\xB0\xE6\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x54\x6f\x67\x67\x6c\x65\x20\x4d\x69\x6e\x69\x6d\x61\x70\x00"));
 
  //**********************************
  $trans = "More";
  //**********************************

  $code = "\x00\xC0\xFC\xC0\xE5\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x4d\x6f\x72\x65\x00"));
 
  //**********************************
  $trans = "Water";
  //**********************************

  $code = "\x00\xBC\xF6\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x57\x61\x74\x65\x72\x00\x00\x00\x00"));
 
  //**********************************
  $trans = "Earth";
  //**********************************

  $code = "\x00\xC1\xF6\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x45\x61\x72\x74\x68\x00\x00\x00\x00"));
  
  //**********************************
  $trans = "Shadow";
  //**********************************

  $code = "\x00\xBE\xCF\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x53\x68\x64\x77\x00\x00\x00\x00\x00"));
  
  //**********************************
  $trans = "Fire";
  //**********************************

  $code = "\x00\xC8\xAD\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x46\x69\x72\x65\x00\x00\x00\x00\x00"));

  //**********************************
  $trans = "Ghost";
  //**********************************

  $code = "\x00\xBF\xB0\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x47\x68\x6f\x73\x74\x00\x00\x00\x00"));

  //**********************************
  $trans = "Wind";
  //**********************************

  $code = "\x00\xC7\xB3\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x57\x69\x6e\x64\x00\x00\x00\x00\x00"));

  //**********************************
  $trans = "Poison";
  //**********************************

  $code = "\x00\xB5\xB6\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x50\x6f\x69\x73\x00\x00\x00\x00\x00"));

  //**********************************
  $trans = "Holy";
  //**********************************

  $code = "\x00\xBC\xBA\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x48\x6f\x6c\x79\x00\x00\x00\x00\x00"));  
		
  //**********************************
  $trans = "Undead";
  //**********************************

  $code = "\x00\xBE\xF0\xB5\xA5\xB5\xE5";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x55\x6e\x64\x00\x00\x00\x00"));

  //**********************************
  $trans = "Neutral";
  //**********************************

  $code = "\x00\xB9\xAB\xBC\xD3\xBC\xBA\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x4e\x65\x75\x74\x72\x00\x00\x00\x00"));  
		
  //**********************************
  $trans = "Big";
  //**********************************

  $code = "\x00\xB4\xEB\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x42\x69\x67\x00\x00"));  
		
  //**********************************
  $trans = "Medium";
  //**********************************

  $code = "\x00\xC1\xDF\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x4d\x65\x64\x00\x00"));  
		
  //**********************************
  $trans = "Small";
  //**********************************

  $code = "\x00\xBC\xD2\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x53\x6d\x61\x6c\x00"));  
				
  //**********************************
  $trans = "Demon";
  //**********************************

  $code = "\x00\xBE\xC7\xB8\xB6\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x44\x65\x6d\x6f\x6e\x00\x00"));  

  //**********************************
  $trans = "Demi-Human";
  //**********************************

  $code = "\x00\xC0\xCE\xB0\xA3\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x44\x65\x6d\x69\x2d\x48\x00"));  
				
  //**********************************
  $trans = "Form";
  //**********************************

  $code = "\x00\xB9\xAB\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x46\x6f\x72\x6d\x00"));  

  //**********************************
  $trans = "Undead 2";
  //**********************************

  $code = "\x00\xBE\xF0\xB5\xA5\xB5\xE5\x25\x64\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x55\x6e\x64\x65\x61\x64\x00\x00\x00"));  

  //**********************************
  $trans = "Plant";
  //**********************************

  $code = "\x00\xBD\xC4\xB9\xB0\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x50\x6c\x61\x6e\x74\x00\x00"));  

  //**********************************
  $trans = "Fish";
  //**********************************

  $code = "\x00\xBE\xEE\xC6\xD0\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x46\x69\x73\x68\x00\x00\x00"));  

  //**********************************
  $trans = "Brute";
  //**********************************

  $code = "\x00\xB5\xBF\xB9\xB0\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x42\x72\x75\x74\x65\x00\x00"));  

  //**********************************
  $trans = "Angel";
  //**********************************

  $code = "\x00\xC3\xB5\xBB\xE7\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x41\x6e\x67\x65\x6c\x00\x00"));  

  //**********************************
  $trans = "Insect";
  //**********************************

  $code = "\x00\xB0\xEF\xC3\xE6\xC7\xFC\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x49\x6e\x73\x65\x63\x74\x00"));  

  //**********************************
  $trans = "Dragon";
  //**********************************

  $code = "\x00\xBF\xEB\xC1\xB7\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x44\x72\x61\x67\x00"));  

  //**********************************
  $trans = "Mob";
  //**********************************

  $code = "\x20\x28\xC0\xCF\xB9\xDD\x29\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x20\x28\x4d\x6f\x62\x29\x00\x00"));  

  //**********************************
  $trans = "Boss";
  //**********************************

  $code = "\x20\x28\xBA\xB8\xBD\xBA\x29\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x20\x28\x42\x6f\x73\x73\x29\x00"));  

  //**********************************
  $trans = "To find the location, please go to";
  //**********************************

  $code = "\x00\xBE\xC8\xB3\xBB\xC7\xCF\xB4\xC2\x20\xC0\xA7\xC4\xA1\xB7\xCE\x20\xC0\xCC\xB5\xBF\xC7\xCF\xBD\xC3\xB1\xE2\x20\xB9\xD9\xB6\xF8\xB4\xCF\xB4\xD9\x2E\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x54\x6f\x20\x66\x69\x6e\x64\x20\x74\x68\x65\x20\x6c\x6f\x63\x61\x74\x69\x6f\x6e\x2c\x20\x70\x6c\x65\x61\x73\x65\x20\x67\x6f\x20\x74\x6f\x00\x00\x00"));  

  //**********************************
  $trans = "Arrived at the target map";
  //**********************************

  $code = "\x00\xB8\xF1\xC7\xA5\x20\xB8\xCA\xBF\xA1\x20\xB5\xB5\xC2\xF8\xC7\xCF\xBF\xB4\xBD\xC0\xB4\xCF\xB4\xD9\x2E\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x41\x72\x72\x69\x76\x65\x64\x20\x61\x74\x20\x74\x68\x65\x20\x74\x61\x72\x67\x65\x74\x20\x6d\x61\x70\x00"));  

  //**********************************
  $trans = "Arrived on the map that has the Npc your looking for. Go to that NPC";
  //**********************************

  $code = "\x00\xC3\xA3\xB0\xED\xC0\xDA\x20\xC7\xCF\xB4\xC2\x20\x4E\x70\x63\xB0\xA1\x20\xC0\xD6\xB4\xC2\x20\xB8\xCA\xBF\xA1\x20\xB5\xB5\xC2\xF8\x20\xC7\xCF\xBF\xB4\xBD\xC0\xB4\xCF\xB4\xD9\x2E\x20\x4E\x70\x63\xB7\xCE\x20\xC0\xCC\xB5\xBF\xC7\xCF\xBD\xC3\xB1\xE2\x20\xB9\xD9\xB6\xF8\xB4\xCF\xB4\xD9\x2E\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x41\x72\x72\x69\x76\x65\x64\x20\x6f\x6e\x20\x74\x68\x65\x20\x6d\x61\x70\x20\x74\x68\x61\x74\x20\x68\x61\x73\x20\x74\x68\x65\x20\x4e\x70\x63\x20\x79\x6f\x75\x72\x20\x6c\x6f\x6f\x6b\x69\x6e\x67\x20\x66\x6f\x72\x2e\x20\x47\x6f\x20\x74\x6f\x20\x74\x68\x61\x74\x20\x4e\x50\x43\x00\x00\x00\x00"));  

  //**********************************
  $trans = "Failed to set info for location";
  //**********************************

  $code = "\x00\xB5\xB5\xC2\xF8\x20\xC1\xF6\xC1\xA1\xBF\xA1\x20\xB4\xEB\xC7\xD1\x20\xC1\xA4\xBA\xB8\x20\xBC\xB3\xC1\xA4\x20\xBD\xC7\xC6\xD0\x21";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x46\x61\x69\x6c\x65\x64\x20\x74\x6f\x20\x73\x65\x74\x20\x69\x6e\x66\x6f\x20\x66\x6f\x72\x20\x6c\x6f\x63\x61\x74\x69\x6f\x6e\x21"));  

  //**********************************
  $trans = "Please specify target goals";
  //**********************************

  $code = "\x00\xB8\xF1\xC7\xA5\xB8\xA6\x20\xC1\xF6\xC1\xA4\x20\xC7\xCF\xBD\xC3\xB1\xE2\x20\xB9\xD9\xB6\xF8\xB4\xCF\xB4\xD9\x2E";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x50\x6c\x65\x61\x73\x65\x20\x73\x70\x65\x63\x69\x66\x79\x20\x74\x61\x72\x67\x65\x74\x20\x67\x6f\x61\x6c\x73\x2E"));  

  //**********************************
  $trans = "Is the map that your looking for mob";
  //**********************************

  $code = "\x00\xC3\xA3\xB0\xED\xC0\xDA\x20\xC7\xCF\xB4\xC2\x20\xB8\xF3\xBD\xBA\xC5\xCD\xB0\xA1\x20\xC0\xD6\xB4\xC2\x20\xB8\xCA\x20\xC0\xD4\xB4\xCF\xB4\xD9\x2E\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x49\x73\x20\x74\x68\x65\x20\x6d\x61\x70\x20\x74\x68\x61\x74\x20\x79\x6f\x75\x72\x20\x6c\x6f\x6f\x6b\x69\x6e\x67\x20\x66\x6f\x72\x20\x6d\x6f\x62\x00"));  
  
  //**********************************
  $trans = "Directions were started";
  //**********************************

  $code = "\x00\xB1\xE6\x20\xBE\xC8\xB3\xBB\xB0\xA1\x20\xBD\xC3\xC0\xDB\x20\xB5\xC7\xBE\xFA\xBD\xC0\xB4\xCF\xB4\xD9\x2E";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x44\x69\x72\x65\x63\x74\x69\x6f\x6e\x73\x20\x77\x65\x72\x65\x20\x73\x74\x61\x72\x74\x65\x64\x00\x00\x2E"));  
  
  //**********************************
  $trans = "Please go to the airship";
  //**********************************

  $code = "\x20\xBA\xF1\xB0\xF8\xC1\xA4\xC0\xB8\xB7\xCE\x20\xC0\xCC\xB5\xBF\x20\xC7\xCF\xBD\xC3\xB1\xE2\x20\xB9\xD9\xB6\xF8\xB4\xCF\xB4\xD9\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x20\x50\x6c\x65\x61\x73\x65\x20\x67\x6f\x20\x74\x6f\x20\x74\x68\x65\x20\x61\x69\x72\x73\x68\x69\x70\x00\x00\x00\x00\x00\x00\x00\x00"));  

  //**********************************
  $trans = "(%s) map, please move to";
  //**********************************

  $code = "\x20\xB8\xCA\x28\x25\x73\x29\xC0\xB8\xB7\xCE\x20\xC0\xCC\xB5\xBF\xC7\xCF\xBD\xC3\xB1\xE2\x20\xB9\xD9\xB6\xF8\xB4\xCF\xB4\xD9\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x20\x28\x25\x73\x29\x20\x6d\x61\x70\x2c\x20\x70\x6c\x65\x61\x73\x65\x20\x6d\x6f\x76\x65\x20\x74\x6f\x00\x00\x00\x00\x00\x00\x00"));  

  //**********************************
  $trans = "Guidance to %s (A) By using";
  //**********************************

  $code = "\x20\xBE\xC8\xB3\xBB\xC7\xCF\xB4\xC2\x20\x25\x73\x28\xC0\xBB\x29\xB8\xA6\x20\xC0\xCC\xBF\xEB\xC7\xCF\xBF\xA9\x00\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x20\x47\x75\x69\x64\x61\x6e\x63\x65\x20\x74\x6f\x20\x25\x73\x20\x28\x41\x29\x20\x42\x79\x20\x75\x73\x69\x6e\x67\x00"));  
  
  //**********************************
  $trans = "Do you want to cancel navigation?";
  //**********************************

  $code = "\x00\xBE\xC8\xB3\xBB\xC1\xDF\x20\xC0\xD4\xB4\xCF\xB4\xD9\x21\x20\xC1\xBE\xB7\xE1\x20\xC7\xCF\xBD\xC3\xB0\xDA\xBD\xC0\xB4\xCF\xB1\xEE\x3F\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x44\x6f\x20\x79\x6f\x75\x20\x77\x61\x6e\x74\x20\x74\x6f\x20\x63\x61\x6e\x63\x65\x6c\x20\x6e\x61\x76\x69\x67\x61\x74\x69\x6f\x6e\x3f\x00"));  

  //**********************************
  $trans = "Use Scroll ?";
  //**********************************

  $code = "\x00\xB4\xF8\xC0\xFC\xC0\xCC\xB5\xBF\x20\xBD\xBA\xC5\xA9\xB7\xD1\x20\xBB\xE7\xBF\xEB\x20\xC0\xFB\xBF\xEB\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x55\x73\x65\x20\x53\x63\x72\x6f\x6c\x6c\x20\x3f\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));  

  //**********************************
  $trans = "Use Kafra Warp ?";
  //**********************************

  $code = "\x00\xC0\xA7\xC4\xA1\x20\xC0\xCC\xB5\xBF\x20\xBC\xAD\xBA\xF1\xBD\xBA\x20\xC7\xE3\xBF\xEB\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x55\x73\x65\x20\x4b\x61\x66\x72\x61\x20\x57\x61\x72\x70\x20\x3f\x00\x00\x00\x00\x00\x00"));  

  //**********************************
  $trans = "Use Airship ?";
  //**********************************

  $code = "\x00\xBA\xF1\xB0\xF8\xC1\xA4\x20\xC0\xCC\xB5\xBF\x20\xC0\xFB\xBF\xEB\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x55\x73\x65\x20\x41\x69\x72\x73\x68\x69\x70\x20\x3f\x00\x00\x00\x00"));  

  //**********************************
  $trans = "Found";
  //**********************************

  $code = "\x00\xB5\xB5\xC2\xF8\x2E\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x46\x6F\x75\x6E\x64\x00"));  

  //**********************************
  $trans = "You";
  //**********************************

  $code = "\x00\xA1\xDA\x00\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x59\x6F\x75\x00"));  

  //**********************************
  $trans = "Path Failure";
  //**********************************

  $code = "\x00\xB1\xE6\x20\xC3\xA3\xB1\xE2\x20\xBD\xC7\xC6\xD0\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x50\x61\x74\x68\x20\x46\x61\x69\x6c\x75\x72\x65\x00"));  

  //**********************************
  $trans = "Guidance";
  //**********************************

  $code = "\x00\x3D\x3D\x3D\x3D\x3D\x3D\x3D\x20\xB1\xE6\x20\xBE\xC8\xB3\xBB\x20\xC1\xA4\xBA\xB8\x20\x3D\x3D\x3D\x3D\x3D\x3D\x3D\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x3d\x3d\x3d\x3d\x3d\x3d\x3d\x20\x47\x75\x69\x64\x61\x6e\x63\x65\x20\x3d\x3d\x3d\x3d\x3d\x3d\x3d\x00\x00\x00\x00\x00"));  

  //**********************************
  $trans = "Goal";
  //**********************************

  $code = "\x00\xB8\xF1\xC7\xA5\x20\x20\x20\x3A\x20\x25\x73\x28\x25\x73\x29\x00\xC0\xCF\xB9\xDD\x00\x00\x00\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x47\x6f\x61\x6c\x20\x20\x20\x3a\x20\x25\x73\x28\x25\x73\x29\x47\x65\x6e\x65\x72\x61\x6c\x00\x00"));  

  //**********************************
  $trans = "Goal 2";
  //**********************************

  $code = "\x00\xB8\xF1\xC7\xA5\x20\x20\x20\x3A\x20";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x47\x6f\x61\x6c\x20\x3a\x00\x00\x20"));  

  //**********************************
  $trans = "Coord";
  //**********************************

  $code = "\x00\xB8\xF1\xC7\xA5\xB8\xCA\x3A\x20\x25\x73\x28\x25\x73\x29\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x43\x6f\x6f\x72\x64\x73\x20\x25\x73\x28\x25\x73\x29\x00\x00"));  

  //**********************************
  $trans = "Result";
  //**********************************

  $code = "\x00\x3D\x3D\x3D\x3D\x3D\x3D\x3D\x3D\x20\xB0\xE1\xB0\xFA\x20\xC1\xA4\xBA\xB8\x20\x3D\x3D\x3D\x3D\x3D\x3D\x3D\x3D\x3D\x3D\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x3d\x3d\x3d\x3d\x3d\x3d\x3d\x3d\x20\x52\x65\x73\x75\x6c\x74\x73\x20\x3d\x3d\x3d\x3d\x3d\x3d\x3d\x3d\x3d\x3d\x00\x00\x00"));  

  //**********************************
  $trans = "Dist  %d Cell %d WarpMove";
  //**********************************

  $code = "\x00\xB0\xC5\xB8\xAE\x20\x20\x20\x3A\x20\x25\x64\x20\x53\x65\x6C\x6C\x20\x25\x64\x20\x57\x61\x72\x70\x4D\x6F\x76\x65\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x44\x69\x73\x74\x20\x20\x25\x64\x20\x43\x65\x6c\x6c\x20\x25\x64\x20\x57\x61\x72\x70\x4d\x6f\x76\x65\x00\x00\x00\x00"));  
  
  //**********************************
  $trans = "Found (%d)";
  //**********************************

  $code = "\x00\x00\x3D\x3D\x20\xB0\xCB\xBB\xF6\x20\xB0\xE1\xB0\xFA\x28\x25\x64\x29\x3D\x3D\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x3d\x3d\x20\x46\x6f\x75\x6e\x64\x20\x28\x25\x64\x29\x20\x3d\x3d\x00\x00\x00\x00"));  

  //**********************************
  $trans = "Please go to indicated direction";
  //**********************************

  $code = "\x00\xB0\xA1\xB8\xAE\xC5\xB0\xB4\xC2\x20\xB9\xE6\xC7\xE2\xC0\xB8\xB7\xCE\x20\xB0\xA1\xBD\xC3\xB1\xE2\x20\xB9\xD9\xB6\xF8\xB4\xCF\xB4\xD9\x2E\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x50\x6c\x65\x61\x73\x65\x20\x67\x6f\x20\x74\x6f\x20\x69\x6e\x64\x69\x63\x61\x74\x65\x64\x20\x64\x69\x72\x65\x63\x74\x69\x6f\x6e\x2e\x00\x00"));  

  //**********************************
  $trans = "<< Goal... >>";
  //**********************************

  $code = "\x00\x3C\x3C\x20\xB0\xCB\xBB\xF6\xC1\xDF\x2E\x2E\x2E\x20\x3E\x3E\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x3c\x3c\x20\x47\x6f\x61\x6c\x2e\x2e\x2e\x20\x3e\x3e\x00\x00\x00"));  

  //**********************************
  $trans = "Goal: (%d, %d)";
  //**********************************

  $code = "\x00\xB8\xF1\xC7\xA5\x3A\x20\x28\x25\x64\x2C\x20\x25\x64\x29\x00";
  $offset = $exe->match($code, "\xAB", 0);
  if ($offset === false) {
    echo "Failed in {$trans} part 1";
    return false;
  }
  $exe->replace($offset, array(0 => "\x00\x47\x6f\x61\x6c\x3a\x20\x28\x25\x64\x2c\x20\x25\x64\x29\x00"));  

  
  return true;
}
?>