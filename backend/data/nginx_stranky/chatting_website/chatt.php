
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="chat.css">
    <title>Chat - Klára</title>
</head>

<body>

<div class="phone-mockup">
    <div class="chat-header">
        <a href="index.php" class="back-btn">‹</a>
        <div class="avatar" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">K</div>
        <div class="contact-info">
            <span class="contact-name">Klára 💫</span>
            <span class="contact-status">Online</span>
        </div>
    </div>

    <div class="chat-box" id="chat-box">
        <div class="date-divider"><span>Dnes</span></div>

        <?php
        // Načtení stejné konverzace z textového souboru
        $chat_file = 'chats/1.txt';
        
        if (file_exists($chat_file)) {
            $lines = file($chat_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $time_minutes = 5; 
            
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                
                if (count($parts) == 2) {
                    $type = trim($parts[0]);
                    $message_text = htmlspecialchars(trim($parts[1])); 
                    
                    $time_formatted = "14:" . str_pad($time_minutes, 2, "0", STR_PAD_LEFT);
                    
                    // PROHOZENÁ LOGIKA: 0 je zpráva od Tomáše (odeslaná), 1 je od Kláry (přijatá)
                    if ($type === '0') {
                        // Odeslaná zpráva (Tomáš)
                        echo '<div class="message sent">' . $message_text . '<div class="time">' . $time_formatted . '</div></div>';
                    } elseif ($type === '1') {
                        // Přijatá zpráva (Klára)
                        echo '<div class="message received">' . $message_text . '<div class="time">' . $time_formatted . '</div></div>';
                    }
                    
                    $time_minutes++;
                }
            }
        } else {
            echo '<p style="text-align:center; color:#888;">Soubor s konverzací (chat.txt) nebyl nalezen.</p>';
        }
        ?>

    </div>

    <div class="chat-input-area">
        <input type="text" class="chat-input" id="message-input" placeholder="Napište zprávu Kláře...">
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

    // Funkce pro odeslání zprávy (teď ji odesíláš ty jako Tomáš)
    function sendMessage() {
        const text = inputField.value.trim();
        if (text === "") return;

        // Vložení tvé zprávy (Tomáš)
        const tomasMsg = document.createElement('div');
        tomasMsg.className = 'message sent';
        tomasMsg.innerHTML = `${text}<div class="time">${getCurrentTime()}</div>`;
        chatBox.appendChild(tomasMsg);

        inputField.value = "";
        chatBox.scrollTop = chatBox.scrollHeight;

        // Automatická odpověď od Kláry s videem za 30 sekund
        setTimeout(() => {
            const klaraMsg = document.createElement('div');
            klaraMsg.className = 'message received';
            klaraMsg.innerHTML = `Hele, tady je ten odkaz, o kterém jsme se bavili: <br><a href="https://www.youtube.com/watch?v=PXqcHi2fkXI" target="_blank">https://www.youtube.com/watch?v=PXqcHi2fkXI</a><div class="time">${getCurrentTime()}</div>`;
            chatBox.appendChild(klaraMsg);
            
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