<?php
// Ici tu pourras connecter une API d'IA (OpenAI, etc.)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>IBIG IMMO BOT</title>
<style>
body{
    margin:0;
    font-family:system-ui,sans-serif;
    background:#f3f4f6;
}
.wrapper{
    height:100vh;
    display:flex;
    flex-direction:column;
}
.header{
    padding:10px 12px;
    background:#003c96;
    color:#fff;
    font-size:14px;
    font-weight:600;
}
.messages{
    flex:1;
    padding:10px;
    overflow-y:auto;
    font-size:13px;
}
.msg{
    margin-bottom:8px;
    max-width:85%;
    padding:8px 10px;
    border-radius:10px;
}
.msg.user{background:#003c96;color:#fff;margin-left:auto;}
.msg.bot{background:#e5e7eb;color:#111827;margin-right:auto;}
.form{
    display:flex;
    padding:8px;
    gap:6px;
    background:#fff;
}
input[type="text"]{
    flex:1;
    padding:6px 8px;
    border-radius:8px;
    border:1px solid #d1d5db;
    font-size:13px;
}
button{
    padding:6px 10px;
    border-radius:8px;
    border:none;
    background:#003c96;
    color:#fff;
    font-size:13px;
    cursor:pointer;
}
</style>
</head>
<body>
<div class="wrapper">
    <div class="header">IBIG IMMO BOT – Assistance rapide</div>
    <div class="messages" id="messages">
        <div class="msg bot">Bonjour 👋 Je suis l’assistant IBIG IMMO TRUST. Posez-moi vos questions sur nos offres, la gestion locative ou les biens disponibles.</div>
    </div>
    <form class="form" onsubmit="sendMsg(event)">
        <input type="text" id="inputMsg" placeholder="Votre question..." autocomplete="off">
        <button>Envoyer</button>
    </form>
</div>

<script>
function addMessage(text, who){
    const box=document.getElementById('messages');
    const div=document.createElement('div');
    div.className='msg '+who;
    div.textContent=text;
    box.appendChild(div);
    box.scrollTop=box.scrollHeight;
}

async function sendMsg(e){
    e.preventDefault();
    const inp=document.getElementById('inputMsg');
    const text=inp.value.trim();
    if(!text) return;
    addMessage(text,'user');
    inp.value='';

    // Ici: appel AJAX vers une API IA côté serveur
    // Pour l'instant, réponse fixe
    addMessage("Merci pour votre message. Un conseiller IBIG IMMO TRUST vous contactera rapidement. Vous pouvez aussi nous écrire sur WhatsApp.",'bot');
}
</script>
</body>
</html>
