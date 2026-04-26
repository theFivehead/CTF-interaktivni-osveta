./install_cert.ps1
#smazat historii, aby se nezobrazovaly příkazy s citlivými informacemi
set-content -Path (Get-PSReadLineOption).HistorySavePath -value $null