<?php


namespace Journbers\Data;


use Journbers\Credentials\InvalidCredentialsException;
use Journbers\Data;
use Tracy\Debugger;

class CredentialsStore extends Data
{
    public function validateCredentials($name, $pass)
    {
        $db = $this->db();
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

        Debugger::barDump(password_hash('Kroupa', PASSWORD_BCRYPT));
        Debugger::barDump($res);
        Debugger::barDump(password_verify($pass, $res['pass']));
        Debugger::barDump($pass);

        if ($res === false) {
            throw new InvalidCredentialsException('Wrong username.');
        }

        if (!password_verify($pass, $res['pass'])) {
            throw new InvalidCredentialsException('Wrong password.');
        }

        return [
            'id' => $res['id'],
            'fullName' => $res['fullname'],
            'providedRoles' => explode(' ', $res['provided_groups'])
        ];
    }
}