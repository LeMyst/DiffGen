<?php
		//Patches 29-32
		function IncreaseZoomOut($exe) {
			if ($exe === true) {
				return new xPatchGroup(29, 'Increase Zoom', array(
						'IncreaseZoomOut50Per',
						'IncreaseZoomOut75Per',
						'IncreaseZoomOutMax'));
			}
		}

    function IncreaseZoomOut50Per($exe) {
        if ($exe === true) {
            return new xPatch(30, 'Increase Zoom Out 50%', 'UI', 29, 'Increases the zoom-out range by 50 percent');
        }
        $code = "\x00\x00\x66\x43\x00\x00\xC8\x43\x00\x00\x96\x43";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) == 0) {
            echo "Failed in part 1";
            return false;
        }
        $offset = $offsets[0];
        $exe->replace($offset, array(6 => "\xFF"));
        return true;
    }

    function IncreaseZoomOut75Per($exe) {
        if ($exe === true) {
            return new xPatch(31, 'Increase Zoom Out 75%', 'UI', 29, 'Increases the zoom-out range by 75 percent');
        }
        $code = "\x00\x00\x66\x43\x00\x00\xC8\x43\x00\x00\x96\x43";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) == 0) {
            echo "Failed in part 1";
            return false;
        }
        $offset = $offsets[0];
        $exe->replace($offset, array(6 => "\x4C\x44"));
        return true;
    }

    function IncreaseZoomOutMax($exe) {
        if ($exe === true) {
            return new xPatch(32, 'Increase Zoom Out Max', 'UI', 29, 'Maximizes the zoom-out range');
        }
        $code = "\x00\x00\x66\x43\x00\x00\xC8\x43\x00\x00\x96\x43";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) == 0) {
            echo "Failed in part 1";
            return false;
        }
        $offset = $offsets[0];
        $exe->replace($offset, array(6 => "\x99\x44"));
        return true;
    }
    
?>