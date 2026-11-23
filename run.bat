@echo off
title SIM BMN - Laravel Server
echo ========================================================
echo   SIM BMN KEMENAG MINAHASA
echo   Menjalankan server di http://127.0.0.1:8000
echo ========================================================
echo.
set "PATH=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64;%PATH%"
php artisan serve --host=127.0.0.1 --port=8000
pause

