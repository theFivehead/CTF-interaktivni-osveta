<?php
session_start();

// Simulace databáze - přednastavené jméno a heslo
$valid_username = "klara";
$valid_password = "6D1anAkáva2003"; // V reálu by tu byl hash hesla (password_hash)

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_username && $password === $valid_password && !isset($_SESSION['new_password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php"); // Přesměrování na chat
        exit;
    }
    else if(($username === $valid_username && $password === $_SESSION['new_password'] && isset($_SESSION['priklady_vypocteny']) && $_SESSION['priklady_vypocteny']) || (isset($_SESSION['splneno']) && $_SESSION['splneno'])){
        $_SESSION['splneno']=true;
    }
    else {
        $error = "Špatné uživatelské jméno nebo heslo.";
    }
}
?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přihlášení</title>
    <link rel="stylesheet" href="main.css">
<link rel="stylesheet" href="login.css">
</head>
<body>
<div class="box">
<?php
//pokud uživatel provede všechny úkoly a přihlásí se s novým heslem získá vlajku a odešle se email pro další úkol a zamkne se vytořením souboru z sha512(odeslat email)
    if(isset($_SESSION['splneno']) && $_SESSION['splneno']){ //pokud uživatel vypočítal příklady a přihlásil se s novým heslem tak získá vlajku a odešle se email pro další úkol a zamkne se vytořením souboru z sha512(odeslat email)
        $lock="047c6e49b828def68eae630d9ed9bdcc614070865988cd5e4b6efb6330bdd7ae99e5810e61740f41e5b5c00271dd2743800e1853354d6c3f897d540a0901c3b5";
        if(!file_exists($lock)){
            exec("swaks --to david.tvrdy00@gmail.com --server localhost:1025 --data @spear.txt "); //TODO
            file_put_contents($lock, "locked");
        }
        echo '<h2>Gratulujeme, získali jste vlajku!</h2>';
        echo '<h3 style="color:green;">flag{Sul>erS1ln3H3s\o}</h3>';
        
    }
    else{
         echo '        
            <form method="POST" action="">';
    if(isset($_SESSION['new_password']) && !$_SESSION['priklady_vypocteny']){ //pokud je heslo nastaveno tak uživatel vypočte x příkladu a získá vlajku
        if(!isset($_SESSION['vysledky']) || !isset($_SESSION['priklady'])){
            $_SESSION['vysledky']=array_fill(0, 6, 0);
                    for($i=0; $i<5; $i++){
            $a = rand(1, 50);
            $b = rand(1, 50);
            $op='';
            switch(rand(0, 3)){
                case 0: $_SESSION['vysledky'][$i]=$a+$b;$op='+'; break;
                case 1: $_SESSION['vysledky'][$i]=$a-$b;$op='-'; break;
                case 2: $_SESSION['vysledky'][$i]=$a*$b;$op='*'; break;
                case 3: $_SESSION['vysledky'][$i]=floor($a/$b);$op='/'; break;
            }
            $_SESSION['priklady'][$i] = "<p>$a $op $b = <input type='text' name='vysledek".$i."' placeholder='Výsledek' required> </p>";
        }
        }
        else{
            $_SESSION['priklady_vypocteny'] = true;
            for($i=0; $i<5; $i++){
                if(!isset($_POST["vysledek".$i]) || $_POST["vysledek".$i] != $_SESSION['vysledky'][$i]){
                    $_SESSION['priklady_vypocteny'] = false;
                    break;
                }
            }
            if($_SESSION['priklady_vypocteny']){
                header("Location: login.php"); // Přesměrování na přihlášení s novým heslem
                exit;
            }
            else{
                $spatne=true;
            }

        }

        echo '<h2>Vypočtěte 5 příkladu</h2><p>Dělení zaokrohlujte na celé čísla dolů.</p>';
        if(isset($spatne) && $spatne){
         echo '<p style="color:red;">Některé výsledky jsou špatné, zkuste to znovu.</p>';
        }
        for($i=0; $i<5; $i++){
            //echo $_SESSION['vysledky'][$i];
            echo $_SESSION['priklady'][$i];
        }
        echo '<button type="submit">Odeslat</button>';

    }
    else{
        echo '<h2>Přihlášení</h2>';
        if($error){
            echo '<div class="error">' . $error . '</div>';
        }
        if(!empty($_SESSION['new_password'])){
            $heslo_placeholder="Zadejte svoje nové heslo";
        }
        else{
            $heslo_placeholder="Heslo z videa";
        }
        echo '
            <input type="text" name="username" placeholder="Uživatelské jméno" required>
            <input type="password" name="password" placeholder="'.$heslo_placeholder.'" required>
            <button type="submit">Přihlásit se</button>
        ';
    }
    echo '</form>';
    }
    ?>
    </div>

</body>
</html>