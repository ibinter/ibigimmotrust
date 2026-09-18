<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>IBIG IMMO BOT</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{margin:0;font-family:system-ui,Arial;background:#f1f5f9;}
#chatbox{height:100vh;display:flex;flex-direction:column;}
#messages{flex:1;overflow-y:auto;padding:12px;}
.msg{padding:10px 14px;border-radius:10px;margin-bottom:10px;max-width:80%;white-space:pre-line;font-size:14px;}
.bot{background:#003c96;color:#fff;align-self:flex-start;}
.user{background:#e2e8f0;color:#111;align-self:flex-end;}
#form{padding:12px;background:#fff;display:flex;gap:8px;border-top:1px solid #e5e7eb;}
#form input{flex:1;padding:10px;border-radius:8px;border:1px solid #cbd5e1;font-size:14px;}
#form button{background:#003c96;color:#fff;border:none;border-radius:8px;padding:10px 16px;font-size:14px;cursor:pointer;}
</style>
</head>
<body>

<div id="chatbox">
  <div id="messages"></div>
  <form id="form">
    <input type="text" id="input" placeholder="Écrivez ici..." autocomplete="off">
    <button type="submit">Envoyer</button>
  </form>
</div>

<script>
const messages = document.getElementById("messages");
const input    = document.getElementById("input");
const form     = document.getElementById("form");

function addMessage(text, cls){
  const div = document.createElement("div");
  div.className = "msg " + cls;
  div.textContent = text;
  messages.appendChild(div);
  messages.scrollTop = messages.scrollHeight;
}

addMessage(
  "👋 Bonjour et bienvenue chez IBIG IMMO TRUST.\nDites-moi en quoi je peux vous aider :\n\n• Achat ⌁ Vente ⌁ Location\n• Gestion locative\n• Construction et BTP\n• Chantier inachevé\n• Investissement immobilier\n\nJe suis disponible 24/7 😊",
  "bot"
);

form.addEventListener("submit", async (e)=>{
  e.preventDefault();
  const text = input.value.trim();
  if (!text) return;

  addMessage(text, "user");
  input.value = "";
  input.focus();

  try{
    const res = await fetch("/bot/bot_api.php", {
      method:"POST",
      headers:{ "Content-Type":"application/json" },
      body:JSON.stringify({ message: text })
    });

    const data = await res.json().catch(()=>null);
    if (data && data.reply){
      addMessage(data.reply, "bot");
    } else {
      addMessage("❌ Réponse invalide reçue du serveur.", "bot");
    }

  }catch(err){
    addMessage("❌ Erreur de connexion au serveur.", "bot");
  }
});
</script>

</body>
</html>
