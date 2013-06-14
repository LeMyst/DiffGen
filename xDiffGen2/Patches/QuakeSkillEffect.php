<?php
function QuakeSkillEffect($exe) {
    if ($exe === true) {
        return new xPatch(67, 'Disable Quake skill effect', 'UI', 0, '');
    }
	
	// MISSION: Find CView::SetQuake and CView::SetQuakeInfo.
	// You are pretty much lost, if you are not able to hunt
	// either of them down, as they are next to each other. One
	// VC6 hint being: Look for PUSH 3E4CCCCDh, PUSH 3E75C28F
	// and PUSH 3F800000h. The next call after these 3 PUSHs is
	// CView::SetQuake, right above it is CView::SetQuakeInfo.
	// VC9 does not push float values like longs, but pull them
	// out of somewhere. The tail of CView::SetQuake can serve
	// for comparison.
	if ($exe->clientdate() <= 20130605)
		$code =  "\xD9\x44\x24\x04\xD9\x59\x04\xD9\x44\x24\x0C\xD9\x59\x0C\xD9\x44\x24\x08\xD9\x59\x08\xC2\x0C\x00\xCC\xCC\xCC\xCC\xCC\xCC\xCC\xCC\x8B\x44\x24\x04";
    else
		$code =  "\x55\x8B\xEC\xD9\x45\x08\xD9\x59\x04\xD9\x45\x10\xD9\x59\x0C\xD9\x45\x0C\xD9\x59\x08\x5D\xC2\x0C\x00\xCC\xCC\xCC\xCC\xCC\xCC\xCC\x55\x8B\xEC\x8B\x45\x08";
		
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

	if ($exe->clientdate() <= 20130605){
		$exe->replace($offset, array(0 => "\xC2\x0C\x00"));
		$exe->replace($offset, array(32 => "\xC2\x14\x00"));
	}
	else {
		$exe->replace($offset, array(0 => "\x90\x90\x90"));
		$exe->replace($offset, array(3 => "\xC2\x0C\x00"));
		$exe->replace($offset, array(35 => "\xC2\x14\x00"));	
	}

    return true;
	
}
?>