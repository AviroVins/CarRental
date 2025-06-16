@echo off
setlocal enabledelayedexpansion

:: === KONFIGURACJA ===
set DB_NAME=AI_Rental
set DB_USER=postgres
set DB_PASSWORD=root
set DB_HOST=127.0.0.1
set DB_PORT=5432
set APP_URL=http://127.0.0.1:8000

:: === SET PGPASSWORD TO AVOID PROMPTS ===
set PGPASSWORD=%DB_PASSWORD%

echo.
echo === [1/7] Installing PHP dependencies...
call composer install
echo.

echo === [2/7] Creating .env if missing...
IF NOT EXIST .env (
    copy .env.example .env
)
echo.

echo === [3/7] Generating APP_KEY...
call php artisan key:generate
echo.

echo === [4/7] Testing database connection...
psql -U %DB_USER% -h %DB_HOST% -p %DB_PORT% -d %DB_NAME% -c "\q"
IF %ERRORLEVEL% NEQ 0 (
    echo -------------------------------------------
    echo ERROR: Failed to connect to the database
    echo Check your .env configuration and make sure PostgreSQL is running.
    echo -------------------------------------------
    pause
    exit /b %ERRORLEVEL%
)
echo Connection OK.
echo.

echo === [5/7] Running migrations and seeders...
call php artisan migrate:fresh --seed
IF %ERRORLEVEL% NEQ 0 (
    echo -------------------------------------------
    echo ERROR: Migration or seeding failed
    echo -------------------------------------------
    pause
    exit /b %ERRORLEVEL%
)
echo.

echo === [6/7] Starting Laravel server...
start %APP_URL%
php artisan serve
echo.

echo === [7/7] Done! Laravel is running at: %APP_URL%
pause
