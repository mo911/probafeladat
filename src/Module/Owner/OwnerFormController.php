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

    protected function createOrUpdateOwner($data) {
        $input = [
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email']
        ];
        if ($input['id'] > 0) {
            $res = Owner::load($data['id']);
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

    protected function validateForm(): bool {
        return true;
    }
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($this->validateForm()){
                $this->createOrUpdateOwner($post);
                header('Location: /owners');
                exit();
            }
        }
        $res = $this->loadData($variable['id'] ?? 0);
        $this->data = array_merge($this->data, ['owner' => $res]);
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