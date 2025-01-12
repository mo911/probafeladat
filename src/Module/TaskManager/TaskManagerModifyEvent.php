<?php

namespace Pamutlabor\Module\TaskManager;

use PHPMailer\PHPMailer\PHPMailer;

class TaskManagerModifyEvent
{

    protected function sendEmail($to, $name, $message) {
        $config = include __DIR__ . '/../../Config/config.php';
        $mail = new PHPMailer(true); // "true" engedélyezi a kivételkezelést
        // SMTP konfiguráció
        $mail->isSMTP();
        $mail->Host       = $config['smtpHost'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['smtpUsername'];
        $mail->Password   = $config['smtpPassword'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet = "UTF-8";

        // Email beállítások
        $mail->setFrom('pamutlabor@moszerviz.com', 'Pamutlabor');
        $mail->addAddress($to, $name);            

        // Email tartalom
        $mail->isHTML(true);
        $mail->Subject = 'Pamutlabor projekt módosítás';
        $mail->Body    = $message;            

        // Email küldése
        $mail->send();            
    }
    public function handle($newObject, $oldObject){
        // projectId, projectTitle, , , , 
        $sendingMessage = "A következő adatok változtak: ";
        if($newObject['projectTitle'] !== $oldObject['projectTitle']) {
            $sendingMessage .= "Cím: " . $newObject['projectTitle'];
        }
        if($newObject['projectDescription'] !== $oldObject['projectDescription']) {
            $sendingMessage .= "Leírás: " . $newObject['projectDescription'];
        }
        if($newObject['ownerEmail'] !== $oldObject['ownerEmail']) {
            $sendingMessage .= "Owner email: " . $newObject['ownerEmail'];
        }
        if($newObject['ownerName'] !== $oldObject['ownerName']) {
            $sendingMessage .= "Owner név: " . $newObject['ownerName'];
        }
        if($newObject['statusName'] !== $oldObject['statusName']) {
            $sendingMessage .= "Státusz: " . $newObject['statusName'];
        }
        $this->sendEmail($newObject['ownerEmail'], $newObject['ownerName'], $sendingMessage);
    }
}