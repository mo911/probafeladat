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

    const PAGE_LIMIT = 10;
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        $selectedStatus = 0;
        $pageNumber = 1;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedStatus = $post['selectedStatus'] ?? 0;
            $pageNumber = $post['pageNumber'] ?? 1;
            if($pageNumber < 1){
                throw new \Exception('PAGE NUMBER MUST BE GREATER THAN 0');
            }
        }
        $countProjects = $this->countProjects($selectedStatus);
        $res = $this->loadData($selectedStatus, $countProjects, $pageNumber);
        $statuses = $this->loadStatuses();
        $this->data = array_merge(
            $this->data, 
            ['projects' => $res], 
            ['statuses' => $statuses], 
            ['selectedStatus' => $selectedStatus],
            ['pagination' => ['limit' => self::PAGE_LIMIT, 'pageNumber' => $pageNumber, 'countProjects' => $countProjects]]
        );
    }

    protected function loadStatuses()
    {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare("SELECT * FROM statuses");
        $stmt->execute();
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        array_unshift($res, ['id' => 0,'name' => 'Összes']);
        return $res;
    }

    protected function countProjects(int $selectedStatus){
        $sql = '
                SELECT 
                    count(p.id) AS darab
                FROM projects p
                LEFT JOIN project_status_pivot psp
                    ON p.id = psp.project_id
                LEFT JOIN statuses s 
                    ON psp.status_id = s.id
            ';
        if($selectedStatus > 0){
            $sql .= " WHERE s.id = $selectedStatus";
        }
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res['darab'];        
    }

    protected function loadData(int $selectedStatus, int $projectCount, int $pageNumber)
    {
        $sql = '
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
            ';

        if($selectedStatus > 0){
            $sql .= " WHERE s.id = $selectedStatus";
        }

        if($projectCount > self::PAGE_LIMIT){
            $offset = ($pageNumber - 1) * self::PAGE_LIMIT;
            $sql .= " LIMIT " . $offset . ", " . self::PAGE_LIMIT;
        }        
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [];
    }
}