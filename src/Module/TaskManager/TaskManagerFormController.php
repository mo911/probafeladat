<?php

namespace Pamutlabor\Module\TaskManager;

use Pamutlabor\Core\PDODatabase;
use Pamutlabor\Module\Layout\LayoutController;
use Pamutlabor\Module\TaskManager\Model\Owner;
use Pamutlabor\Module\TaskManager\Model\Project;

class TaskManagerFormController extends LayoutController
{
    protected $data = [
        'title' => 'Projekt'
    ];

    protected function createOrUpdateOwner($data): int {
        if ($data['ownerId'] > 0) {
            $res = Owner::load($data['ownerId']);
            if($res) {
                Owner::update([
                    'id' => $data['ownerId'],
                    'name' => $data['ownerName'],
                    'email' => $data['ownerEmail']
                ]);
            }
        } else{
            Owner::save([
                'name' => $data['ownerName'],
                'email' => $data['ownerEmail']
            ]);
        }
    }

    protected function createOrUpdateProject($data) {
        if ($data['projectId'] > 0) {
            $res = Project::load($data['projectId']);
            if($res){
                Project::update([
                    
                ]);
            }
        }
    }

    public function __construct() {
        $this->layout = new TaskManagerFormView();
    }
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createOrUpdateOwner($post);
            $this->createOrUpdateProject($post);
        }
        $res = $this->loadData($variable['id'] ?? 0);
        $statuses = $this->loadStatuses();
        $this->data = array_merge($this->data, ['project' => $res], ['statuses' => $statuses]);
    }

    protected function loadStatuses()
    {
        $database = (new PDODatabase())->getConnection();
        $stmt = $database->prepare("SELECT * FROM statuses");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function loadData(int $id = 0)
    {
        $database = (new PDODatabase())->getConnection();
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