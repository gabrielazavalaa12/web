<?php
$password = "admin123";
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "<h3>Nuevo hash generado:</h3>";
echo "<pre>$hash</pre>";
?>
