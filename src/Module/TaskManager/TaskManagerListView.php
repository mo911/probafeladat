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
            <div class="container mt-4" id="projectContainer">
                ' . $projectCards . '
            </div>' . $this->script();
    }

    protected function projectCard($project): string
    {
        return '
            
                <div class="card mb-3" data-project-id="' . $project['projectId'] . '">
                    <div class="card-body">
                        <h5 class="card-title">' . $project['projectTitle'] . '</h5>
                        <p class="card-text">' . $project['ownerName'] . ' (' . $project['ownerEmail'] . ')</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">' . $project['statusName'] . '</span>
                            <div>
                                <a class="btn btn-primary me-2" href="/project/' . $project['projectId'] . '">Szerkesztés</a>
                                <button class="btn btn-danger delete-btn">Törlés</button>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    protected function script(): string {
        return '<script>
                $(document).ready(function () {
                $(".delete-btn").on("click", function () {
                    const card = $(this).closest(".card");
                    const projectId = card.data("project-id");

                    if (confirm("Biztosan törlöd a projektet?")) {
                        $.ajax({
                            url: "/project/delete",
                            type: "POST",
                            data: { id: projectId },
                            success: function (response) {
                                if (response.success) {
                                    card.remove();
                                } else {
                                    alert("Hiba történt a törlés során!");
                                }
                            },
                            error: function () {
                                alert("Hiba történt a törlés során!");
                            }
                        });
                    }
                });
            });
            </script>';
    }
}