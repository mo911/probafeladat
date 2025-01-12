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
                <div class="mb-3">
                    <label for="status" class="form-label">Szűrés státuszra:</label>
                    <select class="form-select" id="status" name="selectedStatus">
                        ' . $this->options($data['statuses'], "id", "name", $data['selectedStatus'] ?? 0) . '
                    </select>
                </div>
            </div>
            <div class="container mt-4" id="projectContainer">
                ' . $projectCards . '
            </div>
            ' . $this->pagination($data['pagination']) . $this->script();
    }

    protected function pagination($pagination): string {
        if($pagination['countProjects'] > $pagination['limit']){
            $totalPages = ceil($pagination['countProjects'] / $pagination['limit']);            
            $pages = "";
            for($i = 0; $i < $totalPages; $i++){
                $pageNumber = $i + 1;
                $pages .= '<li class="page-item ' . ($pagination["pageNumber"] == $pageNumber ? "active" : "") .'"><a class="page-link pagination-btn" href="javascript:void(0)" data-page="' . $pageNumber . '">' . $pageNumber . '</a></li>';
            }
            return '
                <nav aria-label="Oldalak navigációja">
                    <ul class="pagination justify-content-center">                        
                        <li class="page-item ' . ($pagination["pageNumber"] > 1 ? "" : "disabled") .'">
                            <a class="page-link pagination-btn" href="javascript:void(0)" data-page="' . ($pagination['pageNumber'] - 1) . '" aria-disabled="true">Előző</a>
                        </li>
                        ' . $pages . '
                        <!-- Következő gomb -->
                        <li class="page-item ' . ($pagination["pageNumber"] >= $totalPages ? "disabled" : "") .'">
                            <a class="page-link pagination-btn" href="javascript:void(0)" data-page="' . ($pagination['pageNumber'] + 1) . '">Következő</a>
                        </li>
                    </ul>
                </nav>
            ';
        }
        return '';
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
                $("#status").change(function () {
                    const selectedStatus = $(this).val();
                    const form = $("<form>", {
                        method: "POST",
                        action: window.location.href
                    });
                    $("<input>", {
                        type: "hidden",
                        name: "selectedStatus",
                        value: selectedStatus
                    }).appendTo(form);
                    form.appendTo("body").submit();
                });
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

            $(".pagination-btn").on("click", function () {
                const pageNumber = $(this).data("page");
                const form = $("<form>", {
                    method: "POST",
                    action: window.location.href
                });
                $("<input>", {
                    type: "hidden",
                    name: "pageNumber",
                    value: pageNumber
                }).appendTo(form);
                form.appendTo("body").submit();
            });
            </script>';
    }

    protected function options(
        array $options, 
        string $key, 
        string $value, 
        $acutalValue
    ): string {
        $result = "";
        foreach ($options as $option) {
            $result .= "<option " . ($acutalValue == $option[$key] ? 'selected=selected' : '') . " value=" . $option[$key] . ">" . $option[$value] . "</option>";
        }
        return $result;
    }
}