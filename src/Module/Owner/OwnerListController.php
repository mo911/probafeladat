<?php

namespace Pamutlabor\Module\Owner;

use Pamutlabor\Core\PDODatabase;
use Pamutlabor\Module\Layout\LayoutController;

class OwnerListController extends LayoutController
{
    protected $data = [
        'title' => 'Kapcsolattartók'
    ];

    public function __construct() {
        $this->layout = new OwnerListView();
    }
    protected function handleRequest(array $variable = [], array $post = [], array $get = [])
    {
        $res = $this->loadData();
        $this->data = array_merge($this->data, ['owners' => $res]);
    }

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

    protected function loadData()
    {
        $database = (new PDODatabase())->getConnection();
        $stmt = $database->prepare(
            '
                SELECT 
                    id,
                    name,
                    email
                FROM owners'
        );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [];
    }
}