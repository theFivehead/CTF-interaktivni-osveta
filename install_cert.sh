# Path to your PEM certificate
$certPath = "C:\Users\windows\Desktop\cert.pem"

# Import the PEM certificate
$cert = New-Object System.Security.Cryptography.X509Certificates.X509Certificate2
$cert.Import($certPath)

# Open the Local Machine Trusted Root Certification Authorities store
$store = New-Object System.Security.Cryptography.X509Certificates.X509Store `
    "Root", "LocalMachine", "Lets Encrypt"
$store.Open("ReadWrite")

# Add the certificate
$store.Add($cert)
$store.Close()

Write-Output "Certificate installed as Trusted Root successfully!"