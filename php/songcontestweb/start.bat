set PHP_PATH_FOLDER=c:\php-7.1.3-Win32-VC14-x64
rem set BROWSER_PATH=explorer
set BROWSER_PATH=D:\Ati\PortableApps\PortableApps\GoogleChromePortable\GoogleChromePortable.exe
start %PHP_PATH_FOLDER%\php.exe -S localhost:8000 
%BROWSER_PATH% "http://localhost:8000" 