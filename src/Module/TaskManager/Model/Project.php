<?php

namespace Pamutlabor\Module\TaskManager\Model;

use Pamutlabor\Core\PamutlaborModel as Model;

class Project extends Model
{

    public function __construct(
        int $id,
        string $title,
        string $description
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    public static function save(array $data) {
        if($this->id > 0){
            throw new \Exception('THIS_PROJECT_CONTAINS_ID_IS_EXISTS');
        }
        $stmt = $this->database->prepare("INSERT INTO projects (title, description) VALUES (:title, :description)");
        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description']
        ]);
        return $this->database->lastInsertId();
    }

    public static function update(array $data) {
        $stmt = $this->database->prepare("UPDATE projects SET title = :title, description = :description WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':description' => $description
        ]);

        return $stmt->rowCount();
    }

    public delete(int $id) {
        $stmt = $this->database->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount();
    }

    public static function load(int $id) {
        $stmt = $this->database->prepare("SELECT * FROM contact_messages WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}