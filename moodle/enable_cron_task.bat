@echo off
setlocal

set "TASK_NAME=\Moodle Cron"

rem Enable scheduled task that runs Moodle cron
schtasks /Query /TN "%TASK_NAME%" >nul 2>&1
if errorlevel 1 (
  echo [ERROR] Scheduled task not found: "%TASK_NAME%"
  echo If the task name is different, edit TASK_NAME in this file.
  exit /b 1
)

schtasks /Change /TN "%TASK_NAME%" /Enable
if errorlevel 1 (
  echo [ERROR] Failed to enable "%TASK_NAME%".
  echo Try: right-click this file ^> Run as administrator.
  exit /b 1
)

rem Optionally start it once right away (ignore errors)
schtasks /Run /TN "%TASK_NAME%" >nul 2>&1

echo [OK] Enabled: "%TASK_NAME%"
echo [OK] Triggered a run (if allowed).
exit /b 0

