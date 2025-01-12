<?php

namespace Pamutlabor\Module\TaskManager\Model;

use Pamutlabor\Core\PamutlaborModel as Model;
use Pamutlabor\Core\PDODatabase;

class Project extends Model
{

    public static function save(array $data) {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("INSERT INTO projects (title, description) VALUES (:title, :description)");
        $stmt->execute([
            ':title' => $data['projectTitle'],
            ':description' => $data['projectDescription']
        ]);
        $projectId = $database->lastInsertId();
        if ($projectId > 0) {
            Project::addRelations($projectId, $data['ownerId'], $data['statusId']);
        }
        return $projectId;
    }

    public static function addRelations(int $projectId, int $ownerId, int $statusId) {
        Project::deleteRelations($projectId);
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("INSERT INTO project_owner_pivot (project_id, owner_id) VALUES (:projectId, :ownerId)");
        $stmt->execute([
            ':projectId' => $projectId,
            ':ownerId' => $ownerId
        ]);
        $stmt = $database->prepare("INSERT INTO project_status_pivot (project_id, status_id) VALUES (:projectId, :statusId)");
        $stmt->execute([
            ':projectId' => $projectId,
            ':statusId' => $statusId
        ]);

    }

    public static function deleteRelations(int $projectId) {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("DELETE FROM project_owner_pivot WHERE project_id = :projectId");
        $stmt->execute([':projectId' => $projectId]);

        $stmt = $database->prepare("DELETE FROM project_status_pivot WHERE project_id = :projectId");
        $stmt->execute([':projectId' => $projectId]);
    }

    public static function update(array $data) {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("UPDATE projects SET title = :title, description = :description WHERE id = :id");
        $stmt->execute([
            ':id' => $data['projectId'],
            ':title' => $data['projectTitle'],
            ':description' => $data['projectDescription']
        ]);

        $projectId = $data['projectId'];
        if ($projectId > 0) {
            Project::addRelations($projectId, $data['ownerId'], $data['statusId']);
        }
        return $projectId;
    }

    public static function delete(int $id) {
        Project::deleteRelations($id);
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->execute([':id' => $id]);

        Project::deleteRelations($id);

        return $stmt->rowCount();
    }

    public static function load(int $id) {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("SELECT * FROM projects WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}