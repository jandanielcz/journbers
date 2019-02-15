<?php

if ($argc < 2) {
    printf('Usage: \'php hash.php <PASSWORD>\'');
}
printf('Password \'%s\' hash: %s', $argv[1], password_hash($argv[1], PASSWORD_BCRYPT));
