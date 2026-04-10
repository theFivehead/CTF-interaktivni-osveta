$running = wsl --list --running

if ($running -eq $null -or $running -eq "") {
    Write-Output "WSL not running. Starting..."
    wsl
} else {
    Write-Output "WSL already running:"
    $running
}