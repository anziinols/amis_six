@echo off
echo Force Migration for workplan_activities table
echo ============================================
echo.

REM Try different PHP paths commonly used in XAMPP
set PHP_PATH=""

REM Check for XAMPP PHP in common locations
if exist "C:\xampp\php\php.exe" (
    set PHP_PATH="C:\xampp\php\php.exe"
    echo Found PHP at C:\xampp\php\php.exe
) else if exist "C:\xampp7\php\php.exe" (
    set PHP_PATH="C:\xampp7\php\php.exe"
    echo Found PHP at C:\xampp7\php\php.exe
) else if exist "C:\xampp8\php\php.exe" (
    set PHP_PATH="C:\xampp8\php\php.exe"
    echo Found PHP at C:\xampp8\php\php.exe
) else (
    echo PHP not found in common XAMPP locations.
    echo Please run the migration manually using one of these methods:
    echo.
    echo Method 1: Using PHP directly
    echo   php force_migrate_workplan_activities.php
    echo.
    echo Method 2: Using phpMyAdmin
    echo   Import and execute force_migrate_workplan_activities.sql
    echo.
    echo Method 3: Using MySQL command line
    echo   mysql -u root -p amis_six_db ^< force_migrate_workplan_activities.sql
    echo.
    pause
    exit /b 1
)

echo.
echo Running force migration...
echo.

REM Run the PHP migration script
%PHP_PATH% force_migrate_workplan_activities.php

echo.
echo Migration completed. Check the output above for any errors.
echo.
pause
