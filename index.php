<?php
include 'Cobiss.php';
header('Content-Type: text/html; charset=utf-8');
if (!isset($_GET['q'])) die('[]');
$query = htmlspecialchars($_GET['q']);

echo json_encode(Cobiss::parse((new Cobiss('Ptuj'))->search($query)), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);