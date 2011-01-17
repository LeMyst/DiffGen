<?php
    function XtractPacketLength($exe) {
        global $target;
        if ($exe === true) {
            return "[Yom]_eXtract_Packet_Length";
        }
        $code = "\x55\x8B\xEC\x83\xE4\xF8\x83\xEC\xAB\x56\x8B\xF1\xB8";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $fp = fopen("PacketTables\\" . basename($target, ".exe") . ".txt", 'w');
        fwrite($fp,"Extracted With DiffGen2\n\n");
        // time to walk some code
        $ptr = 0;
        $done = 0;
        while(!$done) {
            $ins = bin2hex($exe->read($offset + $ptr, 1));
            switch($ins){
                case "50":
                case "51":
                case "52":
                case "55":
                case "56":
                case "57":
                    $ptr += 1; // push
                    break;
                case "8b":
                    $ptr += 2; // mov ebp, esp
                    break;
                case "83":
                    $ptr += 3;
                    break;
                case "b8":
                    $len = $exe->read($offset + $ptr + 1, 4, "L");
                    $ptr += 5;
                    break;
                case "b9":
                    $len = "-1";
                    $ptr += 5;
                    break;
                case "89":
                    if(bin2hex($exe->read($offset + $ptr + 1, 1)) == "7c") {
                        $pak = $pak2; // packet length read from edi
                    }
                case "8d":
                    $ptr += 4;
                    break;
                case "c7":
                    $pak = $exe->read($offset + $ptr + 4, 4, "L");
                    $ptr += 8;
                    break;
                case "bf": // packet length moved to edi
                    $pak2 = $exe->read($offset + $ptr + 1, 4, "L");
                    $ptr += 5;
                    break;
                case "e8":
                    if(!$pak || !$len){
                        $ptr = dechex($offset+$ptr);
                        echo "pak or len not set $ins @ $ptr #";
                        $done = 1;
                        break;
                    }
                    fwrite($fp, "0x". str_pad(dechex($pak), 3, "0", STR_PAD_LEFT).",$len\n");
                    $pak = null;
                    $len = null;
                    $ptr += 5;
                    break;
                case "5d":
                case "5f":
                case "5e":
                case "c3":
                    $done = 1;
                    break;
                default:
                    $ptr = dechex($offset+$ptr);
                    echo "unknown opcode $ins @ $ptr #";
                    $done = 1;
                    break;
            }
        }
        fclose($fp);
    }
?>