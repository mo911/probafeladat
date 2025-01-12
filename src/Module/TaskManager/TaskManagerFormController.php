<?php

namespace Pamutlabor\Module\TaskManager;

use Pamutlabor\Core\PDODatabase;
use Pamutlabor\Module\Layout\LayoutController;
use Pamutlabor\Module\TaskManager\Model\Project;

class TaskManagerFormController extends LayoutController
{
    protected $data = [
        'title' => 'Projekt'
    ];

    protected $errors = [];

    protected function createOrUpdateProject($input) {
        if ($input['projectId'] > 0) {
            $oldObject = Project::load($input['projectId']);
            if($oldObject){
                $newObject = Project::update($input);
                (new TaskManagerModifyEvent())->handle($newObject, $oldObject);
            }
        } else {
            Project::save($input);
        }
    }

    public function __construct() {
        $this->layout = new TaskManagerFormView();
    }

    protected function validateForm($input): bool {
        $result = true;
        if(isset($input['projectId']) && !is_numeric($input['projectId'])){
            $result = false;
            $this->errors[] = 'Projekt id csak szám lehet';
        }
        if(!is_numeric($input['statusId'])){
            $result = false;
            $this->errors[] = 'Status Id csak szám lehet';
        }
        if(!is_numeric($input['ownerId'])){
            $result = false;
            $this->errors[] = 'Status Id csak szám lehet';
        }
        if(strlen($input['projectTitle']) == 0){
            $result = false;
            $this->errors[] = 'Title nem lehet üres';
        }
        if(strlen($input['projectDescription']) == 0){
            $result = false;
            $this->errors[] = 'Description nem lehet üres';
        }
        return $result;
    }
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = [
                'projectId' => $post['projectId'],
                'statusId' => $post['statusId'],
                'ownerId' => $post['ownerId'],
                'projectTitle' => $post['projectTitle'],
                'projectDescription' => $post['projectDescription']
            ];
            if($this->validateForm($input)){
                $this->createOrUpdateProject($input);
                header('Location: /');
                exit();
            }
        }
        $res = $this->loadData($variable['id'] ?? 0);
        $statuses = $this->loadStatuses();
        $owners = $this->loadOwners();
        $this->data = array_merge($this->data, ['project' => $res], ['statuses' => $statuses], ['owners' => $owners], ['errors' => $this->errors]);
    }

    protected function loadStatuses()
    {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("SELECT * FROM statuses");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function loadOwners(): array
    {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("SELECT * FROM owners");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function loadData(int $id = 0)
    {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare(
            '
                SELECT 
                    p.id AS projectId,
                    p.title AS projectTitle,
                    p.description AS projectDescription,
                    o.id AS ownerId,
                    o.name AS ownerName,
                    o.email AS ownerEmail,
                    s.name AS statusName,
                    s.key AS statusKey,
                    s.id AS statusId
                FROM projects p
                LEFT JOIN project_owner_pivot pop 
                    ON  p.id = pop.project_id
                LEFT JOIN owners o 
                    ON pop.owner_id = o.id
                LEFT JOIN project_status_pivot psp
                    ON p.id = psp.project_id
                LEFT JOIN statuses s 
                    ON psp.status_id = s.id
                WHERE p.id = :id
            '
        );
        $stmt->execute([
            ':id' => $id ?? 0
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?? [];
    }
}