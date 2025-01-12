<?php

namespace Pamutlabor\Module\TaskManager;

use Pamutlabor\Core\PDODatabase;
use Pamutlabor\Module\Layout\LayoutController;
use Pamutlabor\Module\TaskManager\Model\Project;

class TaskManagerDeleteController extends LayoutController
{
    protected $data = [
        'title' => 'Projektek'
    ];

    public function __construct() {
        $this->layout = null;
    }
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['id'];

            if($projectId > 0){
                $res = Project::load($projectId);
                if($res){
                    $isDeleted = (bool)Project::delete($projectId);
        
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $isDeleted]);
                    exit;
                }
            }
            throw new \Exception('SOMETHING_WRONG');
        }
    }

}