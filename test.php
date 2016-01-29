<?php


$url = "/Credential/awd";

echo preg_match("/^\/(Credential)(\/[^\/]*)?$/",$url) ? "Yes" : "No";
?>