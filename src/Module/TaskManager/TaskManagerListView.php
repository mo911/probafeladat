<?php 

namespace Pamutlabor\Module\TaskManager;

use Pamutlabor\Module\Layout\LayoutView;

class TaskManagerListView extends LayoutView
{
    protected function content(array $data): string{
        $projectCards = "";
        foreach ($data['projects'] as $project) {
            $projectCards .= $this->projectCard($project);
        }
        return '
            <div class="container mt-4">
                ' . $projectCards . '
            </div>
        ';
    }

    protected function projectCard($project): string
    {
        return '
            
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">' . $project['projectTitle'] . '</h5>
                        <p class="card-text">' . $project['ownerName'] . ' (' . $project['ownerEmail'] . ')</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">' . $project['statusName'] . '</span>
                            <div>
                                <a class="btn btn-primary me-2" href="/project/' . $project['projectId'] . '">Szerkesztés</a>
                                <button class="btn btn-danger">Törlés</button>
                            </div>
                        </div>
                    </div>
                </div>';
    }
}