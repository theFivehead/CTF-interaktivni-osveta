<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="chat.css">
    <title>Chat - Tomáš</title>
</head>

<body>

<div class="phone-mockup">
    <div class="chat-header">
        <a href="index.php" class="back-btn">‹</a>
        <?php
        $uzivatel=array(array("avatar"=>"T","jmeno"=>"Tomáš 🧗‍♂️","status"=>"Online"),array("avatar"=>"avatars/horst.jpg","jmeno"=>"Horst Fuchs","status"=>"Offline"),array("avatar"=>"M","jmeno"=>"Máma","status"=>"Offline"));
        if(file_exists($uzivatel[$_GET['id']]["avatar"])){
            echo '<img class="avatar" src="'.$uzivatel[$_GET['id']]["avatar"].'">';
        } else {
            echo '<div class="avatar">'.$uzivatel[$_GET['id']]["avatar"].'</div>';
        }
        echo '
        <div class="contact-info">
            <span class="contact-name">'.$uzivatel[$_GET['id']]["jmeno"].'</span>
            <span class="contact-status">'.$uzivatel[$_GET['id']]["status"].'</span>
        </div>';
        ?>
    </div>

    <div class="chat-box" id="chat-box">
        <div class="date-divider"><span>Dnes</span></div>

        <?php
        // Načtení konverzace z textového souboru
        $chat_file = 'chats/'.$_GET['id'].'.txt';
        
        if (file_exists($chat_file)) {
            // Načte soubor do pole, ignoruje prázdné řádky
            $lines = file($chat_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            // Pro časovou simulaci (aby zprávy měly nějaký čas, začneme na 14:05 a budeme přidávat minutu)
            $time_minutes = 5; 
            
            foreach ($lines as $line) {
                // Rozdělí řádek na typ (0 nebo 1) a samotný text zprávy podle první dvojtečky
                $parts = explode(':', $line, 2);
                
                if (count($parts) == 2) {
                    $type = trim($parts[0]);
                    $message_text = htmlspecialchars(trim($parts[1])); // Ochrana proti XSS
                    
                    // Formátování času (např. 14:05)
                    $time_formatted = "14:" . str_pad($time_minutes, 2, "0", STR_PAD_LEFT);
                    
                    if ($type === '0') {
                        // Přijatá zpráva (Tomáš)
                        echo '<div class="message received">' . $message_text . '<div class="time">' . $time_formatted . '</div></div>';
                    } elseif ($type === '1') {
                        // Odeslaná zpráva (Klára)
                        echo '<div class="message sent">' . $message_text . '<div class="time">' . $time_formatted . '</div></div>';
                    }
                    
                    $time_minutes+=rand(1,6); // Zvýšení času pro další zprávu
                }
            }
        } else {
            echo '<p style="text-align:center; color:#888;">Soubor s konverzací (chat.txt) nebyl nalezen.</p>';
        }
        ?>

    </div>

    <div class="chat-input-area">
        <input type="text" class="chat-input" id="message-input" placeholder="Napište zprávu...">
        <button class="send-btn" id="send-btn">➤</button>
    </div>
</div>

<script>
    const chatBox = document.getElementById("chat-box");
    const inputField = document.getElementById("message-input");
    const sendBtn = document.getElementById("send-btn");

    window.onload = function() {
        chatBox.scrollTop = chatBox.scrollHeight;
    };

    function getCurrentTime() {
        return new Date().toLocaleTimeString('cs-CZ', { hour: '2-digit', minute: '2-digit' });
    }

    function sendMessage() {
        const text = inputField.value.trim();
        if (text === "") return;

        const klaraMsg = document.createElement('div');
        klaraMsg.className = 'message sent';
        klaraMsg.innerHTML = `${text}<div class="time">${getCurrentTime()}</div>`;
        chatBox.appendChild(klaraMsg);

        inputField.value = "";
        chatBox.scrollTop = chatBox.scrollHeight;

        // Odeslání zprávy do PHP skriptu pro uložení do souboru by se řešilo přes AJAX/Fetch zde,
        // momentálně zpráva zůstává jen v prohlížeči.

        setTimeout(() => {
            const tomasMsg = document.createElement('div');
            tomasMsg.className = 'message received';
            tomasMsg.innerHTML = `Hele, tady je ten odkaz, o kterém jsme se bavili: <br><a href="https://www.youtube.com/watch?v=PXqcHi2fkXI" target="_blank">https://www.youtube.com/watch?v=PXqcHi2fkXI</a><div class="time">${getCurrentTime()}</div>`;
            chatBox.appendChild(tomasMsg);
            
            chatBox.scrollTop = chatBox.scrollHeight;
        }, 30000); 
    }

    sendBtn.addEventListener("click", sendMessage);

    inputField.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            sendMessage();
        }
    });
</script>

</body>
</html>