<?php
// 08.03.2010 - Started to rework some translations (this will be a hell of work) [Shinryo]

function TranslateClientInEnglish($exe){
	if ($exe === true) {
		return "[UI]_Translate_Client_In_English_(Recommended)";
	}
	
	// I would like to to use a huge array list with codes and replacements, but it isn't really
	// clean to see what is where and why. So just split them in parts.
	
	// Some time declarations (e.g. delete system)
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
			echo "\n\nTranslateClientInEnglish > Times: String length error at index $index\n\n";
			return false;
		}
		
		$offset = $exe->match($codes[$index], "\xAB", 0);
		if($offset === false) {
			echo "\nTranslateTimes: Failed at index {$index}\n";
			return false;
		}
		$exe->replace($offset, array(0 => $changes[$index]."\x00"));
	}
	
	// Next entry..
	
	return true;
}
?>