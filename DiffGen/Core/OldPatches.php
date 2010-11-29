<?php
/*
This file contains all the possible patches.
Each function is a different patch. When called
with a "true" argument, it returns the patch 
name as it appears on the diff files. When called
with a Sakexe object, it tries to apply the patch,
and returns false on failure and true uppon success.
Please note that success means it found the offsets
it needs, but doesn't mean it'll work, so it's a
good idea to test them running the client.

By ffmm
*/

class Patches
{

    static public function AuraCrashfix($exe)
    {
        if ($exe === true) {
            return "[Fix]_Aura_Crashfix";
        }
        $codes = array(
            "\x3B\xC1\x75\x2B\xA0\xAB\xAB\xAB\xAB\x3C\x02\x7F",
            "\x3B\xC1\x75\x29\xA0\xAB\xAB\xAB\xAB\x3C\x02\x7F",
        );
        $codeoffsets = array(2,2);
        $changes = array("\x90\x90","\x90\x90");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        $code = "";
        // Uncomment if it breaks...
        //$code = "\xA0\xAB\xAB\xAB\xAB";
        $len = strlen($code);
        $codes = array("\x3C\x02\x7C\x07\x8B\xCE\xE8\xAB\xAB\x00\x00");
        $codeoffsets = array((2 + $len),(2 + $len));
        $changes = array("\x90\x90");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function FixClientFreeze($exe)
        {
        if ($exe === true) {
            return "[Fix]_Fix_Client_Freeze_Langtype_1+";
        }
        $codes = array("\x85\xC0\x75\xAB\x5F\x8B\xC3\x5E\x5B\x8B\x4D");
        $codeoffsets = array(2);
        $changes = array("\x90\x90");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function AdjustFontSize($exe, $older = false)
    {
        if ($exe === true) {
            return "[UI]_Adjust_Font_Size";
        }
        $codes = array("\x1B\xD2","");
        foreach ($codes as $code) {
            $len = strlen($code);
            $code .= "\x8B\xAB\xAB\x6A\x00\xAB";
            if (!$older) {
                $code .= "\x81\xE2\xAB\xAB\xAB\xAB";
            } else {
                $code .= "\x25\xAB\xAB\xAB\xAB";
            }
            $code .= "\x6A\x00\x6A\x00";
            if (!$older) {
                $code .= "\x81\xC2\xAB\xAB\xAB\xAB";
            } else {
                $code .= "\x05\xAB\xAB\xAB\xAB";
            }
            $code .= "\x6A\x00\xAB\x6A\x00\x6A\x00\x6A\x00\xAB";
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            if (!$older) {
                return self::AdjustFontSize($exe, true);
            } else {
                echo "Failed in part 1";
                return false;
            }
        }
        $data = $exe->read($offset + $len, strlen($code) - $len);
        $data = substr($data, 3, strlen($data) - 4);
        $data .= "\x6A\xF5\x90\x90";
        $exe->replace($offset, array((0 + $len) => $data));
        return true;
    }
    
    static public function UnlimitedChatFlood($exe)
    {
        if ($exe === true) {
            return "[UI](1)_Unlimit_Chat_Flood";
        }
        $code = "\x52\x56\xE8\xAB\xAB\xAB\xAB\x83\xC4\x08\x84\xC0\x74\xAB\xFF\x83\xAB\xAB\xAB\xAB\xEB\xAB\xC7\xAB\xAB\xAB\xAB\x00\x00\x00\x00\x00\x8B\x83\xAB\xAB\xAB\xAB\x50";
        $offsets = $exe->code($code, "\xAB", 4);
        if ($offsets === false) {
            echo "Failed in part 1";
            return false;
        }
        foreach ($offsets as $offset) {
            $exe->replace($offset, array(12 => "\xEB"));
        }
        return true;
    }
    
    static public function DisableSwearFilter($exe)
    {
        // Size is different on different diffs, which one is true?
        if ($exe === true) {
            return "[UI]_Disable_Swear_Filter";
        }
        $code = "\x51\xB9\xAB\xAB\xAB\xAB\xE8\xAB\xAB\xAB\xAB\x84\xC0\x74\x53\x6A\x00\x6A\x00\x6A\x00\x6A\x00\x6A\x03";
        $offsets = $exe->code($code, "\xAB", -1);
        if ($offsets === false) {
            echo "Failed in part 1";
            return false;
        }
        foreach ($offsets as $offset) {
            $exe->replace($offset, array(13 => "\xEB"));
        }
        return true;
    }
    
    static public function EnableASCIIinText($exe)
    {
        if ($exe === true) {
            return "[UI]_Enable_ASCII_in_text";
        }
        $code = "\x7E\x0B\xF6\x04\x10\x80\x75\x0C\x40\x3B\xC1";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(6 => "\x90\x90"));
        return true;
    }
    
    static public function EnableFlagEmotes($exe)
    {
        if ($exe === true) {
            return "[UI]_Enable_Flag_Emotes";
        }
        $codes = array(
        "\x74\x6E\x83",
        "\x74\x4B\x83\xF8\x06",
        "\x74\x6E\x83\xF8\x06",
        );
        $codeoffsets = array(0,0,0);
        $changes = array("\xEB","\xEB","\xEB");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));

