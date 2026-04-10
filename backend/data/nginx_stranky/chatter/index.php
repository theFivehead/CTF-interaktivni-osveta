<?php
session_start();
if (isset($_GET['logout'])) {
    unset($_SESSION['username']);
    unset($_SESSION['logged_in']);
    header("Location: login.php");
    exit;
    //session_destroy();
}

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
        header("Location: login.php");
    exit;
}
else if(isset($_SESSION['new_password'])){//logout
        unset($_SESSION['username']);
        unset($_SESSION['logged_in']);
        header("Location: login.php");
        exit;
}

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="chat.css">
    <link rel="stylesheet" href="main.css">
    <title>Chat - Tomáš</title>
</head>

<body>
<div class="phone-mockup">
    <div class="header">
    <h2><a href="passwordchange.php">Restartovat heslo</a></h2>
    <div class="max"></div>
    <h2><a href="index.php?logout=1">Odhlásit se</a></h2>
        </div>
        <h1>chaty</h1>
        <div id="chats">
            <a href="chat.php?id=0" class="chat-link">
                <div class="chat-wrapper">
                <div class="chat-item">
                    <div class="avatar">T</div>
                    <div class="chat-info">
                        <h3>Tomáš 🧗‍♂️</h3>
                        <p>Nejlepší kamarád</p>
                    </div>
                </div>
                </div>
            </a>
            <a href="chat.php?id=1" class="chat-link">
                <div class="chat-wrapper">
                <div class="chat-item">
                    <img class="avatar" src="avatars/horst.jpg" alt="Horst Fuchs">
                    <div class="chat-info">
                        <h3>Horst Fuchs</h3>
                        <p>marketing</p>
                    </div>
                </div>
                </div>
            </a>
            <a href="chat.php?id=2" class="chat-link">
                <div class="chat-wrapper">
                <div class="chat-item">
                    <div class="avatar">M</div>
                    <div class="chat-info">
                        <h3>Máma</h3>
                        <p>Super máma</p>
                    </div>
                </div>
                </div>
            </a>
            <a href="chat.php?id=3" class="chat-link">
                <div class="chat-wrapper">
                <div class="chat-item">
                    <div class="avatar">T</div>
                    <div class="chat-info">
                        <h3>Táta</h3>
                        <p>Nejlepší táta</p>
                    </div>
                </div>
                </div>
            </a>
            <a href="chat.php?id=4" class="chat-link">
                <div class="chat-wrapper">
                <div class="chat-item">
                    <div class="avatar">L</div>
                    <div class="chat-info">
                        <h3>Lucie</h3>
                        <p>Kamoška</p>
                    </div>
                </div>
                </div>
            </a>
            <a href="chat.php?id=5" class="chat-link">
                <div class="chat-wrapper">
                <div class="chat-item">
                    <div class="avatar">I</div>
                    <div class="chat-info">
                        <h3>idk</h3>
                        <p>sus člověk</p>
                    </div>
                </div>
                </div>
            </a>

        </div>
</div>
</body>
</html>