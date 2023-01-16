<?php
require("vendor/autoload.php");
$openapi = \OpenApi\scan('/home/sistemas/buonny/api_rhhealth/api');
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();