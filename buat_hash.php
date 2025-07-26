<?php
$password_asli = 'admin123';
$hash_baru = password_hash($password_asli, PASSWORD_DEFAULT);
echo $hash_baru;
?>