<?php 

namespace Pamutlabor\Module\TaskManager;

use Pamutlabor\Module\Layout\LayoutView;

class TaskManagerFormView extends LayoutView
{
    protected function content(array $data): string{
        $projectId = $data['project']['projectId'] ?? "";
        $projectTitle = $data['project']['projectTitle'] ?? "";
        $projectDescription = $data['project']['projectDescription'] ?? "";
        $ownerId = $data['project']['ownerId'] ?? "";
        $ownerEmail = $data['project']['ownerEmail'] ?? "";
        return $this->script($data['owners']) . '
            <div class="container mt-4 needs-validation" novalidate>
                <form method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Cím</label>
                        <input type="text" class="form-control" id="title" name="projectTitle" placeholder="" value="' . $projectTitle . '" required>
                        <input type="hidden" name="projectId" value="' . $projectId . '">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Leírás</label>
                        <textarea class="form-control" name="projectDescription" id="description" rows="3" required>' . $projectDescription . '</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Státusz</label>
                        <select class="form-select" id="status" name="statusId">
                            ' . $this->options($data['statuses'], "id", "name", $data['project']['statusId'] ?? 0) . '
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="owner-name"  class="form-label">Kapcsolattartó neve</label>
                        <select class="form-select" name="ownerId" id="owner-name">
                            ' . $this->options($data['owners'], "id", "name", $ownerId ?? 0) . '
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="contact-email" class="form-label">Kapcsolattartó e-mail címe</label>
                        <input id="owner-email" disabled type="email" class="form-control"  name="ownerEmail"id="contact-email" placeholder="" value="' . $ownerEmail . '">
                    </div>

                    <button type="submit" class="btn btn-primary">Mentés</button>
                </form>
            </div>
        ';
    }

    protected function script($owners): string {
        $ownersData = "";
        foreach ($owners as $owner) {
            $ownersData .= '"' . $owner['id'] . '": "' . $owner['email'] . '",';
        }
        return "
            <script>
                
                $(document).ready(function() {
                    // Az összes lehetséges kapcsolattartó neve és emailje (backendről generálva JSON-ként)
                    const ownerEmails = {" .
                        $ownersData
                    . "};
                    $('#owner-name').on('change', function() {
                        const selectedOwnerId = $(this).val();
                        $('#owner-email').val(ownerEmails[selectedOwnerId] || '');
                    });

                    $('#owner-name').trigger('change');
                });
            </script>
        ";
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