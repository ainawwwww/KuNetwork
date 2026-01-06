<?php

$hashedPassword = password_hash('123456', PASSWORD_BCRYPT);

echo $hashedPassword;
?>