<?php 

namespace Pamutlabor\Module\TaskManager;

use Pamutlabor\Module\Layout\LayoutView;

class TaskManagerFormView extends LayoutView
{
    protected function content(array $data): string{
        $projectTitle = $data['project']['projectTitle'] ?? "";
        $projectDescription = $data['project']['projectDescription'] ?? "";
        $ownerId = $data['project']['ownerId'] ?? "";
        $ownerName = $data['project']['ownerName'] ?? "";
        $ownerEmail = $data['project']['ownerEmail'] ?? "";
        return '
            <div class="container mt-4">
                <form method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Cím</label>
                        <input type="text" class="form-control" id="title" name="projectTitle" placeholder="" value="' . $projectTitle . '">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Leírás</label>
                        <textarea class="form-control" name="projectDescription" id="description" rows="3">' . $projectDescription . '</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Státusz</label>
                        <select class="form-select" id="status" name="projectStatus">
                            ' . $this->statuses($data['statuses'], $data['project']['statusId'] ?? 0) . '
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="contact-name" class="form-label">Kapcsolattartó neve</label>
                        <input type="text" class="form-control" name="ownerName" id="contact-name" placeholder="" value="' . $ownerName . '">
                        <input type="hidden" value="' . $ownerId . '" name="ownerId">
                    </div>

                    <div class="mb-3">
                        <label for="contact-email" class="form-label">Kapcsolattartó e-mail címe</label>
                        <input type="email" class="form-control"  name="ownerEmail"id="contact-email" placeholder="" value="' . $ownerEmail . '">
                    </div>

                    <button type="submit" class="btn btn-primary">Mentés</button>
                </form>
            </div>
        ';
    }

    protected function statuses($statuses, $acutalValue): string
    {
        $options = "";
        foreach ($statuses as $status) {
            $options .= "<option " . ($acutalValue == $status['id'] ? 'selected=selected' : '') . " value=" . $status['id'] . ">" . $status['name'] . "</option>";
        }
        return $options;
    }
}