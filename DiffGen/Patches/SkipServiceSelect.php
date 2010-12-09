<?php
function SkipServiceSelect($exe){
	if ($exe === true) {
		return "[UI]_Skip_Service_Select";
	}
	
	// Find JE SHORT <address>
	$code	=	"\x74\x07\xC6\x05\xAB\xAB\xAB\xAB\x01\x68".pack("I", $exe->str("passwordencrypt","rva"));
	$offset	=	$exe->code($code,	"\xAB");
	if ($offset	===	false) {
		echo "Failed in	part 1";
		return false;
	}
	
	// Skip short jump
	$exe->replace($offset, array(0 =>	"\x90\x90"));
	
	// Shinryo:
	// Gravity has their clientinfo hardcoded and seperated the initialization, screw 'em.. :(
	// Therefore just replace (jump over) their call with a standard one which parses clientinfo.xml
	// SelectKoreaClientInfo() -> SelectClientInfo()
	// TO-DO: Maybe use a seperate diff? Dunno.
	$code	=	"\xE8\xAB\xAB\xFF\xFF\xE9\xAB\xAB\xFF\xFF\x6A\x00\xE8\xAB\xAB\xFF\xFF\x83\xC4\x04";
	$offset	=	$exe->code($code,	"\xAB");
	if ($offset	===	false) {
		echo "Failed in	part 2";
		return false;
	}
	
	$exe->replace($offset, array(0 =>	"\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90"));
	
	return true;
}
?>