set PHP_PATH_FOLDER=c:\php-7.1.3-Win32-VC14-x64
set TEST_HOST=127.0.0.1:8001
rem set BROWSER_PATH=explorer
set BROWSER_PATH=D:\Ati\PortableApps\PortableApps\GoogleChromePortable\GoogleChromePortable.exe
start %PHP_PATH_FOLDER%\php.exe -S %TEST_HOST%
rem for explorer:
rem %BROWSER_PATH% %TEST_HOST%
rem for chrome:
%BROWSER_PATH% --disable-web-security %TEST_HOST%