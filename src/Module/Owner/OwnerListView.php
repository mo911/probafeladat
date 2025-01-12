<?php 

namespace Pamutlabor\Module\Owner;

use Pamutlabor\Module\Layout\LayoutView;

class OwnerListView extends LayoutView
{
    protected function content(array $data): string{
        $html = "";
        foreach ($data['owners'] as $owner) {
            $html .= $this->ownerCard($owner);
        }
        return '<div class="container mt-4">' . $html . '</div>';
    }

    protected function ownerCard($owner): string
    {
        return '
            
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">' . $owner['name'] . ' (' . $owner['email'] . ')</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span>
                            <div>
                                
                                <a class="btn btn-primary me-2" href="/owner/' . $owner['id'] . '">Szerkesztés</a>
                            </div>
                        </div>
                    </div>
                </div>';
    }
}