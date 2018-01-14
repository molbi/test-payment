<?php


use OndraKoupil\Csob\Client;
use OndraKoupil\Csob\Config;
use OndraKoupil\Csob\GatewayUrl;
use OndraKoupil\Csob\Payment;

if (isset($_POST['submit'])) {

    require("vendor/autoload.php");
    $config = new Config(
        "A3051azixU",
        "keys/rsa_A3051azixU.key",
        "keys/bank.pub",
        "Obchůdek",

        // Adresa, kam se mají zákazníci vracet poté, co zaplatí
        "http://localhost/testCSOB/return.php",

        // URL adresa API - výchozí je adresa testovacího (integračního) prostředí,
        // až budete připraveni přepnout se na ostré rozhraní, sem zadáte
        // adresu ostrého API. Nezapomeňte také na ostrý veřejný klíč banky.
        GatewayUrl::TEST_LATEST
    );

    $client = new Client($config);

    $payment = new Payment(mktime());
    $payment->addCartItem($_POST['text'], 1, $_POST['cena']*100);

    $response = $client->paymentInit($payment);

    $payId = $payment->getPayId();
    $payId = $response["payId"];

    $url = $client->getPaymentProcessUrl($payment);
    header("Location:" . $url);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ČSOB platba</title>
</head>
<body>
<form method="post">
    <input type="text" name="text" placeholder="Běžný text">
    <input type="text" name="cena" placeholder="">
    <button type="submit" name="submit">Odešli</button>
</form>
</body>
</html>
