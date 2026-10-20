
<?php
ini_set("session.use_strict_mode", "1");

$isHttps = isset($_SERVER["HTTPS"]) &&
           $_SERVER["HTTPS"] !== "off";

session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/",
    "secure" => $isHttps,
    "httponly" => true,
    "samesite" => "Lax"
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
