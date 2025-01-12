<?php

namespace Pamutlabor\Module\TaskManager;

use Pamutlabor\Core\PDODatabase;
use Pamutlabor\Module\Layout\LayoutController;

class TaskManagerListController extends LayoutController
{
    protected $data = [
        'title' => 'Projektek'
    ];

    public function __construct() {
        $this->layout = new TaskManagerListView();
    }
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        $res = $this->loadData();
        $this->data = array_merge($this->data, ['projects' => $res]);
    }

    protected function loadData()
    {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare(
            '
                SELECT 
                    p.id AS projectId,
                    p.title AS projectTitle,
                    o.id AS ownerId,
                    o.name AS ownerName,
                    o.email AS ownerEmail,
                    s.name AS statusName
                FROM projects p
                LEFT JOIN project_owner_pivot pop 
                    ON  p.id = pop.project_id
                LEFT JOIN owners o 
                    ON pop.owner_id = o.id
                LEFT JOIN project_status_pivot psp
                    ON p.id = psp.project_id
                LEFT JOIN statuses s 
                    ON psp.status_id = s.id
            '
        );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [];
    }
}