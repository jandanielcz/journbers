<?php

if ($argc < 2) {
    printf('Usage: \'php hash.php <PASSWORD>\'');
}
printf('Password hash: %s', password_hash($argv[1], PASSWORD_BCRYPT));
