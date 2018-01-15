<?php
/**
 * Created by PhpStorm.
 * User: KralLik
 * Date: 14.01.2018
 * Time: 19:46
 */

use OndraKoupil\Csob\Client;
use OndraKoupil\Csob\Config;
use OndraKoupil\Csob\GatewayUrl;

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
$response = $client->receiveReturningCustomer();

if ($response["paymentStatus"] == 7) {
    // nebo také 4, záleží na nastavení closePayment
    echo "Platba proběhla, děkujeme za nákup.";

} else {
    echo "Něco se pokazilo, sakra...";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ČSOB platba</title>
</head>
<body>


</form>
</body>
</html>
