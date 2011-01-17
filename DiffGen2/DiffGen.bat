@echo off
:DiffGen
cls
@title Welcome to DiffGen
php "DiffGen.php"
echo -------------------------------------------------------------------------------
set /p choice=Press enter to restart:
goto DiffGen
