email="victim@local.test"
swaks --to $email --server localhost:1025 --data @emails/prince.txt 
swaks --to $email --server localhost:1025 --data @emails/typosquatting.txt 
swaks --to $email --server localhost:1025 --from rromanovsky@outlook.com --header "From: Radim Romanovský <rromanovsky@outlook.com>" --body @emails/macro.txt --h-Subject 'Vyplnění dotazníku' --attach @emails/dotaznik.ods
