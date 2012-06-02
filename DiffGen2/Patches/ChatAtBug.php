<?php
    function ChatAtBug($exe, $free = false){
        global $clientdate2;
        if( $exe === true )
            return "[Fix]_Chat_@-Bug";

        /*
            pushad

            movzx    eax, byte ptr [esi+44]
            test    eax, eax
            jz    __PROCESS

            mov    esi, ds:0xDEADBEEF ; GetAsyncKeyState
            push    012h        ; VK_MENU
            call    esi
            test    eax, eax
            jz        __PROCESS
            push    011h        ; VK_CONTROL
            call    esi
            test    eax, eax
            jz        __PROCESS
    
            ;; Discard
            popad
            xor    eax, eax
            retn    4
    
            __PROCESS:
            popad
            push    0xDEADC0DE ; Process Function
            retn

        */
        $code = "\x60\x0F\xB6\x46\x2C\x85\xC0\x74\x1D\x3E\x8B\x35\xEF\xBE\xAD\xDE\x6A\x12\xFF\xD6\x85\xC0\x74\x0E\x6A\x11\xFF\xD6\x85\xC0\x74\x06\x61\x33\xC0\xC2\x04\x00\x61\x68\xDE\xC0\xAD\xDE\xC3";
        if( !$free )
            $free = $exe->zeroed(strlen($code)+1);
        if( !$free ) {
            echo "Failed in part 1";
            return false;
        }

        $ptr = $exe->match("\x00\x01\x01\x01\x00\x01\x01\x00\x00\x00\x01\x00\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x00\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01", '', 0);
        if( !$ptr ) {
            echo "Failed in part 2";
            return false;
        }
        $ptr2 = @$exe->match("\x57\x8B\xCE\xE8", '', $ptr-0x80, $ptr-0x30);
        if( !$ptr2 ) $ptr2 = @$exe->match("\x50\x8B\xCE\xE8", '', $ptr-0x80, $ptr-0x30);
        if( !$ptr2 ) {
            echo "Failed in part 3";
            return false;
        }
        $ptr = $ptr2 + 3;
        echo "#ptr=0x" . dechex($ptr) . " #ptr2=0x" . dechex($ptr2) . " #";
        $f_ptr = $exe->imagebase() + $ptr + 5 + $exe->read($ptr+1,4,'I');

        $iat_keystate = $exe->func('GetAsyncKeyState', true);
        if( !$iat_keystate ) {
            echo "Failed in part 4";
            return false;
        }

        $code = str_replace("\xEF\xBE\xAD\xDE", pack('V', $iat_keystate), $code);
        $code = str_replace("\xDE\xC0\xAD\xDE", pack('V', $f_ptr), $code);

        $exe->insert($code, $free);
        $exe->insert("\xE8" . pack('V', $free - ($ptr + 5)), $ptr);
        return true;
        
    }
?>