<?php
    function RemoveHourlyAnnonce($exe) {
        if ($exe === true) {
            return new xPatch(73, 'Remove Hourly Annonce', 'UI', 0, 'Remove hourly game grade and hourly play time minder annonce');
        }
		
		// RemoveHourlyGameGrade
		/*
		  "75 34"          // JNZ     SHORT ADDR v
		  "66 8B4424 AB"   // MOV     AX,WORD PTR SS:[ESP+?]
		  "66 85C0"        // TEST    AX,AX
		  "75 15"          // JNZ     SHORT ADDR v
		  "84C9"           // TEST    CL,CL
		  "75 26"          // JNZ     SHORT ADDR v
		  "B1 01"          // MOV     CL,1
		  "33C0"           // XOR     EAX,EAX
        */
		
		if ($exe->clientdate() <= 20130605)
			$code =  "\x75\x34\x66\x8B\x44\x24\xAB";
		else
			$code =  "\x75\x33\x66\x8B\x45\xE8\xAB";
		
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }

        $exe->replace($offset, array(0 => "\xEB")); // JNZ -> JMP
		
		// RemoveHourlyPlaytimeMinder
		/*
		0   "B8 B17C2195"  // MOV     EAX,95217CB1h
		5   "F7E1"         // MUL     ECX
		7   "8BFA"         // MOV     EDI,EDX
		9   "C1EF 15"      // SHR     EDI,15h
		12  "3BFD"         // CMP     EDI,R32  ; R32 is initialized to 0
		14  "0F8E"         // JLE     ADDR v
		*/
        $code =  "\xB8\xB1\x7C\x21\x95";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }

        $exe->replace($offset, array(14 => "\x90\xE9")); // JLE -> NOP and JLE -> JMP
		
        return true;
    }
?>