<?php

function sendMailIBIG($to, $subject, $body){
    $headers  = "From: IBIG IMMO TRUST <contact@ibigimmotrust.com>\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    mail($to, $subject, $body, $headers);
}
