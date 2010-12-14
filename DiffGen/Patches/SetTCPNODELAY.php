<?php
    function SetTCPNODELAY($exe){
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
        $code =  "\x73\x65\x74\x73\x6F\x63\x6B\x6F\x70\x74\x00\x00\x57\x53"
                ."\x32\x5F\x33\x32\x2E\x44\x4C\x4C\x00\x55\x8B\xEC\x83\xEC"
                ."\x0C\xC7\x45\xF8\x01\x00\x00\x00\x8B\x45\x10\x50\x8B\x4D"
                ."\x0C\x51\x8B\x55\x08\x52\xA1\xEF\xBE\xAD\xDE\xFF\xD0\x89"
                ."\x45\xFC\x83\x7D\xFC\xFF\x74\x35\x68\x00\x00\x00\x00\x68"
                ."\x01\x00\x00\x00\x8B\x0D\xED\xBE\xAD\xDE\xFF\xD1\x50\x8B"
                ."\x15\xEE\xBE\xAD\xDE\xFF\xD2\x89\x45\xF4\x83\x7D\xF4\x00"
                ."\x74\x11\x6A\x04\x8D\x45\xF8\x50\x6A\x01\x6A\x06\x8B\x4D"
                ."\xFC\x51\xFF\x55\xF4\x8B\x45\xFC\x8B\xE5\x5D\xC2\x0C\x00";
        
        $free = $exe->zeroed(128, false);
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
?>