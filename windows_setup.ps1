$Server = "192.168.100.4"
Set-DnsClientServerAddress -InterfaceAlias "Ethernet" -ServerAddresses ($Server)
scp ctf@Server:/home/ctf/cert.pem %TEMP%\cert.pem
Import-Certificate `-FilePath %TEMP%\cert.pem -CertStoreLocation Cert:\LocalMachine\Root`
Remove-Item %TEMP%\cert.pem