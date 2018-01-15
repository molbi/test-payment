<?php

require("vendor/autoload.php");

use OndraKoupil\Csob\Client;
use OndraKoupil\Csob\Config;
use OndraKoupil\Csob\GatewayUrl;
use OndraKoupil\Csob\Payment;
use Nette\Neon\Neon;

# load neon config file
$config = Neon::decode(file_get_contents('config/app.neon'));

if (isset($_POST['submit'])) {
    if ($_POST['text'] != "" && $_POST['cena'] != "") {
        $gatewayConfig = new Config(
            $config['gateway']['merchant_id'],
            $config['gateway']['private_public_key_path'],
            $config['gateway']['public_bank_key_path'],
            $config['gateway']['shop_title'],
            // Adresa, kam se mají zákazníci vracet poté, co zaplatí
            $config['gateway']['return_path'],
            // URL adresa API - výchozí je adresa testovacího (integračního) prostředí,
            // až budete připraveni přepnout se na ostré rozhraní, sem zadáte
            // adresu ostrého API. Nezapomeňte také na ostrý veřejný klíč banky.
            !$config['gateway']['production'] ? GatewayUrl::TEST_LATEST : GatewayUrl::PRODUCTION_LATEST
        );

        $client = new Client($gatewayConfig);

        $payment = new Payment(time());
        $payment->addCartItem(htmlspecialchars($_POST['text']), 1, $_POST['cena'] * 100);

        $response = $client->paymentInit($payment);

        $payId = $payment->getPayId();
        $payId = $response["payId"];

        $url = $client->getPaymentProcessUrl($payment);
        header("Location:" . $url);
    } else {
        echo "Prosím vyplňte název i cenu položky.";

    }
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
    Název položky: <input type="text" name="text" placeholder="Běžný text"> <br>
    Cena položky: <input type="number" name="cena" placeholder="">  <br>
    <button type="submit" name="submit">Zaplatit</button>
</form>
</body>
</html>