        $code = "\x74\x28\x83\xF8\x06";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(0 => "\xEB"));

        $code = "\x74\x28\x83\xF8\x07";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array(0 => "\xEB"));

        $code = "\x0F\x84\x3B\x01\x00\x00\x83";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 4";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\xE9"));

        $code = "\x74\x2D\x83\xF8\x08";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 5";
            return false;
        }
        $exe->replace($offset, array(0 => "\xEB"));

        $code = "\x0F\x84\xAB\x06\x00\x00\xAB\xAB\xAB\xAB\xAB\x85\xC0\x75\x1E\x8B\x0D";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 6";
            return false;
        }
        $exe->replace($offset, array(13 => "\x72"));

        $code = "\x0F\x84\x28\x01\x00\x00\x83\xF8";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 7";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\xE9"));

        $code = "\x0F\x84\xAB\x05\x00\x00\xAB\xAB\xAB\xAB\xAB\x85\xC0\x75\x1E\x8B\x0D";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 8";
            return false;
        }
        $exe->replace($offset, array(13 => "\x72"));

        $code = "\x0F\x84\xAB\x04\x00\x00\xAB\xAB\xAB\xAB\xAB\x85\xC0\x75\x1E\x8B\x0D";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 9";
            return false;
        }
        $exe->replace($offset, array(13 => "\x72"));

        return true;
    }

    
    static public function EnableNewTradeWindow($exe)
    {
        if ($exe === true) {
            return "[UI]_Enable_New_Trade_Window";
        }
        $code = "\xC7\x80\x48\x3F\x00\x00\xAB\xAB\xAB\xAB\xA1\xAB\xAB\xAB\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $langtype = $exe->read($offset + 11, 4);
        $code = "\xA1" . $langtype . "\x3B\xC6\x0F\x85\xAB\xAB\xAB\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(7 => "\x90\x90\x90\x90\x90\x90"));


        $codes = array(
        "\xA1" . $langtype . "\x53\x56\x57\x85\xC0\x8B\xD9\x0F\x85\xAB\xAB\xAB\xAB",
        "\xA1" . $langtype . "\x53\x56\x57\x85\xC0\x8B\xD9\x6A\x00\x0F\x85\xAB\xAB\xAB\xAB",
        );
        $codeoffsets = array(14,14);
        $changes = array("\x90\x90\x90\x90\x90\x90","\x90\x90\x90\x90\x90\x90");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false)
                break;
        }
        if ($offset === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        $code = "\xA1" . $langtype . "\x53\x56\x57\x33\xFF\x3B\xC7\x89\x7D\xFC\x0F\x85\xAB\xAB\xAB\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 4";
            return false;
        }
        $exe->replace($offset, array(15 => "\x90\x90\x90\x90\x90\x90"));
        $code = "\xA1" . $langtype . "\x85\xC0\x75\x30";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 5";
            return false;
        }
        $exe->replace($offset, array(7 => "\x90\x90"));
        $code = "\x8B\x0D" . $langtype . "\x56\x33\xF6\x57\x3B\xCE\x89\x45\xFC\xC7\x45\xF0\x03\x00\x00\x00\x75\x10";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 6";
            return false;
        }
        $exe->replace($offset, array(22 => "\x90\x90"));
        $code = "\xA1" . $langtype . "\x85\xC0\xC7\x45\xFC\xFF\xFF\xFF\xFF\x75\x4E";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 7";
            return false;
        }
        $exe->replace($offset, array(14 => "\x90\x90"));
        return true;
    }
    
    static public function FixTradeWindowCrash($exe)
    {
        if ($exe === true) {
            return "[Fix]_Trade_Window_Crash_Fix_(Recommended)";
        }
        $codes = array(
                "\xEB\x02\x33\xFF\x68\x79\x01\x00\x00\x68\x30\x02\x00\x00",
        "\x0F\x85\xAB\xAB\xAB\xAB\x68\x79\x01\x00\x00\x68\x30\x02\x00\x00",
                        "\x75\x4E\x68\x79\x01\x00\x00\x68\x30\x02\x00\x00",
        );
        $codeoffsets = array(0,0,0);
        $changes = array(
        "\x90\x90\x90\x90",
        "\x90\x90\x90\x90\x90\x90",
        "\x90\x90"
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function IgnoreMissingFileErrors($exe)
    {
        if ($exe === true) {
            return "[UI]_Ignore_Missing_File_Errors";
        }
        $code = "\x55\x8B\xEC\xE8\xAB\xAB\xAB\xAB\x8B\x45\xAB\x8B\x0D\xAB\xAB\xAB\xAB\x6A\x00";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\xC3"));
        return true;
    }
    
    static public function IgnoreMissingPaletteErrors($exe)
    {
        if ($exe === true) {
            return "[UI]_Ignore_Missing_Palette_Errors";
        }
        $code = "\xC7\x45\xFC\x00\x00\x00\x00";
        // Uncomment this if it breaks:
        //$code = "";
        $len = strlen($code);
        $code .= "\xE8\xAB\xAB\xAB\xAB\x84\xC0\x0F\x85\x83\x01\x00\x00\x8A\xAB\xAB\x53";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array((7 + $len) => "\x90\xE9"));
        return true;
    }
    
    static public function DisableEncryptationInLoginPacket0x2b0($exe)
    {
        if ($exe === true) {
            return "[Fix]_Disable_Encryption_in_Login_Packet_0x2b0_(Recommended)";
        }
        $codes = array(
        "\x8D\x75\xAB"     .    "\x8D\xBD\x2E\xFF\xFF\xFF",
        "\x8D\x75\xAB"     .    "\x8D\xBD\x32\xFF\xFF\xFF",
        "\x8D\xB5\x74\xFF\xFF\xFF\x8D\xBD\x0A\xFF\xFF\xFF",
        "\x8D\xB5\x78\xFF\xFF\xFF\x8D\xBD\x0E\xFF\xFF\xFF",
        "\x8D\xB5\x78\xFF\xFF\xFF\x8D\xBD\x22\xFF\xFF\xFF",
        );
        $codeoffsets = array(1,1,1,1,1);
        $changes = array("\x33\x90","\x33\x90","\x33\x90\x90\x90\x90","\x33\x90\x90\x90\x90","\x33\x90\x90\x90\x90");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function EnforceLoginPacket0x2b0($exe)
    {
        if ($exe === true) {
            return "[Packet](11)_Enforce_Login_Packet_0x2b0";
        }
        $codes = array(
        "\x85\xC0\x0F\x85\xAF\x01\x00\x00",
        "\x85\xC0\x0F\x85\xAC\x01\x00\x00",
        "\x85\xC0\x0F\x85\xB5\x01\x00\x00",
        );
        $codeoffsets = array(2,2,2);
        $changes = array("\x90\x90\x90\x90\x90\x90","\x90\x90\x90\x90\x90\x90","\x90\x90\x90\x90\x90\x90");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function DisableLoginPacket0x2b0($exe)
    {
        if ($exe === true) {
            return "[Packet](11)_Disable_Login_Packet_0x2b0";
        }
        $codes = array(
        "\x85\xC0\x0F\x85\xAF\x01\x00\x00",
        "\x85\xC0\x0F\x85\xAC\x01\x00\x00",
        "\x85\xC0\x0F\x85\xB5\x01\x00\x00",
        );
        $codeoffsets = array(2,2,2);
        $changes = array("\xE9\xB0\x01\x00\x00\x90","\xE9\xB0\x01\x00\x00\x90","\xE9\xB0\x01\x00\x00\x90");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function DisableHallucinationWavyScreen($exe)
    {
        if ($exe === true) {
            return "[Fix]_Disable_Hallucination_Wavy_Screen";
        }
        $code = "\x8D\x73\xAB\x89\x15\xAB\xAB\xAB\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $dword = $exe->read($offset + 5, 4);
        $code = "\x8B\xCB\xE8\xAB\xAB\xAB\xAB\xA1" . $dword . "\x85\xC0\x0F\x84\xAB\xAB\xAB\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(14 => "\x90\xE9"));
        return true;
    }

    static public function ChatAtBug($exe, $free = false)
    {
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

        if( $clientdate2 < 20100309 )
            $ptr = $exe->match("\x00\x01\x01\x01\x01\x01\x01\x00\x00\x00\x01\x00\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x00\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01\x01", '', 0);
        else
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

    static public function SetTCPNODELAY($exe, $free = false)
    {
        if( $exe === true ) {
            return "[Add]_Disable_Nagle_Algorithm";
        }
        /*
        Original code used (compiled with MSVC 16 (VS 2010):
            #include <windows.h>

            typedef int (PASCAL *setsockoptProc) (SOCKET s, int level, int optname, const char FAR * optval, int optlen);
            typedef SOCKET (PASCAL *socketProc) (int af, int type, int protocol);
            typedef FARPROC (WINAPI *GetProcAddressProc)( HMODULE hModule, LPCSTR lpProcName);
            typedef HMODULE (WINAPI *GetModuleHandleProc)(LPCTSTR lpModuleName);
            
            SOCKET PASCAL Mysocket(int af, int type, int protocol) {
                  setsockoptProc setsockoptFunc;
                  int flag = 1;
                  SOCKET r = (*(socketProc*)0xDEADBEEF)(af, type, protocol);
                  if( r != INVALID_SOCKET ) {
                    setsockoptFunc = (*(GetProcAddressProc*)0xDEADBEEE)((*(GetModuleHandleProc*)0xDEADBEED)("WS2_32.DLL"), "setsockopt");
                    if( setsockoptFunc )
                          setsockoptFunc(r, IPPROTO_TCP, TCP_NODELAY, &flag, sizeof(int));
                  }
                  return r;
            }

        ml /c nagle.c
        Changed second 'push 0' (68 00 00 00 00) to 68 01 00 00 00 so that we can patch properly

        68 00 00 00 00    -> 68 <setsocketopt>
        68 01 00 00 00    -> 68 <ws2_32.dll>
        EF BE AD DE    -> socket() IAT
        EE BE AD DE    -> GetProcAddress() IAT
        ED BE AD DE    -> GetModuleHandle() IAT
        */
        $code = "\x73\x65\x74\x73\x6F\x63\x6B\x6F\x70\x74\x00\x00\x57\x53\x32\x5F\x33\x32\x2E\x44\x4C\x4C\x00\x55\x8B\xEC\x83\xEC\x0C\xC7\x45\xF8\x01\x00\x00\x00\x8B\x45\x10\x50\x8B\x4D\x0C\x51\x8B\x55\x08\x52\xA1\xEF\xBE\xAD\xDE\xFF\xD0\x89\x45\xFC\x83\x7D\xFC\xFF\x74\x35\x68\x00\x00\x00\x00\x68\x01\x00\x00\x00\x8B\x0D\xED\xBE\xAD\xDE\xFF\xD1\x50\x8B\x15\xEE\xBE\xAD\xDE\xFF\xD2\x89\x45\xF4\x83\x7D\xF4\x00\x74\x11\x6A\x04\x8D\x45\xF8\x50\x6A\x01\x6A\x06\x8B\x4D\xFC\x51\xFF\x55\xF4\x8B\x45\xFC\x8B\xE5\x5D\xC2\x0C\x00";
        if( !$free )
            $free = $exe->zeroed(strlen($code)+1);
        if( !$free ) {
            echo "Failed in part 1";
            return false;
        }
        $iat_socket = $exe->func("\x17\x00\x00\x80", false);
        if( !$iat_socket ) {
            echo "Failed in part 2";
            return false;
        }
        $iat_getproc = $exe->func('GetProcAddress', true);
        $iat_gethandler = $exe->func('GetModuleHandleA', true);
        if( !$iat_getproc || !$iat_gethandler ) {
            echo "Failed in part 3";
            return false;
        }
        $code = str_replace("\x68\x00\x00\x00\x00", "\x68" . pack('V', $free + $exe->imagebase()), $code);
        $code = str_replace("\x68\x01\x00\x00\x00", "\x68" . pack('V', $free + 12 + $exe->imagebase()), $code);
        $code = str_replace("\xEF\xBE\xAD\xDE", pack('V', $iat_socket), $code);
        $code = str_replace("\xEE\xBE\xAD\xDE", pack('V', $iat_getproc), $code);
        $code = str_replace("\xED\xBE\xAD\xDE", pack('V', $iat_gethandler), $code);

        $ptr = $exe->code("\xFF\x25" . pack('V', $iat_socket), '', 1);
        if( !$ptr ) {
            echo "Failed in part 4";
            return false;
        }
        $exe->insert($code, $free);
        $exe->insert("\xE9" . pack('V', ($free + 23) - ($ptr + 5)), $ptr);
        return true;
    }

    static public function EnableDNSSupport($exe, $free = false)
    {
        global $clientdate2;
        if ($exe === true) {
            return "[Add]_Enable_DNS_Support";
        }
        $codes = array(
        "\x8B\x3D\xAB\xAB\xAB\xAB\x83\xC9\xFF\x33\xC0\x8D\x55\xE4"     .    "\xF2\xAE\xF7\xD1\x2B\xF9\x8B\xC1\x8B\xF7\x8B\xFA\xC1\xE9\x02\xF3\xA5\x8B\xC8\x83\xE1\x03\xF3\xA4",
        "\x8B\x3D\xAB\xAB\xAB\xAB\x83\xC9\xFF\x33\xC0\x8D\x95\x6C\xFF\xFF\xFF\xF2\xAE\xF7\xD1\x2B\xF9\x8B\xC1\x8B\xF7\x8B\xFA\xC1\xE9\x02\xF3\xA5\x8B\xC8\x83\xE1\x03\xF3\xA4",
        "\x8B\x3D\xAB\xAB\xAB\xAB\x83\xC9\xFF\x33\xC0\x8D\x55\xE0"     .    "\xF2\xAE\xF7\xD1\x2B\xF9\x8B\xC1\x8B\xF7\x8B\xFA\xC1\xE9\x02\xF3\xA5\x8B\xC8\x83\xE1\x03\xF3\xA4",
        "\x8B\x3D\xAB\xAB\xAB\xAB\x83\xC9\xFF\x33\xC0\x8D\x55\xD8"     .    "\xF2\xAE\xF7\xD1\x2B\xF9\x8B\xC1\x8B\xF7\x8B\xFA\xC1\xE9\x02\xF3\xA5\x8B\xC8\x83\xE1\x03\xF3\xA4",
        "\x8B\x3D\xAB\xAB\xAB\xAB\x83\xC9\xFF\x33\xC0\x8D\x55\xDC"     .    "\xF2\xAE\xF7\xD1\x2B\xF9\x8B\xC1\x8B\xF7\x8B\xFA\xC1\xE9\x02\xF3\xA5\x8B\xC8\x83\xE1\x03\xF3\xA4",
        );
        $codeoffsets = array(0,0,0,0,0);
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        if (!$free) {
            $free = ($clientdate2 <= 20100407 ? $exe->zeroed(62) : $exe->zeroed(75)); // It requires 61 bytes, added an extra 1 byte for safety
        }
        if ($free === false) {
            echo "Failed in part 2";
            return false;
        }
        $free += 18;
        $code = "\xE8" . pack("I", ($free - ($offset + 5))) . str_repeat("\x90", strlen(substr($codes[$index],5)));
        $exe->replace($offset, array($codeoffsets[$index] => $code));
        $string = $exe->str("192.168.20.170");
        if ($string === false) {
            echo "Failed in part 3";
            return false;
        }
        $code = "\xC7\x05\xAB\xAB\xAB\xAB" . pack("I", $string);
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) == 0) {
            echo "Failed in part 4";
            return false;
        }
        $offset = $offsets[0];
        $offset = $exe->read($offset + 2, 4);
        if ($offset === false) {
            echo "Failed in part 5";
            return false;
        }
        $data = array(
        substr($codes[$index],11,-24) . "\x31\xC9\x85\xC0\x75\x05\x51\x51\x51\xEB\x0F\x8A\x48\x1F\x51\x8A\x48\x1E\x51\x8A\x48\x1D\x51\x8A\x48\x1C\x51\x68",
        substr($codes[$index],11,-24) . "\x33\xC9\x85\xC0\x75\x05\x51\x51\x51\xEB\x0F\x8A\x48\x1F\x51\x8A\x48\x1E\x51\x8A\x48\x1D\x51\x8A\x48\x1C\x51\x68",
        substr($codes[$index],11,-24) . "\x31\xC9\x85\xC0\x75\x05\x51\x51\x51\xEB\x0F\x8A\x48\x1F\x51\x8A\x48\x1E\x51\x8A\x48\x1D\x51\x8A\x48\x1C\x51\x68",
        substr($codes[$index],11,-24) . "\x31\xC9\x85\xC0\x75\x05\x51\x51\x51\xEB\x0F\x8A\x48\x1F\x51\x8A\x48\x1E\x51\x8A\x48\x1D\x51\x8A\x48\x1C\x51\x68",
        substr($codes[$index],11,-24) . "\x31\xC9\x85\xC0\x75\x05\x51\x51\x51\xEB\x0F\x8A\x48\x1F\x51\x8A\x48\x1E\x51\x8A\x48\x1D\x51\x8A\x48\x1C\x51\x68",
        );
        $code = "\xFF\x35" . $offset . "\xFF\x15" . pack("I", $exe->func("\x34\x00\x00\x80", false)) . $data[$index] . pack("I", $exe->str("%d.%d.%d.%d")) . "\x52\xFF\x15" . pack("I", $exe->func("wsprintfA")) . "\x83\xC4\x18\xC3";
        $exe->insert($code, $free);
        return true;
    }
    
    static public function DisableFilenameCheck($exe)
    {
        global $clienttype;
        if ($exe === true) {
            return "[Fix]_Disable_" . $clienttype . "_Filename_Check_(Recommended)";
        }
        $codes = array(
        "\xEB\x05\xE8\xAB\xAB\xAB\xAB\x84\xC0\x75\x1D\x8B\x15\xAB\xAB\xAB\xAB\x6A\x00",
        "\xEB\x05\xE8\xAB\xAB\xAB\xAB\x84\xC0\x75\x1F\x8B\x15\xAB\xAB\xAB\xAB\x53\x53",
        "\xEB\x05\xE8\xAB\xAB\xAB\xAB\x84\xC0\x75\x21\x8B\x15\xAB\xAB\xAB\xAB\x6A\x00\x6A\x00",
        );
        $codeoffsets = array(9,9,9);
        $changes = array("\xEB","\xEB","\xEB");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function KoreaServiceTypeXMLFix($exe)
    {
        if ($exe === true) {
            return "[Fix]_KOREA_ServiceType_XML_Fix_(Recommended)";
        }
        $codes = array(
        "\x83\xF8\x13\x77\x1F\x33\xD2\x8A\x90\xAB\xAB\xAB\xAB",
        "\x83\xF8\x12\x77\x1F\x33\xD2\x8A\x90\xAB\xAB\xAB\xAB",
        "\x83\xF8\xAB\x77\x1F\x33\xD2\x8A\x90\xAB\xAB\xAB\xAB",
        );
        $codeoffsets = array(3,3,3);
        $changes = array("\xEB\x16","\xEB\x16","\xEB\x16");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        $codes = array(
            "\x83\xF8\x13\x77\x1F\x33\xC9\x8A\x88\xAB\xAB\xAB\xAB",
            "\x83\xF8\x12\x77\x1F\x33\xC9\x8A\x88\xAB\xAB\xAB\xAB",
            "\x83\xF8\xAB\x77\x1F\x33\xC9\x8A\x88\xAB\xAB\xAB\xAB",
        );
        $codeoffsets = array(3,3,3);
        $changes = array("\xEB\x16","\xEB\x16","\xEB\x16");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function Disable4LetterUserCharacterLimit($exe)
    {
        if ($exe === true) {
            return "[Fix]_Disable_4_Letter_UserCharacter_Limit";
    }
        $codes = array(
            "\x04\x0F\x8C\x13\x02",
            "\x04\x0F\x8C\x27\x02",
        );
        foreach ($codes as $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false)
                break;
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x00"));
        return true;
    }
    
    static public function Disable4LetterUserIDLimit($exe)
    {
        if ($exe === true) {
            return "[Fix]_Disable_4_Letter_UserID_Limit";
    }
        $codes = array(
            "\x04\x0F\x8C\x23\x01",
            "\x04\x7C\x6F\x8B\x3D",
        );
        foreach ($codes as $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false)
                break;
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x00"));
        return true;
    }
    
    static public function Disable4LetterUserPasswordLimit($exe)
    {
        if ($exe === true) {
            return "[Fix]_Disable_4_Letter_UserPassword_Limit";
    }
        $codes = array(
            "\x04\x0F\x8C\x34\x01",
            "\x04\x7C\x7C\x8B\x4E",
        );
        foreach ($codes as $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false)
                break;
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x00"));
        return true;
    }
    
    static public function ExitBattleModeonlywithSpace($exe)
    {
        if ($exe === true) {
            return "[Fix]_Exit_BattleMode_only_with_Space_(Recommended)";
        }
        $codes = array(
            "\x74\x1E\x83\xF8\x0F",
            "\x74\x19\x83\xF8\x0F",
        );
        $codeoffsets = array(0,0);
        $changes = array("\xEB","\xEB");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function FixBattleModeDoubleLetters($exe)
    {
        if ($exe === true) {
            return "[Fix]_Fix_BattleMode_Double_Letters_in_Chats_(Recommended)";
        }
        $codes = array(
            "\x75\x6A\x39\x1D",
            "\x75\x6A\x39\x3D",
        );
        $codeoffsets = array(0,0);
        $changes = array("\x90\x90","\x90\x90");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function FixCameraAnglesRecomm($exe)
    {
        if ($exe === true) {
            return "[UI](4)_Fix_Camera_Angles_(Recommended)";
        }
        $code = "\xC7\x45\x08\x00\x00\xA0\x41";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(5 => "\x28\x42"));
        $code = "\x74\x07\xC7\x45\xFC\x00\x00\xA0\x41";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(7 => "\x28\x42"));
        $code = "\x00\x00\xC8\xC1\x00\x00\x82\xC2";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) != 1) {
            echo "Failed in part 3";
            return false;
        }
        $offset = $offsets[0];
        $exe->replace($offset, array(2 => "\x80\xBF", 6 => "\xB2"));
        return true;
    }
    
    static public function FixCameraAnglesLess($exe)
    {
        if ($exe === true) {
            return "[UI](4)_Fix_Camera_Angles_(less)";
        }
        $code = "\xC7\x45\x08\x00\x00\xA0\x41";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(5 => "\xEC"));
        $code = "\x74\x07\xC7\x45\xFC\x00\x00\xA0\x41";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(7 => "\xEC"));
        $code = "\x00\x00\xC8\xC1\x00\x00\x82\xC2";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) != 1) {
            echo "Failed in part 3";
            return false;
        }
        $offset = $offsets[0];
        $exe->replace($offset, array(2 => "\x80\xBF", 6 => "\xB2"));
        return true;
    }
    
    static public function FixCameraAnglesFull($exe)
    {
        if ($exe === true) {
            return "[UI](4)_Fix_Camera_Angles_(FULL)";
        }
        $code = "\xC7\x45\x08\x00\x00\xA0\x41";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(5 => "\x82\x42"));
        $code = "\x74\x07\xC7\x45\xFC\x00\x00\xA0\x41";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(7 => "\x82\x42"));
        $code = "\x00\x00\xC8\xC1\x00\x00\x82\xC2";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) != 1) {
            echo "Failed in part 3";
            return false;
        }
        $offset = $offsets[0];
        $exe->replace($offset, array(2 => "\x80\xBF", 6 => "\xB2"));
        return true;
    }
    
    static public function IgnoreChangedAlertMessages($exe)
    {
        if ($exe === true) {
            return "[UI]_Ignore_Changed_Alert_Messages";
        }
        $codes = array("\x83\xC4\x08\x85\xC0\x0F\x85\xE2\x00\x00\x00\xA1\xAB\xAB\xAB\xAB\x8B\x7D\x08");
        $codeoffsets = array(5);
        $changes = array("\x90\xE9");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        $codes = array(
        "\x83\xC4\x08\x85\xC0\x0F\x85\xDD\x00\x00\x00\xA1\xAB\xAB\xAB\xAB\x57",
        "\x83\xC4\x08\x85\xC0\x0F\x85\xDF\x00\x00\x00\xA1\xAB\xAB\xAB\xAB\x57",
        "\x00\x00\x00\x85\xC0\x0F\x85\xDB\x00\x00\x00\xA1\xAB\xAB\xAB\xAB\x57",
        "\x83\xC4\x08\x85\xC0\x0F\x85\xE1\x00\x00\x00\xA1\xAB\xAB\xAB\xAB\x57",
        );
        $codeoffsets = array(5,5,5,5);
        $changes = array("\x90\xE9","\x90\xE9","\x90\xE9","\x90\xE9");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function SkipLicenseScreen($exe)
    {
        if ($exe === true) {
            return "[UI](6)_Skip_License_Screen_(Recommended)";
        }
        
        $ptr = $exe->code("\x83\xF8\xAB\xC7\xAB\x18\x01\x00\x00\x00\x0F\x87", "\xAB", 1);
        if( $ptr === false ) {
            echo "Failed in part 1";
            return false;
        }
        $ptr = $exe->match("\xFF\x24\x85", "\xAB", $ptr);
        if( $ptr === false ) {
            echo "Failed in part 2";
            return false;
        }
        $ptr = $exe->read($ptr + 3, 4, 'V') - $exe->imagebase();
        
        $bin = $exe->read($ptr + 8, 4);
        $exe->replace($ptr, array(0 => $bin, 4 => $bin));
        
        return true;
    }
    
    static public function UseOfficialClothesPalettes($exe)
    {
        if ($exe === true) {
            return "[Data]_Use_Official_Clothes_Palettes_All_Langtypes";
        }
        $code = "\x0F\x85\x1D\x02\x00\x00\xE8";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\x90\x90\x90\x90\x90"));
        return true;
    }

    
    static public function UseEncodedDescriptions($exe)
    {
        if ($exe === true) {
            return "[Data](8)_Use_Encoded_Descriptions";
        }
        $code = "\x56\x85\xC0\x57\x75\x50\x8B\x75\x08\x83\xC9\xFF\x8B\xFE";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(4 => "\x90\x90"));
        return true;
    }
    
    static public function IncreaseZoomOut50Per($exe)
    {
        if ($exe === true) {
            return "[UI](5)_Increase_Zoom_Out_50%";
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

    static public function IncreaseZoomOut75Per($exe)
    {
        if ($exe === true) {
            return "[UI](5)_Increase_Zoom_Out_75%";
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
    
    static public function IncreaseZoomOutMax($exe)
    {
        if ($exe === true) {
            return "[UI](5)_Increase_Zoom_Out_Max";
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
    
    static public function IncreaseQualityScreenshotTo95per($exe)
    {
        if ($exe === true) {
            return "[UI]_Increase_Quality_Screenshot_to_95%";
        }
        $code = "\xC7\x85\xEC\xB0\xFF\xFF\x03\x00\x00\x00\xC7\x85\xF0\xB0\xFF\xFF\x02\x00\x00\x00\x89\x8D\xF8\xB0\xFF\xFF\x89\x85\x04\xB1\xFF\xFF\x89\x9D\x08\xB1\xFF\xFF";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(2 => "\x28\xB1", 6 => "\x5F"));
        return true;
    }
    

    static public function UnlimitedLoadingScreens($exe)
    {
        if ($exe === true) {
            return "[Data]_Unlimited_Loading_Screens";
        }
        $code = "\x89\x75\xEC\x89\x75\xF0\x8B\x45\x08\x47\x3B\xF8";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(6 => "\x89"));
        return true;
    }

    static public function EnableProxySupport($exe, $free = false)
    {
        if ($exe === true) {
            return "[Add]_Enable_Proxy_Support";
        }
        $code = "\x6A\x10\x53\x51\x66\x89\x46\x0A\xE8\xAB\xAB\xAB\xAB\x83\xF8\xFF\x75\xAB\xE8\xAB\xAB\xAB\xAB\x3D\x33\x27\x00\x00";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $code = "\xFF\x25" . pack("I", $exe->func("\x04\x00\x00\x80", false));
        $connect = $exe->code($code, "\xAB");
        if ($connect === false) {
            echo "Failed in part 2";
            return false;
        }
        if (!$free) {
            $free = $exe->zeroed(26 + 1); // Uses 26 bytes, 1 extra for safety
        }
        if ($free === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array(9 => pack("I", ($free - ($offset + 13)))));

        // What's the first address there? (\xFC\x1F\x79\x00 at 2007-10-09a)
//        $code = "\x60\xBF" . "\xFC\x1F\x79\x00" . "\x8B\x07\x85\xC0\x75\x05\x8B\x43\x04\x89\x07\x89\x43\x04\x61\xE9" . pack("I", ($connect - ($free + 26)));
        // What's the first address there? (\xFC\x2F\x7C\x00 at 2008-05-28a)
//        $code = "\x60\xBF" . "\xFC\x2F\x7C\x00" . "\x8B\x07\x85\xC0\x75\x05\x8B\x43\x04\x89\x07\x89\x43\x04\x61\xE9" . pack("I", ($connect - ($free + 26)));
        // What's the first address there? (\xFC\x3F\x7C\x00 at 2008-07-15a)
//        $code = "\x60\xBF" . "\xFC\x3F\x7C\x00" . "\x8B\x07\x85\xC0\x75\x05\x8B\x43\x04\x89\x07\x89\x43\x04\x61\xE9" . pack("I", ($connect - ($free + 26)));
        
        
        $rsrc = $exe->getSection(".rsrc");
        
        if($rsrc === false) {
            echo "Failed in part 4";
            return false;
        }
        
        $offset = $rsrc->vOffset+$exe->imagebase();
        $offset -= 4; // It uses 4 bytes it seems...
        $code = "\x60\xBF" . pack("I", $offset) . "\x8B\x07\x85\xC0\x75\x05\x8B\x43\x04\x89\x07\x89\x43\x04\x61\xE9" . pack("I", ($connect - ($free + 26)));
        // Wild Guess End
        
        $exe->insert($code, $free);

        return true;
    }
    
    static public function OFF_by_default_Skip($exe)
    {
        if ($exe === true) {
            return "[Auto]_OFF_by_default_/Skip";
        }
        $codes = array(
            "\x01\x00\x00\x00\x01\x00\x00\x00\x01\x00\x00\x00\x53",
            "\x01\x00\x00\x00\x01\x00\x00\x00\x01\x00\x00\x00\x4E"
        );
        $codeoffsets = array(0,0);
        $changes = array("\x00","\x00");
        foreach ($codes as $index => $code) {
            $offset = $exe->match($code, "\xAB", 0);
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function EnforceIROFont($exe)
    {
        if ($exe === true) {
            return "[UI](10)_Enforce_iRO_Font";
        }
        $code = "\x74\x05\x83\xF8\x0C\x75";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(5 => "\xEB"));
        $code = "\x8B\xC8\x2B\xCA\xC1\xF9\x02\x3B\xF9\x72\x02\x8B\xCF\x85\xD2\x75\x04\x33\xC0\xEB\x05\x2B\xC2\xC1\xF8\x02\x03\xC1\x85\xC0\x89\x45\xF8";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(15 => "\xEB"));
        return true;
    }
    

    static public function DisableLv99Aura($exe)
    {
        if ($exe === true) {
            return "[UI](2)_Disable_lv99_Aura";
        }
        $codes = array(
        "\x75\x0D\x8B\x06\x53\x53\x53\x6A\x7D\x53\x8B",
        "\x75\x0D\x8B\x06\x53\x53\x53\x6A\x7E\x53\x8B",
        "\x75\x11\x3B\xF3\x74\x0D\x8B",
        "\x63\x75\x11\x8B\x16\x6A\x00\x6A\x00",
        );
        $codeoffsets = array(0,0,1);
        $changes = array("\xEB","\xEB","\xEB");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function EnableAuraOverLv99($exe)
    {
        if ($exe === true) {
            return "[UI](2)_Enable_Aura_over_lv99";
        }
        $codes = array(
        "\x75\x0D\x8B\x06\x53\x53\x53\x6A\x7D\x53\x8B",
        "\x75\x0D\x8B\x06\x53\x53\x53\x6A\x7E\x53\x8B",
        "\x75\x11\x3B\xF3\x74\x0D\x8B",
        "\x63\x75\x11\x8B\x16\x6A\x00\x6A\x00",
        );
        $codeoffsets = array(0,0,1);
        $changes = array("\x72","\x72","\x72");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function EnableStatsOver99($exe)
    {
        if ($exe === true) {
            return "[UI]_Enable_Stats_over_99";
        }
        $code = "\x3B\xCA\xB8\xAC\xFE\xFF\xFF\x7F\x09\x83\x7C\x3D\xD0\x63\x7D\x02\x33\xC0\x85\xC9";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(10 => "\x7D"));
        return true;
    }
    
    static public function EnableWAndWhoCommands($exe)
    {
        if ($exe === true) {
            return "[UI]_Enable_/w_and_/who_commands";
        }
        $codes = array(
        "\x83\xF8\x03\x0F\x84\xAB\x5D\x00\x00\x83\xF8\x08\x0F\x84\xAB\x5D\x00\x00\x83\xF8\x09",
        "\x83\xF8\x03\x0F\x84\xAB\x6C\x00\x00\x83\xF8\x08\x0F\x84\xAB\x6C\x00\x00\x83\xF8\x09",
        "\x83\xF8\x03\x0F\x84\xAB\x6E\x00\x00\x83\xF8\x08\x0F\x84\xAB\x6E\x00\x00\x83\xF8\x09",
        "\x83\xF8\x03\x0F\x84\xAB\x6D\x00\x00\x83\xF8\x08\x0F\x84\xAB\x6D\x00\x00\x83\xF8\x09",
        "\x83\xF8\x03\x0F\x84\xAB\x5E\x00\x00\x83\xF8\x08\x0F\x84\xAB\x5E\x00\x00\x83\xF8\x09",
        "\x83\xF8\x03\x0F\x84\xAB\x75\x00\x00\x83\xF8\x08\x0F\x84\xAB\x75\x00\x00\x83\xF8\x09",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(2 => "\xFF", 11 => "\xFF", 20 => "\xFF"));
        $code = "\x75\x38\x8B\x45\x08\x56\x57\x66";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(0 => "\xEB"));
        return true;
    }




    static public function PlayOpenningDotBik($exe)
    {
        if ($exe === true) {
            return "[UI]_Play_Openning.bik";
        }
        $code = "\x83\xF9\x01\x89\x45\xF4\x75\x0A\x83\x7D\x08\x01\x0F\x84\xAB\x01\x00\x00\x8B\x0D\xAB\xAB\xAB\xAB\x6A\x00\x51\xFF\x15";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(12 => "\x90\x90\x90\x90\x90\x90"));
        return true;
    }

    static public function EnforceOfficialLoginBackground($exe)
    {
        global $clientdate2;

        if ($exe === true) {
            return "[UI](3)_Enforce_Official_Login_Background_(Recommended)";
        }

        $code = "\xFF\xA1\xAB\xAB\xAB\xAB\x85\xC0\x74\xAB\x83\xF8\x04";
        if ($clientdate2 < 20090601)
        {
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part 1";
                return false;
            }
        }
        else
        {
            $offsets = $exe->matches($code, "\xAB", 0);
            if (count($offsets) != 2) {
                echo "Failed in part 2";
                return false;
            }
            $offset = $offsets[0];
        }
        $exe->replace($offset, array((8) => "\xEB"));
        return true;
    }

    static public function BlackLoginBackground($exe)
    {
        global $clientdate2;

        if ($exe === true) {
            return "[UI](3)_Black_Login_Background";
        }

        $code = "\x74\x0A\x83\xF8\xAB\x74\x05\x83\xF8\xAB\x75\x11\xB9\xAB\xAB\xAB\xAB\xE8\xAB\xAB\xAB\xAB\x8B\x46\x04";
        if ($clientdate2 < 20090601)
        {
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part 1";
                return false;
            }
        }
        else
        {
            $offsets = $exe->matches($code, "\xAB", 0);
            if (count($offsets) != 2) {
                echo "Failed in part 2";
                return false;
            }
            $offset = $offsets[0];
        }
        $exe->replace($offset, array((17) => "\x90\x90\x90\x90\x90"));

        $code = "\x8B\x46\x04\x85\xC0\x75\x0A\xB9\xAB\xAB\xAB\xAB\xE8\xAB\xAB\xAB\xAB";
        if ($clientdate2 < 20090601)
        {
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part 3";
                return false;
            }
        }
        else
        {
            $offsets = $exe->matches($code, "\xAB", 0);
            if (count($offsets) != 2) {
                echo "Failed in part 4";
                return false;
            }
            $offset = $offsets[0];
        }
        $exe->replace($offset, array(5 => "\x90\x90"));

        return true;
    }

    static public function ShowExpBarsUpTo255($exe)
    {
        global $clientdate2;
        if ($exe === true) {
            return "[UI]_Show_EXP/Job_Bars_up_to_255";
        }
        if($clientdate2 <= 20080910){
            $code = "\xBF\x63\x00\x00\x00\x3B\xC7\x75\x0E\x8B\x11\x6A\x4E";
            $offset = $exe->code($code, "\xAB");
            if ($offset === false){
                echo "Failed in part 1";
                return false;
            }
            $exe->replace($offset, array(1 => "\xFF"));
        }
        if($clientdate2 < 20090520){
            $code = "\x83\x3D\xAB\xAB\xAB\xAB\x46";
            $offset = $exe->code($code, "\xAB");
            if ($offset === false){
                echo "Failed in part 2";
                return false;
            }
            $exe->replace($offset, array(6 => "\x7F", 13 => "\xEB"));
        }else{
            $code = "\x00\x00\x00\x7C\x44\x8B";      // Code for Job Bars Lvl 50
            $code2 = "\x00\x00\x00\x83\xF8\x46\x7C"; // Code for Job Bars Lvl 70
            $offset = $exe->code($code, "\xAB");
            $offset2 = $exe->code($code2, "\xAB");
            if ($offset === false){
                echo "Failed in part 3";
                return false;
            }
            if ($offset2 === false){
                echo "Failed in part 4";
                return false;
            }
            $exe->replace($offset, array(3 => "\xEB"));
            $exe->replace($offset2, array(6 => "\xEB"));
        }
        return true;
    }

    static public function GRFAdataBdataSupport($exe, $free = false)
    {
        if ($exe === true) {
            return "[Data](7)_GRF_Adata/Bdata_Support";
        }
        global $clientdate2, $clienttype; 
        if("Sakexe" == $clienttype)
            $grf = pack("I", $exe->str("sdata.grf"));
        if("Ragexe" == $clienttype)
            $grf = pack("I", $exe->str("data.grf"));
        if("RagexeRE" == $clienttype)
            $grf = pack("I", $exe->str("rdata.grf"));

        if(20080702 >= $clientdate2) {
            $code = "\x68" . pack("I", $exe->str("\x00data.grf") + 1);
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part 1";
                return false;
            }
            $exe->replace($offset, array(-2 => "\x90\x90"));
            if (!$free) {
                $free = $exe->zeroed(64 + 1*16);
            }
            if ($free === false) {
                echo "Failed in part 2";
                return false;
            }        
            $code = "\x88\x0D\xAB\xAB\xAB\xAB\x68" . $grf;
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part 3";
                return false;
            }
            $exe->replace($offset, array(6 => "\xE9" . pack("I", ($free - ($offset + 11)))));
            $code = "\x74\xAB\x68" . $grf;
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part 4";
                return false;
            }
            $mov = $exe->read($offset + 7, 5);
            $exe->replace($offset, array(0 => "\xEB"));
            $call = $exe->read($offset + 13, 4, "i");
            $code = "\x68" . $grf .
                    $mov .
                    "\xE8" . pack("i", ($offset + 13 + $call - ($free + 11))) .
                    "\x68" . pack("I", ($exe->imagebase() + $free + 41)) .
                    $mov .
                    "\xE8" . pack("i", ($offset + 12 + $call - ($free + 25))) .
                    "\x68" . pack("I", ($exe->imagebase() + $free + 55)) .
                    "\xE9" . pack("i", ($offset - ($free + 72) + 6)) .
                    "\x00" . "adata.grf" . "\x00\x00\x00\x00\x00" . "bdata.grf";
            $exe->insert($code, $free);
            echo "Old Adata Bdata ";
            return true;
        }

        if("RagexeRE" == $clienttype) {
            $code = "\xF2\xAE\xF7\xD1\x2B\xF9\x68" . $grf . "\x8B\xC1\x8B\xF7\x8B\xFA\xC1\xE9\x02\xF3\xA5\x8B\xC8\x83\xE1\x03\xF3\xA4\xB9\xAB\xAB\xAB\xAB\xE8\xAB\xAB\xAB\xAB";
            //echo "\n\n".bin2hex($code)."\n\n";
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part 5";
                return false;
            }
            if (!$free)
                $free = $exe->zeroed(82 + 1); // 64 bytes plus 1 byte for safety
            if ($free === false) {
                echo "Failed in part 6";
                return false;
            }
            //echo "\n\n".dechex($offset)."\n\n";
            $stuff = $exe->read($offset + 11, 18);
            $mov = $exe->read($offset + 29, 5);
            $call = $exe->read($offset + 35, 4, "i");
            $exe->replace($offset, array(6 => "\xE9" . pack("I", ($free - ($offset + 11))) . "\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90"));
            $code = "\x68" . $grf .
                    $stuff .
                    $mov .
                    "\xE8" . pack("i", ($offset + 35 + $call - ($free + 29))) .
                    "\x68" . pack("I", ($exe->imagebase() + $free + 59)) .
                    $mov .
                    "\xE8" . pack("i", ($offset + 34 + $call - ($free + 43))) .
                    "\x68" . pack("I", ($exe->imagebase() + $free + 73)) .
                    "\xE9" . pack("i", ($offset - ($free + 90) + 6 + 37)) .
                    "\x00" . "adata.grf" . "\x00\x00\x00\x00\x00" . "bdata.grf";
            $exe->insert($code, $free);
            echo "New Adata Bdata ";
            return true;
        }

        if(20080708 <= $clientdate2) {
            $code = "\x0F\x95\xC1";
            // Uncomment if it breaks:
            //$code = "";
            $len = strlen($code);
            $code .= "\x88\xAB\xAB\xAB\xAB\xAB\x68" . $grf . "\xB9\xAB\xAB\xAB\xAB\xE8\xAB\xAB\xAB\xAB";
            //echo "\n\n".bin2hex($code)."\n\n";
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part 7";
                return false;
            }
            $offset += $len;
            if (!$free)
                $free = $exe->zeroed(64 + 1); // 64 bytes plus 1 byte for safety
            if ($free === false) {
                echo "Failed in part 8";
                return false;
            }
            $mov = $exe->read($offset + 11, 5);
            $call = $exe->read($offset + 17, 4, "i");
            $exe->replace($offset, array(6 => "\xE9" . pack("I", ($free - ($offset + 11)))));
            $code = "\x68" . $grf .
                    $mov .
                    "\xE8" . pack("i", ($offset + 17 + $call - ($free + 11))) .
                    "\x68" . pack("I", ($exe->imagebase() + $free + 41)) .
                    $mov .
                    "\xE8" . pack("i", ($offset + 16 + $call - ($free + 25))) .
                    "\x68" . pack("I", ($exe->imagebase() + $free + 55)) .
                    "\xE9" . pack("i", ($offset - ($free + 72) + 6 + 37)) .
                    "\x00" . "adata.grf" . "\x00\x00\x00\x00\x00" . "bdata.grf";
            $exe->insert($code, $free);
            echo "New Adata Bdata ";
            return true;
        }
    }

    static public function Autos($exe, $offset = false)
    {
        if ($exe === true) {
            return "[Auto]_";
        }
        $codes = array(
        "\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x8B\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x8B\xAB\xAB\x00\x00\x89\x8B\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\xA3\xAB\xAB\xAB\x00\x88\x8B\xAB\xAB\x00\x00\x88\x8B\xAB\xAB\x00\x00",
        "\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x8B\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x8B\xAB\xAB\x00\x00\x89\x8B\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\x89\x83\xAB\xAB\x00\x00\xA3\xAB\xAB\xAB\x00\x88\x8B\xAB\xAB\x00\x00\x88\x8B\xAB\xAB\x00\x00",
        );
        $offset1 = array(3,9,15,33,45,51,57,63,69,75,93,104,110);
        $offset2 = array(3,57,63,9,21,27,33,39,45,51,87,104,110);
        $code1 = array("\x8B","\x8B","\x8B","\x83","\x83","\x83","\x8B","\x8B","\x8B","\x8B","\x8B","\x83","\x83");
        $code2 = array("\x8B","\x8B","\x8B","\x83","\x83","\x83","\x8B","\x8B","\x8B","\x8B","\x8B","\x83","\x83");
        $codeoffsets = array($offset1,$offset2);
        $changes = array($code1,$code2);
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $codeoffsets = $codeoffsets[$index];
        $changes = $changes[$index];
        for ($i = 0; $i < count($codeoffsets); $i++) {
            $exe->replace($offset, array($codeoffsets[$i] => $changes[$i]));
        }
        return true;
    }

    static public function UseCustomFont($exe, $strOff = false)
    {
        if ($exe === true) {
            return "[UI](9)_Use_Custom_Font";
        }
        if (!$strOff) {
            $strOff = 0x350;
        }
        if ($strOff === false) {
            echo "Failed in part 1";
            return false;
        }
        $string = "Comic Sans MS";
        $exe->insert($string, $strOff);
        $code = pack("I", $exe->str("Gulim"));
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) != 1) {
            echo "Failed in part 2";
            return false;
        }
        $offset = $offsets[0];
        $replace = str_repeat(pack("I", ($exe->imagebase() + $strOff)), 22);
        $exe->replace($offset, array(0 => $replace));
        return true;
    }

    static public function UseCustomAuraSprites($exe, $free = false)
    {
        if ($exe === true) {
            return "[Data]_Use_Custom_Aura_Sprites";
        }
        if (!$free) {
            $free = 0x500;
        }
        if ($free === false) {
            echo "Failed in part 1";
            return false;
        }
        $code = "\x68" . pack("I", $exe->str("effect\\ring_blue.tga")) . "\x8B\xCE\xE8\xAB\xAB\xAB\xAB\xE9\xAB\xAB\xAB\xAB\x53\x68" . pack("I", $exe->str("effect\\pikapika2.bmp"));
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(1 => pack("I", ($exe->imagebase() + $free)), 19 => pack("I", ($exe->imagebase() + $free + 21))));
        $code = "effect\aurafloat.tga\x00effect\auraring.bmp\x00\x90";
        $exe->insert($code, $free);
        return true;
    }

    static public function UseNormalGuildBrackets($exe)
    {
        if ($exe === true) {
            return "[UI]_Use_Normal_Guild_Brackets";
        }
        $offset = $exe->str("%s\xA1\xBA%s\xA1\xBB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $offset -= $exe->imagebase();
        $exe->replace($offset, array(0 => "%s [%s]\x00"));
        return true;
    }

    static public function EnableShowname($exe)
    {
        if ($exe === true) {
            return "[UI](10)_Enable_/showname";
        }
        $code = "\x0A\x74\x05\x83\xF8\x0C\x75";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(6 => "\x90\x90"));

        $code = "\x75\x04\x33\xC0\xEB\x05\x2B\xC2\xC1\xF8\x02\x03\xC1\x85\xC0\x89\x45\xF8\x7D\x02\x33\xC0\xC1\xE0\x02";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\x90"));
        return true;
    }
    
    static public function InvalidEmailFix($exe)
    {
        if ($exe === true) {
            return "[Fix]_Invalid_Email_Fix_(Recommended)";
        }
        $codes = array("\x75\x07\x68\x2E\x01\x00\x00");
        $codeoffsets = array(0);
        $changes = array("\xEB");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        $codes = array(
        "\x75\x0E\x8A\x86\xA3\x00\x00\x00\x84\xC0\x75\x04\xC6\x45\xCE",
        "\x75\xAB\x8A\x86\xA3\x00\x00\x00\x84\xC0\x75\xAB\xC6\xAB\xAB\xAB\xAB\xAB\xAB",
        "\x75\x11\x8A\x86\xA3\x00\x00\x00\x84\xC0\x75\x07\xC6\x85\x6E\xFF\xFF\xFF\x00",
        "\x75\x11\x8A\x83\xA3\x00\x00\x00\x84\xC0\x75\x07\xC6\x85\x66\xFF\xFF\xFF\x00",
        "\x75\x11\x8A\x83\xA3\x00\x00\x00\x84\xC0\x75\x07\xC6\x85\x6A\xFF\xFF\xFF\x00",
        );
        $codeoffsets = array(0,0,0,0,0);
        $changes = array("\xEB","\xEB","\xEB","\xEB","\xEB");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        $codes = array("\x75\x07\x68\x2F\x01\x00\x00\xEB\x05\x68\x2D\x01\x00\x00");
        $codeoffsets = array(0);
        $changes = array("\xEB");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }
    
    static public function ShowAllButtonsInLoginWindows($exe)
    {
        if ($exe === true) {
            return "[UI]_Show_All_Buttons_In_Login_Windows";
        }
        $code = "\x74\x1C\x3B\xC7\x74\x18\x83\xF8\x0A\x74\x13\x83\xF8\x0B";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\xEB"));
        return true;
    }
    
    static public function ShowLicenseScreenAlways($exe)
    {
        if ($exe === true) {
            return "[UI](6)_Show_License_Screen_Always";
        }
        $codes = array(
        "\x74\xAB\x83\xF8\x08\x74\xAB\x83\xF8\x09\x74\xAB\x83\xF8\x06",
        "\x74\x59\x83\xF8\x01\x74\x54\x83\xF8\x08",
        );
        foreach ($codes as $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\xEB"));
        return true;
    }
    
    static public function SkipResurrectionButtons($exe)
    {
        if ($exe === true) {
            return "[UI]_Skip_Resurrection_Buttons";
        }
        // Simply change the 'Token of Siegfried' ID to 0xFFFF - way easier.
        $ptr = $exe->code("\x68\xC5\x1D\x00\x00", "\xAB", 1);
        if( !$ptr ) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($ptr, array(1 => "\xFF\xFF"));
        return true;
    }
    
    static public function SkipServiceSelect($exe)
    {
        if ($exe === true) {
            return "[UI]_Skip_Service_Select";
        }
        $code = "\x74\x07\xC6\x05\xAB\xAB\xAB\xAB\x01\x68" . pack("I", $exe->str("passwordencrypt"));
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\x90"));
        return true;
    }

    static public function GmChatColor($exe)
    {
        if ($exe === true) {
            return "[Color](A)_GM_Chat_Color";
        }
        $code = "\xC4\x1C\x6A\x00\x6A\x00\x8D\x8D\x00\xFF\xFF\xFF\x68\xFF\xFF\x00\x00\x51\x6A\x01";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(13 => "\xAA\xAA\xAA"));
        return true;
    }

    static public function OtherChatColor($exe)
    {
        if ($exe === true) {
            return "[Color](B)_Other_Chat_Color";
        }
        $codes = array(
        "\x74\x1C\x6A\x00\x6A\x01\x68\xFF\xFF\xFF\x00\x8D\x85\xDC\xFE\xFF\xFF\x50\x6A\x01",
        "\x74\x1C\x6A\x00\x6A\x01\x68\xFF\xFF\xFF\x00\x8D\x95\xFC\xFE\xFF\xFF\x52\x6A\x01",
        );
        $codeoffsets = array(7,7);
        $changes = array("\xAA\xAA\xAA","\xAA\xAA\xAA");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function MainChatColor($exe)
    {
        if ($exe === true) {
            return "[Color](C)_Main_Chat_Color";
        }
        $codes = array(
            "\xFF\xFF\x00\x00\x52\xEB\x4C",
            "\xFF\xFF\x00\x00\x51\xEB\x4C",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\xAA\xAA\xAA"));
        return true;
    }
    
    static public function YourChatColor($exe)
    {
        if ($exe === true) {
            return "[Color](D)_Your_Chat_Color";
        }
        $codes = array(
            "\xF4\xFF\xEB\x37\x6A\x01\x8D\x95\x00\xFF\xFF\xFF\x68\x00\xFF\x00\x00\x52\x6A\x01",
            "\xF4\xFF\xEB\x37\x6A\x01\x8D\x95\xE0\xFE\xFF\xFF\x68\x00\xFF\x00\x00\x52\x6A\x01",
            "\xF5\xFF\xEB\x37\x6A\x01\x8D\x95\xE0\xFE\xFF\xFF\x68\x00\xFF\x00\x00\x52\x6A\x01",
            "\xF5\xFF\xEB\x37\x6A\x01\x8D\x95\x00\xFF\xFF\xFF\x68\x00\xFF\x00\x00\x52\x6A\x01",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(13 => "\xAA\xAA\xAA"));
        return true;
    }

    static public function YourPartyChatColor($exe)
    {
        if ($exe === true) {
            return "[Color](E)_Your_Party_Chat_Color";
        }
        $code = "\x6A\x03\x8D\x95\xFC\xFE\xFF\xFF\x68\xFF\xC8\x00\x00\x52\x6A\x01\xB9";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(9 => "\xAA\xAA\xAA"));
        return true;
    }

    static public function OtherPartyChatColor($exe)
    {
        if ($exe === true) {
            return "[Color](F)_Other_Party_Chat_Color";
        }
        $codes = array(
        "\x8D\xFC\xFE\xFF\xFF\x68\xFF\xC8\xC8\x00\x51\x6A\x01\xB9\xAB\xAB\x77\x00\xE8",
        "\x8D\xFC\xFE\xFF\xFF\x68\xFF\xC8\xC8\x00\x51\x6A\x01\xB9\xAB\xAB\xAB\x00\xE8",
        );
        $codeoffsets = array(6,6);
        $changes = array("\xAA\xAA\xAA","\xAA\xAA\xAA");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function GuildChatColor($exe)
    {
        if ($exe === true) {
            return "[Color](G)_Guild_Chat_Color";
        }
        $codes = array(
        "\xFF\xFF\x68\xB4\xFF\xB4\x00\x50\x6A\x01\xB9\xAB\xAB\x77\x00\xE8",
        "\x6A\x00\x6A\x04\x8D\x85\xAB\xAB\xFF\xFF\x68\xB4\xFF\xB4\x00\x50\x6A\x01\xB9\xAB\xAB\xAB\x00\xE8\xAB\xAB\xAB\xFF\xA1",
        );
        $codeoffsets = array(3,11);
        $changes = array("\xAA\xAA\xAA","\xAA\xAA\xAA");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function XRayAllowCreateCustomPalettes($exe)
    {
        if ($exe === true) {
            return "[XRay]_Allow_Create_Custom_Palettes";
        }
        $codes = array(
        "\x08\x7E\x06\x89\xBB\xAB\x01\x00\x00\x8B\x83\xAB\x01\x00\x00\x3B",
        "\x08\x7E\x06\x89\xBB\x8C\x01\x00\x00\x8B\x83\x8C\x01\x00\x00\x3B",
        "\x08\x7E\x06\x89\xBB\x88\x01\x00\x00\x8B\x83\x88\x01\x00\x00\x3B",
        );
        $codeoffsets = array(0,0,0);
        $changes = array("\x7F","\x7F","\x7F");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function XRayAllowCreateCustomHairstyle($exe)
    {
        if ($exe === true) {
            return "[XRay]_Allow_Create_Custom_Hairstyle";
        }
        $codes = array(
        "\x00\x00\x75\x09\x66\xC7\x83\xAB\x00\x00\x00\x17\x00\x0F\xBF\x8B",
        "\x00\x00\x75\x09\x66\xC7\x83\x9E\x00\x00\x00\x17\x00\x0F\xBF\x8B",
        "\x00\x00\x75\x09\x66\xC7\x83\x9A\x00\x00\x00\x17\x00\x0F\xBF\x8B",
        );
        $codeoffsets = array(11,11,11);
        $changes = array("\x2B","\x2B","\x2B");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));

        $codes = array(
        "\x18\x00\x75\x09\x66\xC7\x83\xAB\x00\x00\x00\x01\x00\x0F\xBF\x83",
        "\x18\x00\x75\x09\x66\xC7\x83\x9E\x00\x00\x00\x01\x00\x0F\xBF\x83",
        "\x18\x00\x75\x09\x66\xC7\x83\x9A\x00\x00\x00\x01\x00\x0F\xBF\x83",
        );
        $codeoffsets = array(0,0,0);
        $changes = array("\x2C","\x2C","\x2C");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function XRayExpandHomunculusandMercenaryIDs($exe)
    {
        if ($exe === true) {
            return "[XRay]_Expand_Homunculus_and_Mercenary_IDs";
        }
        $code = "\x17\x00\x00\x7E\xAB\x81\xFE\xA0\x17\x00\x00\x7D\xAB\xA1";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(7 => "\x11\x27"));

        $code = "\x55\x8B\xEC\x8B\x45\x08\x3D\x70\x17\x00\x00\x7E\x10\x3D\xA0\x17";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(14 => "\x11\x27"));

        $code = "\x55\x8B\xEC\x8B\x45\x08\x3D\x81\x17\x00\x00\x7E\x10\x3D\xA0\x17";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array(14 => "\x11\x27"));
        return true;
    }

    static public function UseRagnarokIcon($exe)
    {
        if ($exe === true) {
            return "[UI]_Use_Ragnarok_Icon";
        }
        $codes = array(
            "\x10\x01\x00\x80\x77",
            "\x30\x01\x00\x80\x77",
        );
        $codeoffsets = array(0,0);
        $changes = array(
            "\x28",
            "\x48",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->match($code, "\xAB", 0);
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
        return true;
    }

    static public function FreeFormStatsPolygon($exe)
    {
        if ($exe === true) {
            return "[UI]_Free-Form_Stats_Polygon";
        }
        $code = "\x00\x00\x00\xB8\x05";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(4 => "\x01"));

        $codes = array(
        "\xCD\x00\x00\x00\x3C\x02\x0F\x82\x69\x0A\x00\x00\x8A\x8B\xCA\x00\x00\x00\xFE\xC8\xFE\xC1",
        "\xC9\x00\x00\x00\x3C\x02\x0F\x82\x69\x0A\x00\x00\x8A\x8B\xC6\x00\x00\x00\xFE\xC8\xFE\xC1",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(0 => "\xC6", 5 => "\x09\x72\x02\x32\xC0\xFE\xC0\x88\x83", 18 => "\x8B\xCB\xEB\x32"));

        $codes = array(
        "\xCF\x00\x00\x00\x3C\x02\x0F\x82\x0F\x0A\x00\x00\x8A\x8B\xCB\x00\x00\x00\xFE\xC8\xFE\xC1",
        "\xCB\x00\x00\x00\x3C\x02\x0F\x82\x0F\x0A\x00\x00\x8A\x8B\xC7\x00\x00\x00\xFE\xC8\xFE\xC1",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array(0 => "\xC7", 5 => "\x09\x72\x02\x32\xC0\xFE\xC0\x88\x83", 18 => "\x8B\xCB\xEB\xD8"));

        $codes = array(
        "\xCE\x00\x00\x00\x3C\x02\x0F\x82\xD0\x09\x00\x00\x8A\x8B\xCC\x00\x00\x00\xFE\xC8",
        "\xCA\x00\x00\x00\x3C\x02\x0F\x82\xD0\x09\x00\x00\x8A\x8B\xC8\x00\x00\x00\xFE\xC8",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 4";
            return false;
        }
        $exe->replace($offset, array(0 => "\xC8", 5 => "\x09\x72\x02\x32\xC0\xFE\xC0\x88\x83", 18 => "\xEB\x6D"));

        $codes = array(
        "\xCA\x00\x00\x00\x3C\x02\x0F\x82\x91\x09\x00\x00\x8A\x8B\xCD\x00\x00\x00\xFE\xC8",
        "\xC6\x00\x00\x00\x3C\x02\x0F\x82\x91\x09\x00\x00\x8A\x8B\xC9\x00\x00\x00\xFE\xC8",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 5";
            return false;
        }
        $exe->replace($offset, array(0 => "\xC9", 5 => "\x09\x72\x02\x32\xC0\xFE\xC0\x88\x83", 18 => "\xEB\x2E"));

        $codes = array(
        "\xCC\x00\x00\x00\x3C\x02\x0F\x82\x3B\x09\x00\x00\x8A\x8B\xCE\x00\x00\x00\xFE\xC8",
        "\xC8\x00\x00\x00\x3C\x02\x0F\x82\x3B\x09\x00\x00\x8A\x8B\xCA\x00\x00\x00\xFE\xC8",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 6";
            return false;
        }
        $exe->replace($offset, array(0 => "\xCA", 5 => "\x09\x72\x02\x32\xC0\xFE\xC0\x88\x83", 18 => "\xEB\x2E"));

        $codes = array(
        "\xCB\x00\x00\x00\x3C\x02\x0F\x82\xE5\x08\x00\x00\x8A\x8B\xCF\x00\x00\x00\xFE\xC8",
        "\xC7\x00\x00\x00\x3C\x02\x0F\x82\xE5\x08\x00\x00\x8A\x8B\xCB\x00\x00\x00\xFE\xC8",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 7";
            return false;
        }
        $exe->replace($offset, array(0 => "\xCB", 5 => "\x09\x72\x02\x32\xC0\xFE\xC0\x88\x83", 18 => "\xEB\x2E"));
        return true;
    }

    static public function SaveMainChatWithScrollLock($exe, $free = false)
    {
        if ($exe === true) {
            return "[Fix]_Save_Main_Chat_with_Scroll_Lock"; // Why is it a [Fix] ?
        }
        if (!$free) {
            $free = $exe->zeroed(43 + 1); // 43 + 1 byte for safety
            // Why the heck did -o- put the extra code in the middle of a function on the client?
            // That sounds too weird... so I'm putting it in a free space.
            // This was tested with a 2008-07-22a client and worked fine.
        }
        if ($free === false) {
            echo "Failed in part 1";
            return false;
        }

        $code = "\x6A\x00\x6A\x00\x6A\x00\x8B\x11\x6A\x4B";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $mov2 = $exe->read(($offset - 6), 6);
        $exe->replace($offset, array(-6 => "\xE8" . pack("I", $free - ($offset - 6 + 5)) . "\x90" . str_repeat("\x90", 13)));

        $code = "\x83\xC4\x04\x50\x6A\x01\xB9\xAB\xAB\xAB\xAB\xE8\xAB\xAB\xAB\xAB";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) == 0) {
            echo "Failed in part 3";
            return false;
        }
        $mov1 = $exe->read($offsets[0] + 6, 5);
        $call = $exe->read($offsets[0] + 12, 4, "i");
        $call += 5 + $offsets[0];
        $code = "\x81\x7D\x08\x91\x00\x00\x00\x6A\x00\x6A\x00\x6A\x00" . "\x75\x0E" .
            $mov1 . "\x51\x6A\x06\xE8" . pack("i", $call - 5 - ($free + 19)) . "\xC3" .
            $mov2 . "\x8B\x11\x6A\x4B\xFF\x52\x14\xC3";
        $exe->insert($code, $free);

        return true;
    }
    
    static public function ShowDebug($exe, $free = false)
    {
        if ($exe === true) {
            return "[Fix]_Show_Debug";
        }

        $code = "\x8B\x41\x0C\x85\xC0\x74\x06\x8B\x08\x50\xFF\x51\x10\xC3\x90\x90\x55\x8B\xEC\x8B\x55\x0C\x8B\x45\x08\x56\x57\x8B\x79\x08\x8B\x49\x0C\x8B\x30\x6A\x00\x2B\xFA\x52\x57\x51\x6A\x04\x50\xFF\x56\x7C\x5F\x5E\x5D\xC2\x08\x00\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\xC3\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }

        $alloca_probe = $exe->code("\x51\x3D\x00\x10", "\xAB");
        if ($alloca_probe === false) {
            echo "Failed in part 2";
            return false;
        }

        $vsprintf = $exe->code( "\x55\x8B\xEC\x83\xEC\x20\x8B\x45\x08\x56\xFF\x75\x10", "\xAB");
        if ($vsprintf === false) {
            if (!$free) {
                $free = $exe->zeroed(81 + 1); // It requires 81 bytes, added an extra 1 byte for safety
            }
            if ($free === false) {
                echo "Failed in part 3";
                return false;
            }

            $codes = array(
                "\x55\x8B\xEC\x81\xEC\xAB\x02\x00\x00\x53\x56",
                "\x55\x8B\xEC\x81\xEC\x48\x02\x00\x00\x53\x56",
                "\x55\x8B\xEC\x81\xEC\x4C\x02\x00\x00\x53\x56",
                );
            foreach ($codes as $code) {
                $output = $exe->code($code, "\xAB");
                if ($output !== false)
                    break;
            }
            if ($output === false) {
                echo "Failed in part 4";
                return false;
            }

            $call__flsbuf = $exe->code("\x55\x8B\xEC\x53\x56\x8B\x75\x0C\x8B\x46\x0C\x8B\x5E\x10\xA8\x82", "\xAB");
            if ($call__flsbuf === false) {
                echo "Failed in part 5";
                return false;
            }

            $vsprintf = "\x55\x8B\xEC\x83\xEC\x20\x8B\x45\x08\x56\xFF\x75\x10\x89\x45\xE8\x89\x45\xE0\x8D\x45\xE0\xFF\x75\x0C\xC7\x45\xEC\x42\x00\x00\x00\xC7\x45\xE4\xFF\xFF\xFF\x7F\x50" .
                        "\xE8" . pack("I", ($output - ($free + 45))) .
                        "\x83\xC4\x0C\xFF\x4D\xE4\x8B\xF0\x78\x08\x8B\x45\xE0\x80\x20\x00\xEB\x0D\x8D\x45\xE0\x50\x6A\x00" .
                        "\xE8" . pack("I", ($call__flsbuf - ($free + 74))) .
                        "\x59\x59\x8B\xC6\x5E\xC9\xC3";
            $exe->insert($vsprintf, $free);
            $vsprintf = $free;
        }

        $OutputDebugStringA = $exe->func("OutputDebugStringA");
        if ($OutputDebugStringA === false) {
            echo "Failed in part 6";
            return false;
        }

        $code = "\x55\x8B\xEC\xB8\x00\x10\x00\x00\xE8" . pack("I", ($alloca_probe - ($offset + 13))) .
            "\x8D\x45\x0C\x50\xFF\x75\x08\x8D\x85\x00\xF0\xFF\xFF\x50\xE8" . pack("I", ($vsprintf - ($offset + 32))) .
            "\x83\xC4\x0C\x8D\x85\x00\xF0\xFF\xFF\x50\xFF\x15" . pack("I", $OutputDebugStringA) .
            "\xC9\xC3\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\xE9\xBB\xFF\xFF\xFF\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90";
        $exe->replace($offset, array(0 => $code));
        return true;
    }

    static public function DisableCharnameChatParsing($exe)
    {
        if ($exe === true) {
            return "[UI]_Disable_Charname_Chat_Parsing";
        }
        $code = "\x51\x0F\xBE\x0D" . pack("I", $exe->str(" :")) . "\x51\x50\xE8\xAB\xAB\xAB\xAB\x83\xC4\x0C\x85\xC0\x75\xAB";
        $offsets = $exe->code($code, "\xAB", -1);
        if ($offsets === false) {
            echo "Failed in part 1";
            return false;
        }
        foreach ($offsets as $offset) {
            $exe->replace($offset, array(20 => "\x90\x90"));
        }
/*        $code = "\x50\x0F\xBE\x05" . pack("I", $exe->str(" :")) . "\x50\x51\xE8\xAB\xAB\xAB\xAB\x83\xC4\x0C\x85\xC0\x74\xAB";
        $offsets = $exe->code($code, "\xAB", 2);
        if ($offsets === false) {
            echo "Failed in part 2";
            return false;
        }
        foreach ($offsets as $offset) {
            $exe->replace($offset, array(20 => "\x90\x90"));
        }
*/        return true;
    }

    static public function IgnoreFileChecksum($exe)
    {
        if ($exe === true) {
            return "[Required]_Ignore_File_Checksum_Langtype_0";
        }
/*        $code = "\x00\x8B\xCE\xE8\xAB\xAB\x04\x00\x8B\x46\x14\x85\xC0\x0F\x84\x56\x01\x00\x00\xE8";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 0";
            return false;
        }
        $exe->replace($offset, array(3 => "\x90\x90\x90\x90\x90"));
*/        $code = "\x00\x85\xC0\x75\x7D\x8A\x15";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(3 => "\xEB"));
        return true;
    }

    static public function DisableEffect($exe)
    {
        if ($exe === true) {
            return "[UI]_Disable_/effect";
        }
        // This part disables loading /effect state from registry
        $code = "\x68" . pack("I", $exe->str("isEffectOn")) . "\x52\xFF\xD6\x85\xC0\x74\xAB\xC7\x07\x01\x00\x00\x00";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(14 => "\x00"));

        // This makes the command always enable it, instead of disabling it if it's enabled
        $code = "\xBA\x01\x00\x00\x00\x2B\xD0\x8D\x73\x50\x89\x15\xAB\xAB\xAB\xAB\x8B\xFE\xB8" . pack("I", $exe->str("xmas_fild01.rsw"));
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(5 => "\x90\x90"));

        // Disables the command (makes the client show invalid command)
        $codes = array(
          //"\x08\x00\x00\x00\xC7\x45\xF0" . pack("I", $exe->str("/effect")) . "\x89\x5D\xF4\x8B\x4E\x08\x8D\x45\xF0\x50\x51\x8B\xCE\xE8", 2010-03-09a
            "\x08\x00\x00\x00\xC7\x45\xAB" . pack("I", $exe->str("/effect")) . "\x89\x5D\xF4\x8B\xAB\x08\x8D\xAB\xAB\xAB\xAB\x8B\xCE\xE8",
            "\xC7\x45\xAB" . pack("I", $exe->str("/effect")) . "\xC7\x45\xAB\x08\x00\x00\x00\x8B\xAB\x08\x8D\xAB\xAB\xAB\xAB\x8B\xCE\xE8",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }

        if ($offset === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array(24 => "\x83\xC4\x08\x90\x90"));

        return true;
    }

    static public function MultiLanguageSupport($exe)
    {
        if ($exe === true) {
            return "[Fix]_Multi_Language_Support";
        }
        $code = "\x03\x75\x06\x88\x1D";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(1 => "\x90\x90"));
        return true;
    }

    static public function GuildMessageCrashFix($exe)
    {
        if ($exe === true) {
            return "[Fix]_Guild_Message_Crash_Fix_(Recommended)";
        }
        $code = "\x75\xC7\xA1\xAB\xAB\xAB\xAB\x85\xC0\x7E\x5B\x8B";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(2 => "\xEB\x62\x90\x90\x83\xF8\x18\x7D"));
        $code = "\x2B\x45\xE4\xEB\xBC\x8B\x75\x10";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(4 => "\xBB"));
        return true;
    }

    static public function EnableNewCharSelectScreen($exe)
    {
        if ($exe === true) {
            return "[UI]_Use_New_Char_Selection_Screen_on_All_Langtypes_(Recommended)";
        }
        $code = "\x0F\x85\xDB\x01\x00\x00";
        $code .= "\x8A";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\x90\x90\x90\x90\x90"));
        return true;
    }

    static public function GravityErrorHandler($exe)
    {
        if ($exe === true)
            return "[UI]_Change_Gravity_Error_Handler_(Recommended)";
        global $clientdate, $clienttype;
        $code = "";
        $code2 = "";
        $string = "";
        $string2 = "";
        $chars = str_split("Gravity(tm) Error Handler");
        for ($i = 0; $i < count($chars); $i++)
            $code .= $chars[$i] . "\x00";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) == 0) {
            echo "Failed in part 1";
            return false;
        }
        $chars = str_split($clientdate . " " . $clienttype);
        for ($i = 0; $i < count($chars); $i++)
            $string .= $chars[$i] . "\x00";
        if(strlen($code) < strlen($string)) {
            echo "Failed - " . $string . " too long to Fit. ";
            return false;
        }
        $string .= str_repeat("\x20\x00", ((strlen($code) - strlen($string))/2));
        $exe->replace($offsets[1], array(0 => $string));
        $chars = str_split("to Gravity or Game Master.");
        for ($i = 0; $i < count($chars); $i++)
            $code2 .= $chars[$i] . "\x00";
        $offset = $exe->match($code2, "\xAB", 0);
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $chars = str_split("to Diff Team on eA Forums.");
        for ($i = 0; $i < count($chars); $i++)
            $string2 .= $chars[$i] . "\x00";
        if(strlen($code2) < strlen($string2)) {
            echo "Failed " . $string2 . " too long to Fit. ";
            return false;
        }
        $string2 .= str_repeat("\x00\x00", ((strlen($code2) - strlen($string2))/2));
        $exe->replace($offset, array(0 => $string2));
        return true;
    }

    /*
     On Ragexe, it seems the client does some sort of "encryption", calculating
     a value based on a constant value initialized by a small sub, and then XORing
     it with the WantToConnection packet (not sure if it's only with the two first
     bytes or more than that, needs testing). This patch disables the "key" initialization
     by NOP'ing the initializing sub. This is for testing purposes for now. [Fabio]
     Note: this needs a better name, anyone? It's also included in the patch
     below, because it's part of the WantToConnection obfuscation... however,
     Ragexes older than 2008-09-10a just have the packet number obfuscated,
     not the whole packet data.  So... I have no idea what to do. XD
    */
    static public function DisableWantToConnectionXORing($exe)
    {
        if ($exe === true) {
            return "[Packet]_Disable_WantToConnection_XORing_(EXPERIMENTAL)";
        }
        $offset = $exe->str("PACKET_CZ_ENTER");
        $code = /*"\xE8\xAB\xAB\xAB\xAB" . */"\x68" . pack("I", $offset);
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        // Checking it here, because that CALL isn't included in the pattern for performance purposes
        if ($exe->read($offset - 5, 1) != "\xE8") {
            // If the instruction just before the PUSH isn't a CALL... that's BAD
            echo "Failed in part 2";
            return false;
        }
        // Calculate the offset based on the CALL
        $call = $exe->read($offset - 4, 4, "i");
        $call += $offset;
        // Check to see if it's really the sub we want to patch
        $code = "\xC7\x41\x04\xAB\xAB\xAB\xAB\xC7\x41\x08\xAB\xAB\xAB\xAB\xC7\x41\x0C\xAB\xAB\xAB\xAB";
        $nop = str_repeat("\x90", strlen($code) - 1); // Gonna NOP everything except the RET instruction
        $offset = $exe->match($code, "\xAB", $call);
        if ($offset !== $call) {
            echo "Failed in part 3";
            return false;
        }
        // This is a little hack for newer clients, sort of
        if ($exe->read($offset + strlen($code), 3) == "\xC7\x41\x10") { // If there's a 4th MOV...
            if ($exe->read($offset + strlen($code) + 7, 1) != "\xC3") { // There's must be a RET after it...
                echo "Failed in part 4";
                return false;
            }
            $nop = str_repeat("\x90", strlen($code) + 7); // Gonna NOP only the MOV's
        } elseif ($exe->read($offset + strlen($code), 1) == "\xC3") { // If there isn't a 4th MOV, it must be a RET...
            $nop = str_repeat("\x90", strlen($code)); // Only MOV's!
        } else { // Else something went wrong
            echo "Failed in part 5";
            return false;
        }
        // End of hack
        $exe->replace($offset, array(0 => $nop));
        return true;
    }
    
    /*
    This is for Ragexe (maybe RagexeRE?) only. Sakexe doesn't have that as far as I know. [Fabio]
    Note: it's only needed on 2008-09-10aRagexe so far.
    */
    static public function DisableWantToConnectionObfuscation($exe)
    {
        if ($exe === true) {
            return "[Packet]_Disable_WantToConnection_Obfuscation_(Recommended)";
        }
        if (self::DisableWantToConnectionXORing($exe) === false) { // Apply the above patch also
            echo "Failed in part 1";
            return false;
        }
        $code = "\x81\xF2\xAB\xAB\xAB\xAB\x89\x55\xF3";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(6 => "\x90\x90\x90"));
        $code = "\x35\xAB\xAB\xAB\xAB\x51\x52\x89\x45\xE6";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array(7 => "\x90\x90\x90"));
        return true;
    }

    /*
    This Diff change registry save from HKEY_LOCAL_MACHINE to HKEY_CURRENT_USER.
    Note: should be used on Setup.exe too.
    */

    static public function HKLMtoHKCU($exe)
    {
        if ($exe === true) {
            return "[Fix]_HKLM_To_HKCU";
        }
        $code = "\x68\x02\x00\x00\x80";
        $offsets = $exe->code($code, "\xAB", -1);
        if ($offsets === false) {
            echo "Failed in part 1";
            return false;
        }
        foreach ($offsets as $offset) {
            $exe->replace($offset, array(1 => "\x01"));
        }
        return true;
    }
    
    /*
    04/02/2009
    RagexeRE and Sakexe now have nProtect KeyCrypt.
    This patch disables the loading of its dll.
    */
    static public function DisableKeyCrypt($exe)
    {
        if ($exe === true) {
            return "[Fix]_Disable_nProtect_KeyCrypt_(Recommended)";
        }
        $code = "\xE8\xAB\xAB\xAB\xAB\x3B\xC3\x74\xAB\xE8\xAB\xAB\xAB\xAB\x3B\xC3\x75\x0E";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $relative = $exe->read($offset + 17, 1, "C");
        $relative += 16;
        $exe->replace($offset, array(0 => ("\xEB" . pack("C", $relative) . "\x90\x90\x90")));
        return true;
    }

    /*
    This remove Gravity Logo on Login Background
    */
    static public function RemoveGravityLogo ($exe)
    {
        if ($exe === true) {
            return "[UI]_Remove_Gravity_Logo";
        }

        $code = "\x54\x5F\x52\x25\x64\x2E\x74\x67\x61";
        $offset = $exe->matches($code, "\xAB", 0);
        if (count($offset) != 1) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        return true;
    }

    /*
    This remove Gravity Ads on Login Background
    */
    static public function RemoveGravityAds ($exe)
    {
        global $clientdate2;
        if ($exe === true) {
            return "[UI]_Remove_Gravity_Ads";
        }

        $code = "\x54\x5F\xC1\xDF\xB7\xC2\xBC\xBA\xC0\xCE\x2E\x74\x67\x61";
        $offset = $exe->matches($code, "\xAB", 0);
        if (count($offset) != 1) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));

        if ($clientdate2 <= 20091223) {
            $code = "\x54\x5F\x31\x32\xBC\xBC\x2E\x74\x67\x61";
            $offset = $exe->matches($code, "\xAB", 0);
            if (count($offset) != 1) {
                echo "Failed in part 2";
                return false;
            }
            $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        }
        else
        {
            $code = "\x54\x5F\x47\x61\x6D\x65\x47\x72\x61\x64\x65\x2E\x74\x67\x61";
            $offset = $exe->matches($code, "\xAB", 0);
            if (count($offset) != 1) {
                echo "Failed in part 3";
                return false;
            }
            $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        }

        $code = "\x54\x5F\xC5\xD7\xC0\xD4\x25\x64\x2E\x74\x67\x61";
        $offset = $exe->matches($code, "\xAB", 0);
        if (count($offset) != 1) {
            echo "Failed in part 4";
            return false;
        }
        $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        return true;
    }

    /*
    25/03/2009
    RagexeRE and Sakexe now have HShield.
    This patch disables the loading of its dlls.
    */
    static public function DisableHShield ($exe)
    {
        if ($exe === true) {
            return "[Fix]_Disable_HShield_(Recommended)";
        }
        
        $code = "\xE8\xAB\xAB\xAB\xFF\x85\xC0\x75\x0E\x5F\x5E\xB8\x01\x00\x00\x00\x5B\x8B\xE5\x5D\xC2\x10\x00\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $codes = array(
                "\x10\x00\x39\x1D\xAB\xAB\xAB\x00\x74\x2A\xE8\xAB\xAB\xAB\xFF\x84\xC0\x75\x21\x68\xAB\xAB\xAB\x00",
                "\x10\x00\xA1\xAB\xAB\xAB\x00\x33\xFF\x3B\xC7\x74\x2A\xE8\xAB\xAB\xAB\xAB\x84\xC0\x75\x21\x68\xAB\xAB\xAB",
                );
        foreach ($codes as $index => $code) {
            $offset2 = $exe->code($code, "\xAB");
            if ($offset2 !== false) {
                break;
            }
        }
        if ($offset2 === false) {
            echo "Failed in part 2";
            return false;
        }
    
        $exe->replace($offset, array(0 => "\xEB".pack("S", $offset2-$offset)));

        $rdata = $exe->getSection(".rdata");
        if($rdata === false) {
            echo "Failed in part 3";
            return false;
        }

        $aOffset = $exe->match("aossdk.dll\x00\x00", "", 0);
        if (count($aOffset) != 1) {
            echo "Failed in part 4";
            return false;
        }
        
        $code = "\x00\xAB\xAB\xAB\x00\x00\x00\x00\x00\x00\x00\x00\x00".pack("I", $aOffset);
        $offsets = $exe->matches($code, "\xAB", $rdata->rOffset, $rdata->rOffset+$rdata->rSize);
        if (count($offsets) != 1) {
            echo "Failed in part 5";
            return false;
        }
        
        $data = $exe->read($offsets[0] + 21, 19) . str_repeat("\x00", 20);
        $exe->replace($offsets[0], array(1 => $data));

        return true;
    }

    static public function OnlyFirstLoginBackground($exe)
    {
        if ($exe === true) {
            return "[UI]_Only_First_Login_Background";
        }

        $code = "\xC0\xAF\xC0\xFA\xC0\xCE\xC5\xCD\xC6\xE4\xC0\xCC\xBD\xBA\x5C\x54\x5F\xB9\xE8\xB0\xE6\x25\x64\x2D\x25\x64\x2E\x62\x6D\x70";
        $offset1 = $exe->matches($code, "\xAB", 0);
        if (count($offset1) != 1) {
            echo "Failed in part 1";
            return false;
        }

        $code = "\xC0\xAF\xC0\xFA\xC0\xCE\xC5\xCD\xC6\xE4\xC0\xCC\xBD\xBA\x5C\x54\x32\x5F\xB9\xE8\xB0\xE6\x25\x64\x2D\x25\x64\x2E\x62\x6D\x70";
        $offset2 = $exe->matches($code, "\xAB", 0);
        if (count($offset2) != 1) {
            echo "Failed in part 2";
            return false;
        }

        $code = "\x68".pack("I", $offset2[0]+4194304);
        $offsets = $exe->code($code, "\xAB", -1);
        if ($offsets === false) {
            echo "Failed in part 3";
            return false;
        }

        foreach ($offsets as $offset) {
            $exe->replace($offset, array(1 => pack("I", $offset1[0]+4194304)));
        }
        return true;
    }
    
    static public function OnlySecondLoginBackground($exe)
    {
        if ($exe === true) {
            return "[UI]_Only_Second_Login_Background";
        }

        $code = "\xC0\xAF\xC0\xFA\xC0\xCE\xC5\xCD\xC6\xE4\xC0\xCC\xBD\xBA\x5C\x54\x32\x5F\xB9\xE8\xB0\xE6\x25\x64\x2D\x25\x64\x2E\x62\x6D\x70";
        $offset1 = $exe->matches($code, "\xAB", 0);
        if (count($offset1) != 1) {
            echo "Failed in part 1";
            return false;
        }

        $code = "\xC0\xAF\xC0\xFA\xC0\xCE\xC5\xCD\xC6\xE4\xC0\xCC\xBD\xBA\x5C\x54\x5F\xB9\xE8\xB0\xE6\x25\x64\x2D\x25\x64\x2E\x62\x6D\x70";
        $offset2 = $exe->matches($code, "\xAB", 0);
        if (count($offset2) != 1) {
            echo "Failed in part 2";
            return false;
        }

        $code = "\x68".pack("I", $offset2[0]+4194304);
        $offsets = $exe->code($code, "\xAB", -1);
        if ($offsets === false) {
            echo "Failed in part 3";
            return false;
        }

        foreach ($offsets as $offset) {
            $exe->replace($offset, array(1 => pack("I", $offset1[0]+4194304)));
        }
        return true;
    }

    static public function EnableNCharacterSlots($exe, $num = "9")
    {
        if ($exe === true) {
            if($num == "9")
                return "[Fix](12)_Enable_".$num."_Character_Slots_(Recommended)";
            else
                return "[Fix](12)_Enable_".$num."_Character_Slots";
        }

        global $clientdate2;
        $hex = pack("C", $num);
        $code = "\x75\xAB\x8D\x04\xF6\x6A\x00\x6A\x12\x6A\x01\x8D\xAB\xC0\x6A\x00\x68";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\xEB"));

        $code = "\x68" . pack("I", $exe->str("extendedslot")) . "\x8B\xAB";
        $len = strlen($code);
        $code .= "\xE8\xAB\xAB\xAB\xAB\x85\xC0\x74\x07\xC6\x05\xAB\xAB\xAB\xAB\x01\x68" . pack("I", $exe->str("readfolder"));
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array((7 + $len) => "\x90\x90"));

