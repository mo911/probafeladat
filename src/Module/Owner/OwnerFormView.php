<?php 

namespace Pamutlabor\Module\Owner;

use Pamutlabor\Module\Layout\LayoutView;

class OwnerFormView extends LayoutView
{
    protected function content(array $data): string{
        $id = $data['owner']['id'] ?? "";
        $name = $data['owner']['name'] ?? "";
        $email = $data['owner']['email'] ?? "";

        $errorMessage = "";        
        if(!empty($data['errors'])){
            $errorDescription = "";
            foreach ($data['errors'] as $error) {
                $errorDescription .= "$error <br>"; 
            }
            $errorMessage = "<div class='alert alert-danger text-center fixed-bottom mb-0' role='alert'>
                $errorDescription
            </div>";
        }

        return '
            <div class="container mt-4 needs-validation" novalidate>
                <form method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Név</label>
                        <input type="text" class="form-control" id="title" name="name" placeholder="" value="' . $name . '" required>
                        <input type="hidden" name="id" value="' . $id . '">
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Email</label>
                        <input type="email" class="form-control" id="title" name="email" placeholder="" value="' . $email . '" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Mentés</button>
                </form>
            ' . $errorMessage . ' </div>
        ';
    }
}