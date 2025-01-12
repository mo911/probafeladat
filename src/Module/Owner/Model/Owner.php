<?php

namespace Pamutlabor\Module\Owner\Model;

use Pamutlabor\Core\PamutlaborModel as Model;
use Pamutlabor\Core\PDODatabase;

class Owner extends Model
{
    public static function save(array $data) {
        if(isset($data['id']) && $data['id'] > 0){
            throw new \Exception('ID_CAN_NOT_HAVE_VALUE');
        }
        $database = (new PDODatabase())->getConnection();
        $stmt = $database->prepare("INSERT INTO owners (name, email) VALUES (:name, :email)");
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email']
        ]);
        return $database->lastInsertId();
    }

    public  static function update(array $data) {
        $database = (new PDODatabase())->getConnection();
        $stmt = $database->prepare("UPDATE owners SET name = :name, email = :email WHERE id = :id");
        $stmt->execute([
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':email' => $data['email']
        ]);

        return $stmt->rowCount();
    }

    public static function delete(int $id) {
        $database = (new PDODatabase())->getConnection();
        $stmt = $database->prepare("DELETE FROM owners WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount();
    }

    public static function load(int $id) {
        $database = (new PDODatabase())->getConnection();
        $stmt = $database->prepare("SELECT * FROM owners WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}