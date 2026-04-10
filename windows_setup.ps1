
$username = "soc"
$password = ConvertTo-SecureString "heslo123456789" -AsPlainText -Force

# 1. Vytvoření uživatele
New-LocalUser -Name $username `
    -Password $password `
    -FullName "SOC Restricted User" `
    -Description "Pouze prohlížeč" `
    -PasswordNeverExpires

Add-LocalGroupMember -Group "Users" -Member $username

# 2. Odebrání z privilegovaných skupin
$groupsToRemove = @("Administrators", "Remote Desktop Users", "Hyper-V Administrators")

foreach ($group in $groupsToRemove) {
    Remove-LocalGroupMember -Group $group -Member $username -ErrorAction SilentlyContinue
}

# 3. BLOKACE nástrojů (NTFS deny)
$blockedApps = @(
    "C:\Windows\System32\cmd.exe",
    "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe",
    "C:\Windows\System32\wsl.exe",
    "C:\Windows\System32\bash.exe"
)

foreach ($app in $blockedApps) {
    if (Test-Path $app) {
        icacls $app /deny "${env:COMPUTERNAME}\$username:(RX)"
    }
}

# 4. Povolení pouze vlastního profilu (základní jistota)
$userProfile = "C:\Users\$username"
if (!(Test-Path $userProfile)) {
    New-Item -ItemType Directory -Path $userProfile -Force
}

# 5. Zakázání přístupu do jiných profilů (příklad)
$otherProfiles = Get-ChildItem "C:\Users" | Where-Object { $_.Name -ne $username }

foreach ($profile in $otherProfiles) {
    icacls $profile.FullName /deny "${env:COMPUTERNAME}\$username:(OI)(CI)RX"
}

Write-Host "Omezený uživatel $username vytvořen."

./install_cert.ps1