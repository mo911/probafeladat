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

    protected function createOrUpdateProject($data) {
        $input = [
            'projectId' => $data['projectId'],
            'statusId' => $data['statusId'],
            'ownerId' => $data['ownerId'],
            'projectTitle' => $data['projectTitle'],
            'projectDescription' => $data['projectDescription']
        ];
        if ($input['projectId'] > 0) {
            $res = Project::load($data['projectId']);
            if($res){
                Project::update($input);
            }
        } else {
            Project::save($input);
        }
    }

    public function __construct() {
        $this->layout = new TaskManagerFormView();
    }

    protected function validateForm(): bool {
        return true;
    }
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($this->validateForm()){
                $this->createOrUpdateProject($post);
                header('Location: /');
                exit();
            }
        }
        $res = $this->loadData($variable['id'] ?? 0);
        $statuses = $this->loadStatuses();
        $owners = $this->loadOwners();
        $this->data = array_merge($this->data, ['project' => $res], ['statuses' => $statuses], ['owners' => $owners]);
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