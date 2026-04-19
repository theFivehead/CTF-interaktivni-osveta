# Check for wslhost to see if the background VM is running
$wslProcess = Get-Process -Name "wslhost" -ErrorAction SilentlyContinue

if (!$wslProcess) {
    # Use Start-Process to hide the window, while running an infinite sleep loop inside WSL
    Start-Process -FilePath "wsl.exe" -ArgumentList "-d debian --exec sh -c `"while true; do sleep 60; done`"" -WindowStyle Hidden
}