<?php

namespace Pamutlabor\Module\Owner;

use Pamutlabor\Core\PDODatabase;
use Pamutlabor\Module\Layout\LayoutController;
use Pamutlabor\Module\Owner\Model\Owner;

class OwnerFormController extends LayoutController
{
    protected $data = [
        'title' => 'Kapcsolattartó'
    ];

    protected $errors = [];

    protected function createOrUpdateOwner($input) {
        if ($input['id'] > 0) {
            $res = Owner::load($input['id']);
            if($res){
                Owner::update($input);
            }
        } else {
            Owner::save($input);
        }
    }

    public function __construct() {
        $this->layout = new OwnerFormView();
    }

    protected function validateForm($input): bool {
        $result = true;
        if(!empty($input['id']) && !is_numeric($input['id'])){
            $result = false;
            $this->errors[] = 'Owner id csak szám lehet';
        }
        if(strlen($input['name']) == 0){
            $result = false;
            $this->errors[] = 'Név nem lehet üres';
        }
        if(strlen($input['email']) == 0){
            $result = false;
            $this->errors[] = 'Email nem lehet üres';
        }
        return $result;
    }
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = [
                'id' => $post['id'],
                'name' => $post['name'],
                'email' => $post['email']
            ];
            if($this->validateForm($input)){
                $this->createOrUpdateOwner($post);
                header('Location: /owners');
                exit();
            }
        }
        $res = $this->loadData($variable['id'] ?? 0);
        $this->data = array_merge($this->data, ['owner' => $res], ['errors' => $this->errors]);
    }

    protected function loadData(int $id = 0)
    {
        $database = PDODatabase::getConnection();
        $stmt = $database->prepare(
            '
                SELECT 
                    * 
                FROM owners o
                WHERE o.id = :id
            '
        );
        $stmt->execute([
            ':id' => $id ?? 0
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?? [];
    }
}