/*    This is used to check how many matches for the first search are
/*    in case that the code broke
/*
        $code = "\xB8\xAB\x00\x00\x00\x89\x86\xAB\x01\x00\x00\x89";
        $offsets = $exe->matches($code, "\xAB", 0);
        echo "\n" . count($offsets) . "\n";
        for ($i = 0; $i < count($offsets); $i++)
            echo dechex($offsets[$i]) . "\n";
*/

        if (20090225 <= $clientdate2) {
            $code = "\xB8\xAB\x00\x00\x00\x89\x86\xAB\x01\x00\x00\x89";
            $offsets = $exe->matches($code, "\xAB", 0);
            if (count($offsets) != (($clientdate2 <= 20090715) ? "4" : (($clientdate2 < 20090811) ? "5" : "6"))){
                echo "Failed in part 3";
                return false;
            }
            for ($i = 1; $i < (($clientdate2 <= 20090715) ? "4" : (($clientdate2 < 20090811) ? "5" : "6")); $i++)
                $exe->replace($offsets[$i], array(1 => $hex));

            $code = "\xC7\x86\x30\x01\x00\x00";
            $offsets = $exe->matches($code, "\xAB", 0);
            if (count($offsets) != (($clientdate2 <= 20090529) ? "4" : (($clientdate2 <= 20090610) ? "5" : "4"))){
                echo "Failed in part 4";
                return false;
            }
            for ($i = 0; $i < 2; $i++)
                $exe->replace($offsets[$i], array(6 => $hex));

            $code = "\xC7\x86\x2C\x01\x00\x00\xAB";
            $offsets = $exe->matches($code, "\xAB", 0);
            if (count($offsets) != 5) {
                echo "Failed in part 5";
                return false;
            }
            for ($i = 0; $i < 2; $i++)
                $exe->replace($offsets[$i], array(6 => $hex));
            return true;
        }
        if (20090204 <= $clientdate2) {
            $code = "\xC7\x86\x30\x01\x00\x00";
            $offsets = $exe->matches($code, "\xAB", 0);
            if (count($offsets) != 7) {
                echo "Failed in part 6";
                return false;
            }
            for ($i = 0; $i < 5; $i++)
                $exe->replace($offsets[$i], array(6 => $hex));
            return true;
        }
        $code = "\x75\xAB\xC7\x86\xAB\xAB\xAB\xAB\x04\x00\x00\x00";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 7";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\x90"));

        $code = "\x83\xF8\x09\x88\x9C\x06\xA0\x0B\x00\x00\x7D\x07";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 8";
            return false;
        }
        $exe->replace($offset, array(2 => $hex));

        $code = "\x83\x3D\xAB\xAB\xAB\xAB\x0C";
        $len = strlen($code);
        $code .= "\x74\xAB\x39\xAB\xCC\x0B\x00\x00\x7E\xAB\x89\xAB\xCC\x0B\x00\x00";
        $offsets = $exe->code($code, "\xAB", 2);
        if ($offsets === false) {
            echo "Failed in part 9";
            return false;
        }
        foreach ($offsets as $offset) {
            $exe->replace($offset, array((0 + $len) => "\xEB"));
        }
        return true;
    }

    /*
    This save ScreenShots in BMP instead of JPG by default
    */
    static public function SSInBMPByDefault ($exe)
    {
        if ($exe === true) {
            return "[UI]_ScreenShot_In_BMP_By_Default";
        }
        $code = "\x6A\x11\xFF\x15\xAB\xAB\xAB\x00\x33\xC9\x8A\xCC\xAB\xAB\xAB\xAB\x00\x84\xC9\x0F\x84\xAB\x01\x00\x00\x3B\xC7\x0F\x84\x9C\x00\x00\x00\x8B\x45\xD8\x8B\xAB\xAB\xAB\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(17 => "\x85"));

        return true;
    }

    /*
    This enable the Title Bar Menu and Icon
    */
    static public function EnableTitleBarMenu ($exe)
    {
        if ($exe === true) {
            return "[UI]_Enable_Title_Bar_Menu";
        }
        $code = "\x68\x00\x00\xC2\x02";
        $offset = $exe->matches($code, "\xAB", 0);
        if (count($offset) != 2) {
            echo "Failed in part 1";
            return false;
        }
        //$exe->replace($offset[0], array(3 => "\xCA"));
        $exe->replace($offset[1], array(3 => "\xCA"));
        return true;
    }

    /*
    This enable the Official Custom Fonts on All Langtype
    */
    static public function EnableOfficialCustomFonts ($exe)
    {
        if ($exe === true) {
            return "[UI]_Enable_Official_Custom_Fonts";
        }
        $code = "\x85\xC0\x0F\x85\xAE\x00\x00\x00\xE8\xAB\xAB\xAB\xFF";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(2 => "\x90\x90\x90\x90\x90\x90"));

        return true;
    }

    /*
    This translate client in English.
    */
    static public function TranslateClientInEnglish ($exe)
    {
        if ($exe === true) {
            return "[UI]_Translate_Client_In_English_(Recommended)";
        }
        global $clientdate2;
        if (20091110 <= $clientdate2) {
            $codes = array(
/* 0 */        "\x00\xB1\xE2\xC5\xB8\x20\xC1\xF7\xBE\xF7\xB1\xBA\x00",
                "\x00\xC0\xFC\xBD\xC2\x20\x32\xC2\xF7\x20\xC1\xF7\xBE\xF7\x00",
                "\x00\xC0\xFC\xBD\xC2\x20\x31\xC2\xF7\x20\xC1\xF7\xBE\xF7\x00",
                "\x00\x33\x2D\x32\xC2\xF7\x20\xC1\xF7\xBE\xF7\x00\x00",
                "\x00\x33\x2D\x31\xC2\xF7\x20\xC1\xF7\xBE\xF7\x00\x00",
                "\x00\x32\xC2\xF7\x20\xC1\xF7\xBE\xF7\x00\x00",
                "\x00\x31\xC2\xF7\x20\xC1\xF7\xBE\xF7\x00\x00",
                "\x00\xBF\xAA\xC7\xD2\x00\x00",
                "\x00\xB7\xB9\xBA\xA7\xC0\xBA\x20\x31\x7E\x31\x35\x30\x20\xBB\xE7\xC0\xCC\xC0\xC7\x20\xBC\xFD\xC0\xDA\xB8\xA6\x20\xC0\xD4\xB7\xC2\xC7\xD8\x20\xC1\xD6\xBC\xBC\xBF\xE4\x2E\x00",
                "\x00\xB7\xB9\xBA\xA7\xBF\xA1\x20\xBC\xFD\xC0\xDA\x20\xC0\xCC\xBF\xDC\xC0\xC7\x20\xB9\xAE\xC0\xDA\xB4\xC2\x20\xB5\xE9\xBE\xEE\xB0\xA5\x20\xBC\xF6\x20\xBE\xF8\xBD\xC0\xB4\xCF\xB4\xD9\x2E\x00",
/* 10 */    "\x00\xBC\xB1\xC5\xC3\xB5\xC8\x20\xC1\xF7\xBE\xF7\xC0\xBA\x20\x25\x64\xB0\xB3\xC0\xD4\xB4\xCF\xB4\xD9\x2E\x20\xC3\xD6\xB4\xEB\x20\x36\xB0\xB3\xB1\xEE\xC1\xF6\xB8\xB8\x20\xC1\xF7\xBE\xF7\xC0\xBB\x20\xBC\xB1\xC5\xC3\xC7\xD2\x20\xBC\xF6\x20\xC0\xD6\xBD\xC0\xB4\xCF\xB4\xD9\x2E\x00",
                "\x00\xC3\xD6\xBC\xD2\x20\x31\xB0\xB3\xC0\xCC\xBB\xF3\xC0\xC7\x20\xC1\xF7\xBE\xF7\xC0\xBB\x20\xBC\xB1\xC5\xC3\xC7\xD8\x20\xC1\xD6\xBC\xC5\xBE\xDF\x20\xC7\xD5\xB4\xCF\xB4\xD9\x2E\x00",
                "\x00\xB8\xF0\xB5\xCE\x20\xBC\xB1\xC5\xC3\x00\x00",
                "\x00\xC6\xC4\xC6\xBC\xBF\xF8\xC0\xBB\x20\xB8\xF0\xC1\xFD\xC7\xD5\xB4\xCF\xB4\xD9\x2E\x00\x00",
                "\x00\xB8\xF0\xC1\xFD\x20\xC7\xCF\xB1\xE2\x00",
                "\x00\xB4\xEB\xC8\xAD\x00",
                "\x00\xB0\xCB\xBB\xF6\xC1\xDF\x20\x2D\x20\x00\x00",
                "\x00\xC6\xC4\xC6\xBC\xB1\xA4\xB0\xED\xB0\xA1\x20\xC3\xDF\xB0\xA1\xB5\xC7\xBE\xFA\xBD\xC0\xB4\xCF\xB4\xD9\xAB\x00",
                "\x00\x28\xC4\xB3\xB8\xAF\xC5\xCD\x2F\xC3\xD1\x20\xBD\xBD\xB7\xD4\x29\x00",
                "\x00\x20\xC3\xA2\x20\xC7\xA5\xBD\xC3\x20\xC1\xA4\xBA\xB8\xAB",
/* 20 */    "\x00\x20\x20\xC3\xA4\xC6\xC3\xB8\xF0\xB5\xE5\x20\x4F\x6E\x4F\x66\x66\x20\xC8\xB0\xBC\xBA\xC8\xAD\x00",
            );
            $changes = array(
/* 0 */        "Other Jobs\x00",
                "2nd Jobs High\x00",
                "1st Jobs High\x00",
                "3-2 Classes\x00",
                "3-1 Classes\x00",
                "2nd Jobs\x00",
                "1st Jobs\x00",
                "Roles\x00",
                "Please enter levels between 1~150.\x00",
                "Only numeric characters are allowed.\x00",
/* 10 */    "You have selected %d Jobs. You can only have up to 6 different Jobs\x00",
                "You have to select atleast 1 or more Jobs.\x00",
                "Select All\x00",
                "Recruit party members\x00",
                "Recruit\x00",
                "Talk\x00",
                "Searching \x00",
                "Party ads has been added.\x00",
                "(Used / Total)\x00",
                " Display Info\x00",
/* 20 */    "  Enable Battlemode\x00",
            );
            // sanity check for string overflow
            foreach ($codes as $index => $code) {
                if(strlen($changes[$index])+1 > strlen($code)){
                    die("\n\nTranslateClientInEnglish string length error at index $index\n\n");
                }
            }

            $data = $exe->getSection(".data");
            if($data === false) {
                    echo "Failed in part section"; // Höhö.. :>
                    return false;
            }
            
            $dOffset = $data->rOffset;
            $dOffset2 = $data->rOffset+$data->rSize;
            
            $failed = "Failed in parts ";
            $fails = 0;
            foreach ($codes as $index => $code) {
                if ($index == 7 && $clientdate2 <= 20100324 ||  //ignore Roles
                $index >= 13 && $index <= 14 && $clientdate2 <= 20100317 ||
                $index >= 1 && $index <= 6 && ($clientdate2 >= 20100309  && $clientdate2 <= 20100310) ||
                $index == 3 && $clientdate2 == 20100316 ||
                $index >= 19 && $index <= 20 && $clientdate2 >= 20100601 ||
                ($index >= 0 && $index <= 6 || $index >= 8 && $index <= 12 || $index >= 15 && $index <= 17 || $index == 20) && $clientdate2 <= 20100303) { 
                    continue;
                }
                $offset = $exe->match($code, "\xAB",$dOffset,$dOffset2);
                if ($offset === false) {
                    $fails++;
                    $failed = $failed . "$index ";
                    continue;
                } else {
                    $exe->replace($offset, array(1 => $changes[$index]));
                }
            }
            if($fails == sizeof($codes)) return false;
            if($fails > 0){
                global $failcount;
                $failcount++;
                echo $failed . " - others ";
            }
        }
        
            /*
            Translate Message Box (by Natz)
            */
            if (20091006 <= $clientdate2) {
                $code = $exe::Hex("B8 DE BD C3 C1 F6");
                // Shinryo: Even though there are more occurrences, we assume that
                // the first one is always the correct one
                $offset = $exe->match($code, "\xAB", 0);
                if ($offset === false) {
                    echo "Failed in part 21";
                    return false;
                }
                $exe->replace($offset, array(0 => "message"."\x00"));
            }

            /*
            Taekwon SL SG Korean to English
            */
            $codes = array(
                "\x85\xC0\x75\x59\xE8\xAB\xAB\xAB\xAB\x85\xC0",
                "\xB9\xAB\xAB\xAB\xAB\x85\xC0\x75\x59\xE8",
            );
            $codeoffsets = array(2,7);
            $changes = array("\xEB","\xEB");
            foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false)
                break;
            }
            if ($offset === false) {
                echo "Failed in part 22";
                return false;
            }
            $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));

            /*
            Cash Points Korean to English
            */
            $codes = array(
                "\x6A\x85\xC0\x75\x31",
                "\x39\x85\xC0\x75\x1D",
                "\x3A\x85\xC0\x75\x1E",
                "\x43\x85\xC0\x75\x21",
                "\x3F\x85\xC0\x75\x1F",
            );
            foreach ($codes as $index => $code) {
                $offset = $exe->code($code, "\xAB");
                if ($offset !== false)
                    break;
            }
            if ($offset === false) {
                echo "Failed in part 23";
                return false;
            }
            $exe->replace($offset, array(3 => "\xEB"));

            /*
            Map Indicator in langtype 1+
            */
            $code = "\xBA\x5C\x6D\x61\x70\x5C\x00\xA1\xDA\x00\x00";
            $offset = $exe->match($code, "\xAB", 0);
            if ($offset === false) {
                echo "Failed in part 24";
                return false;
            }
            $exe->replace($offset, array(6 => "\x00\x2A\x00\x00\x00"));

            /*
            Status Icons Timers to English (seconds) (by Earthlingz)
            */
            if (20100316 <= $clientdate2 && 20100707 >= $clientdate2) {
                /* Seconds */
                $code = "\x25\x64\x20\xC3\xCA\x00\x00\x00";
                $offset = $exe->matches($code, "\xAB", 0);
                if (count($offset) != 1) {
                    echo "Failed in part 25";
                    return false;
                }
                $exe->replace($offset[0], array(3 => "secs"."\x00"));
            
                /* Minutes */
                $code = "\x25\x64\x20\xBA\xD0\x20\x00\x00";
                $offset = $exe->matches($code, "\xAB", 0);
                if (count($offset) != 1) {
                    echo "Failed in part 26";
                    return false;
                }
                $exe->replace($offset[0], array(2 => "mins "."\x00"));
                
                /* Hours */
                $code = "\x25\x64\x20\xBD\xC3\xB0\xA3\x20\x00\x00\x00\x00";
                $offset = $exe->matches($code, "\xAB", 0);
                if (count($offset) != 1) {
                    echo "Failed in part 27";
                    return false;
                }
                $exe->replace($offset[0], array(3 => "hours "."\x00"));
            }
            return true;
    }

    /*
    This Disable Multiple Windows.
    */
    static public function DisableMultipleWindows($exe)
    {
        global $clientdate2;
        if ($exe === true) {
            return "[Fix](13)_Disable_Multiple_Windows";
        }

        $code = "\xB9\x0F\x00\x00\x00\x33\xC0\x8D\x7D\x89\xC6\x45\x88\x00\xF3\xAB";
        $nOffset = $exe->code($code, "\xAB");
        if ($nOffset === false) {
            echo "Failed in part 1";
            return false;
        }
        
        $codes = array(
            "\x00\x00\x00\x83\xF8\x03\x0F\x84\xEA\x00\x00\x00\x83\xF8\x05",
            "\x00\x00\x00\x83\xF8\x03\x0F\x84\xF3\x00\x00\x00\x83\xF8\x05", // <- Probably fail
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false)
                break;
        }
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        //$exe->replace($offset, array(6 => "\xE8".pack("I", $nOffset-$offset+6)));
        $exe->replace($offset, array(-3 => "\xE9".pack('V', $nOffset-($offset-3+5))));

        return true;
    }

    static public function ChangeVersionIntoDate($exe)
    {
        global $clientdate2;

        if ($exe === true) {
            return "[Fix]_Change_Version_Into_Date_(Experimental)";
        }
        $code = "\xE8\xAB\xAB\xAB\x00\x83\xC4\x04\xA3\xAB\xAB\xAB\x00\x68".pack("I", $exe->str("langtype"));
        $offsets = $exe->code($code, "\xAB", -1);
        if ($offsets === false){
            echo "Failed in part 1";
            return false;
        }
        $bindate = pack("I", $clientdate2);
        foreach ($offsets as $offset) {
            $exe->replace($offset, array(0 => "\x83\xC4\x04\xC7\x05" . $exe->read($offset + 9, 4) . $bindate));
        }
        return true;
    }

    static public function TranslateClient($exe, $extra)
    {
        global $arrchange;

        $type = $extra["type"];
        $lang = $extra["lang"];

        if ($exe === true) {
            return "[UI]_Translate_".$type."_Into_".$lang."_(Experimental)";
        }

        // Reads .data offset and size
        $offset = $exe->match(".data\x00\x00\x00", "", 0);
        $offset += 8 + 2 * 4;
        $pSize = $exe->read($offset, 4, "I"); // Size
        $offset += 4;
        $pOffset = $exe->read($offset, 4, "I"); // Offset

        $arrtype = $arrchange[$type];
        foreach($arrtype as $key => $value)
        {
            if(isset($value[$lang]) && bin2hex(strlen($key)) >= bin2hex(strlen($value[$lang])))
            {
                $code = "\x00".$key."\x00";
                $offset = $exe->match($code, "", $pOffset-98304, $pOffset + $pSize);
                if ($offset === false)
                {
                    echo "1) Where is \"".$key."\" ?\r\n";
                    continue;
                }
                $exe->replace($offset, array(1 => $value[$lang]."\x00"));
            }
            elseif(isset($value[$lang]) && bin2hex(strlen($key)) < bin2hex(strlen($value[$lang])))
            {
                $free = $exe->zeroed((strlen(bin2hex($key)) / 2) + 2);
                if ($free === false) {
                    return false;
                }
                $insertaddr = pack("I", $free+1+4194304);

                $str = $exe->str($key);
                if($str === false)
                {
                    echo "2) Where is \"".$key."\" ?\r\n";
                    continue;
                }
                $straddr = pack("I", $str);

                $codes = array(
                    "\xC7\xAB\xAB" . $straddr,
                    "\xC7\xAB\xAB\xAB\xAB\xAB" . $straddr,
                    "\x68" . $straddr,
                    "\xB8" . $straddr,
                    "\xBE" . $straddr,
                    "\xBF" . $straddr,
                );
                $codeoffsets = array(3,6,1,1,1,1);    //Offset where to Change Code
                $done = false;
                foreach ($codes as $index => $code) {
                    $offsets = $exe->code($code, "\xAB", -1);
                    if ($offsets !== false)
                    {
                        $done = true;
                        foreach ($offsets as $offset)
                            $exe->replace($offset, array($codeoffsets[$index] => $insertaddr));
                    }
                }
                if ($offsets === false && !$done)
                {
                    echo "3) Where is \"".$key."\" ?\r\n";
                    continue;
                }
                $exe->insert("\x00".$value[$lang]."\x00", $free);
            }
        }
        return true;
    }

    static public function RemoveGravityErrorMessage($exe)
    {
        if ($exe === true) {
            return "[Fix]_Remove_Gravity_Error_Message_(Experimental)";
        }
        $code = "\xFF\x15\xAB\xAB\xAB\x00\x5F\x5E\xB8\x01\x00\x00\x00\x5B\x8B\xE5\x5D\xC2\x04\x00";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\x90\x90\x90\x90\x90"));

        return true;
    }
    
    // Disable Renewal Capatcha Request [LightFighter]
    static public function DisableCaptcha($exe)
    {
        if ($exe === true) {
            return "[Fix]_Disable_Captcha";
        }
        
        $code = "\x15\x00\x00\x00\x33\xC0\x5F\x5E\x5B\x8B\xE5\x5D\xC2\x10\x00";
        $offset = $exe->code($code, "");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x16"));
        
        return true;
    }

    // Load .lua files before .lub files [YomNomNom]
    static public function LoadLuaBeforeLub($exe)
    {
        if ($exe === true) {
            return "[Data]_Load_Lua_Before_Lub";
        }
        $code = "\x2E\x6C\x75\x61\x00\x00\x00\x00\x2E\x6C\x75\x62";
        $offset = $exe->match($code, "\xAB", 0);
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(3 => "\x62\x00\x00\x00\x00\x2E\x6C\x75\x61"));
        
        return true;
    }

    // Restore The Login Window
    static public function LoginWindow($exe)
    {
        if ($exe === true) {
            return "[Fix]_Restore_Login_Window";
        }
        // First Pattern
        $codes = array(
/*2010-08-10*/        "\xA0\x2C\xD5\x83\x00\x84",//\xC0\x74\x21\xC6\x05\x2C\xD5\x83\x00\x00\x5F\xC7\x43\x0C\x04\x00\x00\x00\x5E\x5B\x8B\x4D\xF4\x64\x89\x0D\x00\x00\x00\x00\x8B\xE5\x5D\xC2\x04\x00\xB9\x90\x34\x81\x00\xE8\x1C\x39\xF2\xFF\x5F\x5E\x5B\x8B\x4D\xF4\x64\x89\x0D\x00\x00\x00\x00\x8B\xE5\x5D\xC2\x04\x00",
/*2010-08-04*/        "\xA0\x04\xD5\x83\x00\x84",//\xC0\x74\x21\xC6\x05\x04\xD5\x83\x00\x00\x5F\xC7\x43\x0C\x04\x00\x00\x00\x5E\x5B\x8B\x4D\xF4\x64\x89\x0D\x00\x00\x00\x00\x8B\xE5\x5D\xC2\x04\x00\xB9\x58\x34\x81\x00\xE8\x5C\x3B\xF2\xFF\x5F\x5E\x5B\x8B\x4D\xF4\x64\x89\x0D\x00\x00\x00\x00\x8B\xE5\x5D\xC2\x04\x00",
        );
        $changes = array(
/*2010-08-10*/        "\x6A\x03\xB9\xF0\x9D\x7D\x00\xE8\xE4\xCD\xEF\xFF\x8A\x0D\x70\x34\x81\x00\x84\xC9\x0F\x85\xFB\x13\x00\x00\x8B\x10\x33\xC9\x51\x51\x51\x51\x6A\x58\x51\x8B\xC8\xE9\x12\xFF\xFF\xFF\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90",
/*2010-08-04*/        "\x6A\x03\xB9\xB8\x9D\x7D\x00\xE8\x24\xD0\xEF\xFF\x8A\x0D\x38\x34\x81\x00\x84\xC9\x0F\x85\xFB\x13\x00\x00\x8B\x10\x33\xC9\x51\x51\x51\x51\x6A\x58\x51\x8B\xC8\xE9\x12\xFF\xFF\xFF\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => $changes[$index]));
        
        // Second Pattern
        $codes = array(
/*2010-08-10*/        "\x0F\x85\xC9\x01\x00\x00\xA1\x7C\x34\x81\x00\x33\xF6\x3B\xC6\x74\x49\x83\xF8\x12\x74\x44",
/*2010-08-04*/        "\x0F\x85\xC9\x01\x00\x00\xA1\x44\x34\x81\x00\x33\xF6\x3B\xC6\x74\x49\x83\xF8\x12\x74\x44",
        );
        $changes = array(
/*2010-08-10*/        "\x0F\x85\xC9\x01\x00\x00\xA1\x7C\x34\x81\x00\x33\xF6\x3B\xC6\x90\x90\x83\xF8\x12\x90\x90",
/*2010-08-04*/        "\x0F\x85\xC9\x01\x00\x00\xA1\x44\x34\x81\x00\x33\xF6\x3B\xC6\x90\x90\x83\xF8\x12\x90\x90",
        );
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(0 => $changes[$index]));
        return true;
    }

    // increases ViewID to 2000 [Waeyan/Yommy]
    static public function IncreaseViewID($exe)
    {
        if ($exe === true) {
            return "[Add]_Increase_Headgear_ViewID_to_2000";
        }
        $codes = array(
        "\xFF\xB9\xE8\x03\x00\x00\x2B\xC8\x51",
        "\xFF\x3D\xE8\x03\x00\x00\x76\x15\x8B",
        "\x81\xFE\xE8\x03\x00\x00\x7C\xB8\x8B",
        );
        $codeoffsets = array(2,2,2);
        $changes = array("\xD0\x07","\xD0\x07","\xD0\x07");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part $index";
                return false;
            } else {
                $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
            }
        }
        return true;
    }
        
    static public function Enable127Hairstyles($exe)
    {
        if ($exe === true) {
            return "[Add]_Enable_127_Hairstyles";
        }
        
        $codes = array(
            "\xC0\xCE\xB0\xA3\xC1\xB7\x5C\xB8\xD3\xB8\xAE\xC5\xEB\x5C\x25\x73\x5C\x25\x73",
            "\x7C\x05\x83\xF8\x1B\x7E\x06",
            "\x8B\x14\x96\x52\x8B\x14\x81\x52\xEB\x20",
            "\x8B\x12\x8B\x40\x04\x50",
        );
        
        $codeoffsets = array(18, 4, 0, 12);
        $changes = array("\x64","\x7F","\x90\x90\x90","\x90\x90\x90");
        
        foreach ($codes as $index => $code) {
            $offsets = $exe->matches($code, "\xAB", 0);
            if ($offsets === false || count($offsets) != 2) {
                echo "Failed in part $index";
                return false;
            }
            
            foreach ($offsets as $offset) {
                $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
            }
        }

        return true;
    }
    
    static public function UseSharedPalettes($exe)
    {

        if ($exe === true) {
            return "[Add]_Use_shared_palettes";
        }
    
        $part = 1;        
        $codes = array(
            "\x01\x00\x00\x89\x86\x20\x01\x00\x00\x8B\xC6\x5E\xC3",
            "\x84\xC0\x74\x50\x8B\x8D\x70\xFF\xFF\xFF\x8B\x95\x68\xFF\xFF\xFF",
            "\x5F\x32\xC0\x5E\x64\x89\x0D\x00\x00\x00\x00\x8B\xE5\x5D\xC2\x04\x00",
        );
        $codeoffsets = array(13, 4, 17);
        $changes = array(
            "\x8B\x8D\x70\xFF\xFF\xFF\xEB\x55",
            "\xEB\x74\xEB\xA5\x90\x90",
            "\x85\xFF\x8D\x8D\x64\xFF\xFF\xFF\x74\xA0\xEB\x80"
        );
        
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part $part";
                return false;
            }
            
            if($index == 0) $funcFileExists = $offset+23+$exe->imagebase();
            
            $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
            $part++;
        }
        
        // Cloth Colors
    
        // >= 2010-04-20
        $offset = $exe->code("\xEB\x03\x8B\x14\x91\x8B\x75\x14\x52", "\xAB");
        
        // <= 2010-04-14
        if($offset === false)
            $offset = $exe->code("\xEB\x03\x8B\x14\x81\x8B\x75\x14\x52", "\xAB", 2);
    
        if ($offset === false) 
        {
            echo "Failed in part $part";
            return false;
        }
        
        if(is_array($offset))
            $offset = $offset[1];
        
        $part++;
        
        $funcSprintf = ($offset+15)+$exe->read($offset+16, 4, "I")+$exe->imagebase()+5;
        
        $code = array( 
            // Clothes
            "\x70\x61\x6C\x65\x74\x74\x65\x5C\xB8\xF6\x5C\x58\x25\x73\x25\x73\x5F\x25\x64\x2E\x70\x61\x6C\x00\x00\xB8\xF6\x5C\x61\x6C\x6C\x69\x6E\x6F\x6E\x65\x5F\x25\x64\x2E\x70\x61\x6C\x00\x00\xE8\x4E\xB5\xCC\xFF\x0F\xBE\xC8\x85\xC9\x75\x15\x8B\x55\x10\x52\x68\x19\x9E\x75\x00\x8B\x75\x14\x56\xE8\x9D\x98\xFA\xFF\x83\xC4\x0C\x89\xF0\x5E\x5D\xC2\x10\x00",
            "\x68\x00\x9E\x75\x00\x56\xE8\x99\x5B\x03\x00\x83\xC4\x14\x31\xC9\x8B\x75\x14\x56\xE9\xDF\xC2\x08\x00",
            
            // Hairs
            "\x70\x61\x6C\x65\x74\x74\x65\x5C\xB8\xD3\xB8\xAE\x5C\xB8\xD3\xB8\xAE\x25\x64\x25\x73\x5F\x25\x64\x2E\x70\x61\x6C\x00\x00\xB8\xD3\xB8\xAE\x5C\x61\x6C\x6C\x69\x6E\x6F\x6E\x65\x5F\x25\x64\x2E\x70\x61\x6C\x00\x00\x8B\x75\x18\x56\xE8\xB8\xB4\xCC\xFF\x0F\xBE\xC8\x85\xC9\x75\x15\x8B\x75\x14\x56\x68\xA9\x9E\x75\x00\x8B\x75\x18\x56\xE8\x07\x98\xFA\xFF\x83\xC4\x0C\x89\xF0\x5E\x5D\xC2\x14\x00",
        );
        
        $free = $exe->zeroed(strlen($code[0])+strlen($code[2])+1);
        if(!$free) {
            echo "Failed in part $part";
            return false;
        }
        
        $part++;

        $stringPos1 = $exe->imagebase()+$free;    // palette\¸ö\X%s%s_%d.pal
        $stringPos2 = $stringPos1+25;                        // ¸ö\allinone_%d.pal
        
        $code[0] = str_replace("\x4E\xB5\xCC\xFF", pack('V', $funcFileExists - ($free+45+$exe->imagebase()) - 5), $code[0]);
        $code[0] = str_replace("\x19\x9E\x75\x00", pack('V', $stringPos2), $code[0]);
        $code[0] = str_replace("\x9D\x98\xFA\xFF", pack('V', $funcSprintf - ($free+70+$exe->imagebase()) - 5), $code[0]);
        $exe->insert($code[0], $free);
        
        $code[1] = str_replace("\x00\x9E\x75\x00", pack('V', $stringPos1), $code[1]);
        $code[1] = str_replace("\x99\x5B\x03\x00", pack('V', $funcSprintf - ($offset+15+$exe->imagebase()) - 5), $code[1]);
        $code[1] = str_replace("\xDF\xC2\x08\x00", pack('V', ($free+45+$exe->imagebase()) - ($offset+29+$exe->imagebase()) - 5), $code[1]);
        
        $exe->replace($offset+9, array(0 => $code[1]));
        
        $free += strlen($code[0]);
        
        // Hair Colors
        
        $offset = $exe->code("\x8B\x14\x96\x52\x8B\x14\x81\x8B\x75\x18\x52", "\xAB");
        
        if ($offset === false) {
            echo "Failed in part $part";
            return false;
        }
        
        $part++;
        
        $stringPos1 = $exe->imagebase()+$free;    // palette\¸Ó¸®\¸Ó¸®%d%s_%d.pal
        $stringPos2 = $stringPos1+30;                        // ¸Ó¸®\allinone_%d.pal
        
        $exe->replace($offset, array(5 => "\x55\x08", 12 => pack('V', $stringPos1), 25 => "\x31\xC9\xE9".pack('V', ($free+52+$exe->imagebase()) - ($offset+27+$exe->imagebase()) - 5)));

        $code[2] = str_replace("\xB8\xB4\xCC\xFF", pack('V', $funcFileExists - ($free+56+$exe->imagebase()) - 5), $code[2]);
        $code[2] = str_replace("\xA9\x9E\x75\x00", pack('V', $stringPos2), $code[2]);
        $code[2] = str_replace("\x07\x98\xFA\xFF", pack('V', $funcSprintf - ($free+81+$exe->imagebase()) - 5), $code[2]);
        $exe->insert($code[2], $free);
                
        return true;
    }

    // prints out matches and offsets of patterns [YomNomNom]
    static public function PatternTest($exe)
    {
        $code = "\xFF\xA1\xAB\xAB\xAB\xAB\x85\xC0\x74\xAB\x83\xF8\x04";
        $offsets = $exe->matches($code, "\xAB", 0);
        echo "\npattern = ". bin2hex($code) ." \n";
        foreach ($offsets as $offset) {
            $data = $exe->read($offset, strlen($code));
            echo "\nmatch found @ " . dechex($offset) . " - " . bin2hex($data);
        }
        echo "\n\n";
        die();
    }


/*
    static public function FunctionName($exe)
    {
        if ($exe === true) {
            return "[Fix/...]_Function_Name_(Recommended/...)";
        }
        $codes = array("\x");    //Hex Code to Search for
        $codeoffsets = array();    //Offset where to Change Code
        $changes = array("\x");    //New Hex Values for the given Offset
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset !== false) {
                break;
            }
        }
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));

        return true;
    }
*/

}
?>