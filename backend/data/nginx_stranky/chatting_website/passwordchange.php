

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Změna hesla</title>
    <link rel="stylesheet" href="main.css">
     <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="box">
        <h2>Změna hesla</h2>
        <p class="rules">
            Heslo musí mít: 
            <ol>
                <li>min. 10 znaků</li>
                <li>velké i malé písmeno</li>
                <li>číslo nebo speciální znak</li>
                <li>nesmí být ve slovníku známých hesel</li>
                <li><a href="https://jecas.cz/bezpecne-heslo#entropie">entropie</a> musí přesáhnout 60</li>
            </ol>
        </p>
        
        <?php if ($message): ?>
            <div class="msg"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="password" name="new_password" placeholder="Nové heslo" required>
            <input type="password" name="confirm_password" placeholder="Potvrzení hesla" required>
            <button type="submit">Změnit heslo</button>
        </form>
        
        <br>
        <a href="index.php" style="font-size: 12px; color: #007aff; text-decoration: none;">Zpět</a>
    </div>
    <script>
        // Po úspěšné změně hesla spustíme odpočítávání
        const countdownElement = document.getElementsByClassName('countdown')[0];
        if (countdownElement) {
            let countdown = 10;
            const interval = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = 'index.php?logout=1'; // Odhlásí uživatele a přesměruje na login
                }
            }, 1000);
        }
    </script>
</body>
</html>

<?php
session_start();

// Volitelná kontrola, jestli je uživatel přihlášený:
// if (!isset($_SESSION['logged_in'])) { header("Location: login.php"); exit; }

$message = "";
$dictionary = file('Pwdb_top-1000000.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Funkce pro výpočet entropie hesla
function calculateEntropy($password) {
    $pool = 0;
    if (preg_match('/[a-z]/', $password)) $pool += 26; // Malá písmena
    if (preg_match('/[A-Z]/', $password)) $pool += 26; // Velká písmena
    if (preg_match('/[0-9]/', $password)) $pool += 10; // Čísla
    if (preg_match('/[^a-zA-Z0-9]/', $password)) $pool += 32; // Speciální znaky (odhad)

    if ($pool === 0) return 0;
    return strlen($password) * log($pool, 2);
}
function logout(){
            unset($_SESSION['username']);
        unset($_SESSION['logged_in']);
        header("Location: login.php");
        exit;
}
if(isset($_SESSION['new_password'])){
logout();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'] ?? '';
    $errors = [];
    if (isset($_POST['confirm_password']) && $_POST['confirm_password'] != $new_password) {
        $errors[] = "Hesla se neshodují.";
    }
    else {
        // Kontrola hesla
    // 1. Délka alespoň 10 znaků
    if (strlen($new_password) < 10) {
        $errors[] = "Heslo musí mít alespoň 10 znaků.";
    }

    // 2. Alespoň jedno velké a malé písmeno
    if (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password)) {
        $errors[] = "Heslo musí obsahovat alespoň jedno velké a jedno malé písmeno.";
    }

    // 3. Nejméně jeden speciální znak nebo číslo
    if (!preg_match('/[0-9\W]/', $new_password)) {
        $errors[] = "Heslo musí obsahovat alespoň jedno číslo nebo speciální znak.";
    }

    // 4. Kontrola proti slovníku (převedeme na malá písmena pro lepší kontrolu)
    if (in_array(strtolower($new_password), $dictionary)) {
        $errors[] = "Toto heslo je příliš běžné (nachází se ve slovníku).";
    }

    // 5. Entropie alespoň 60
    $entropy = calculateEntropy($new_password);
    if ($entropy <= 60) {
        $errors[] = "Heslo je příliš slabé (Entropie: " . round($entropy) . " / Požadováno: 60). Přidejte speciální znaky nebo délku.";
    }
    }
    if (empty($errors)) {
        // V reálné aplikaci bychom heslo uložili: $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $message = "<p style='color:green;'>Heslo bylo úspěšně změněno! (Entropie: " . round($entropy) . ")</p>
                    <p style='color:green;'>Za <span class='countdown'>10</span>s budete odhlášeni. Znovu se přihlašte a získejte flag</p>";
        /*$password_file=base64_encode(random_bytes(8))+'.txt';
                    fwrite($password_file, $new_password); // Uložíme nové heslo do textového souboru (pro účely CTF)*/
        $_SESSION['new_password'] = $new_password; // Uložíme nové heslo do session, aby se dalo zkontrolovat při přihlášení
        sleep(10); //odhlásí uživatele po 10 sekundách
        logout();

    } else {
        $message = "<p style='color:red;'>" . implode("<br>", $errors) . "</p>";
    }

}
?>