<?php


namespace Journbers\Data;


use Journbers\Credentials\InvalidCredentialsException;
use Journbers\Data;

class CredentialsStore extends Data
{
    public function validateCredentials($name, $pass)
    {
        $db = $this->database();
        $stmt = $db->prepare("
            select users.id, users.fullname, credentials.provided_groups, credentials.pass
            from credentials 
            join users
              on users.id = credentials.user
            where name = :name
        ");
        $stmt->execute([
            'name' => $name
        ]);

        $res = $stmt->fetch();

        usleep(1000000 * rand(0.5, 3));

        if ($res === false) {
            throw new InvalidCredentialsException('Wrong username or password.');
        }

        if (!password_verify($pass, $res['pass'])) {
            throw new InvalidCredentialsException('Wrong username or password.');
        }

        return [
            'id' => $res['id'],
            'fullName' => $res['fullname'],
            'providedRoles' => explode(' ', $res['provided_groups'])
        ];
    }
}
