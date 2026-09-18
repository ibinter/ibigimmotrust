<?php
// Config Meta (à remplir)
$WA_TOKEN   = "TON_TOKEN_META";
$WA_PHONEID = "TON_PHONE_ID";

function wa_send_text($phone, $message){
    global $WA_TOKEN,$WA_PHONEID;
    if(!$phone || !$message) return;

    $url = "https://graph.facebook.com/v18.0/$WA_PHONEID/messages";
    $payload = [
        "messaging_product"=>"whatsapp",
        "to"=>$phone,
        "type"=>"text",
        "text"=>["body"=>$message]
    ];

    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_HTTPHEADER,[
        "Authorization: Bearer $WA_TOKEN",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($payload));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $res = curl_exec($ch);
    curl_close($ch);
}

function sendWhatsAppToClient($rdv_id,$message){
    global $pdo;
    $st=$pdo->prepare("SELECT phone FROM rdv_requests WHERE id=?");
    $st->execute([$rdv_id]);
    $r=$st->fetch(PDO::FETCH_ASSOC);
    if($r && $r['phone']) wa_send_text($r['phone'],$message);
}

function sendWhatsAppToAgent($phone,$message){
    if($phone) wa_send_text($phone,$message);
}
