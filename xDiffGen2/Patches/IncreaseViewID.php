<?php
    function IncreaseViewID($exe) {
        if ($exe === true) {
            return new xPatch(28, 'Increase Headgear ViewID to 5000', 'Add', 0, 'Increases the limit for the headgear ViewIDs from 2000 to 5000');
        }
        
		//Step 1 - Find ReqAccName
		$reqacc = pack("I",$exe->str("ReqAccName","rva"));

		//Step 2 - Find where it is pushed - there is only 1 place
		$reqpush = $exe->code("\x68".$reqacc, "\xAB");
		
		//Step 3 - Get Little Endian byte sequence of old value (2000 on 2013 clients) & new value 5000
		$oldValue = pack("V", 2000);
		$newValue = pack("V", 5000);
		
		//Step 4 - Replace old value in the cmp/push/mov instructions before and after the push - lets start with a relative limit of -100 from the push.
		if ($exe->clientdate() > 20130605)
			$count = 3; //there are two cmp and 1 mov instruction
		else
			$count = 2; //there is 1 push and 1 cmp instruction
		
		$offset = $reqpush - 400;
		for ($i = 1; $i <= $count; $i++)
		{
			$offset = $exe->match($oldValue,"", $offset);
			if($offset === false)
			{
				echo "Failed at Part 4: iteration $i";
				return false;
			}
			$exe->replace($offset, array(0=>$newValue));
			$offset += 4;
		}
        return true;
    }
?>