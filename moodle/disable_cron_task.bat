@echo off
setlocal

set "TASK_NAME=\Moodle Cron"

rem Disable scheduled task that runs Moodle cron
schtasks /Query /TN "%TASK_NAME%" >nul 2>&1
if errorlevel 1 (
  echo [ERROR] Scheduled task not found: "%TASK_NAME%"
  echo If the task name is different, edit TASK_NAME in this file.
  exit /b 1
)

rem Stop it if it is currently running (ignore errors)
schtasks /End /TN "%TASK_NAME%" >nul 2>&1

schtasks /Change /TN "%TASK_NAME%" /Disable
if errorlevel 1 (
  echo [ERROR] Failed to disable "%TASK_NAME%".
  echo Try: right-click this file ^> Run as administrator.
  exit /b 1
)

echo [OK] Disabled: "%TASK_NAME%"
exit /b 0

