# 1. Get the active network interface
$adapter = Get-NetAdapter | Where-Object { $_.Status -eq "Up" }

# 2. Define your DNS servers (Cloudflare example)
$dnsServers = ("1.1.1.1", "1.0.0.1")

# 3. Apply the change
if ($adapter) {
    Write-Host "Changing DNS for: $($adapter.Name)" -ForegroundColor Cyan
    Set-DnsClientServerAddress -InterfaceIndex $adapter.ifIndex -ServerAddresses $dnsServers
    Write-Host "DNS successfully updated." -ForegroundColor Green
} else {
    Write-Warning "No active network adapter found."
}