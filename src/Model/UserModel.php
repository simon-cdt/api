<?php

namespace Simoncdt\Api\Model;

class UserModel
{
    public int $id;

    public string $pseudo;

    public string $mdp;

    public int $admin;

    public string $token;

    public function __construct(array $init = [])
    {
        $this->id = $init['id'] ?? -1;
        $this->pseudo = $init['pseudo'] ?? "";
        $this->mdp = $init['mdp'] ?? "";
        $this->admin = 0;
        $this->token = $init['token'] ?? "";
    }

    public static function selectAll(): array
    {
        $query =
            "SELECT id, pseudo, mdp, admin, token FROM utilisateur";

        $param = [];

        $statement = DataBase::getDB()->run($query);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, __CLASS__);
        return $statement->fetchAll();
    }

    public static function selectById($id): UserModel|false
    {
        $query = "SELECT id, pseudo, mdp, admin, token FROM utilisateur WHERE id = :id";

        $param = [
            ':id' => $id
        ];

        $statement = DataBase::getDB()->run($query, $param);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, __CLASS__);
        return $statement->fetch();
    }

    public function insertUser()
    {
        $token = $this->generateToken(100);

        $mdp = password_hash($this->mdp, PASSWORD_BCRYPT);

        $query = "INSERT INTO utilisateur(pseudo, mdp, token, admin) VALUES (:pseudo, :mdp, :token, 0)";

        $param = [
            ':pseudo' => $this->pseudo,
            ':mdp' => $mdp,
            ':token' => $token,
        ];

        DataBase::getDB()->run($query, $param);
        return Database::getDB()->lastInsertId();
    }

    function generateToken($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }

        return $token;
    }

    public function validToken($id, $token)
    {
        $user = UserModel::selectById($id);

        if ($token == $user->token) {
            return true;
        } else {
            return false;
        }
    }
}
