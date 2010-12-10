<?php
// 10.12.2010 - I think the diff I've placed inside SkipServiceSelect was
//							KoreaServiceTypeXMLFix. Even though, the previous version of this diff
//							just replaced the properties with those of america in the wrong way. [Shinryo]

function KoreaServiceTypeXMLFix($exe){
	if ($exe === true) {
		return "[Fix]_KOREA_ServiceType_XML_Fix_(Recommended)";
	}
	
	// Shinryo:
	// Gravity has their clientinfo hardcoded and seperated the initialization, screw 'em.. :(
	// SelectKoreaClientInfo() has for example global variables like g_extended_slot set
	// which aren't set by SelectClientInfo(). Just call both functions will fix this as the
	// changes from SelectKoreaClientInfo() will persist and overwritten by SelectClientInfo().
	// TO-DO: Maybe use a seperate diff? Dunno.
	$code	=	"\xE8\xAB\xAB\xFF\xFF\xE9\xAB\xAB\xFF\xFF\x6A\x00\xE8\xAB\xAB\xFF\xFF\x83\xC4\x04";
	$offset	=	$exe->code($code,	"\xAB");
	if ($offset	===	false) {
		echo "Failed in	part 2";
		return false;
	}

	$exe->replace($offset, array(5 =>	"\x90\x90\x90\x90\x90"));
	
	return true;
}
?>