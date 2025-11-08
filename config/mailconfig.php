<?php

require_once 'load.php';

// Configuration PHPMailer
define('MAIL_HOST', $_ENV['MAIL_HOST']);
define('MAIL_PORT', $_ENV['MAIL_PORT']);
define('MAIL_USERNAME', $_ENV['MAIL_USERNAME']);
define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD']);
define('MAIL_ENCRYPTION', $_ENV['MAIL_ENCRYPTION']);
define('MAIL_FROM_ADDRESS', $_ENV['MAIL_FROM_ADDRESS']);
define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME']);

/**
 * Classe Mailer utilisant PHPMailer
 */
class Mailer
{
    private PHPMailer\PHPMailer\PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $this->configure();
    }

    private function configure(): void
    {
        // Configuration SMTP
        $this->mail->isSMTP();
        $this->mail->Host = MAIL_HOST;
        $this->mail->Port = MAIL_PORT;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = MAIL_USERNAME;
        $this->mail->Password = MAIL_PASSWORD;
        
        // Encryption
        if (MAIL_ENCRYPTION === 'tls') {
            $this->mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        } elseif (MAIL_ENCRYPTION === 'ssl') {
            $this->mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        }
        
        // Encoding
        $this->mail->CharSet = 'UTF-8';
        
        // Expéditeur
        $this->mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        
        // Debug (à désactiver en production)
        // $this->mail->SMTPDebug = 2;
        // $this->mail->Debugoutput = 'error_log';
    }

    public function sendEmail(
        string $toEmail, 
        string $toName, 
        string $subject, 
        string $htmlBody, 
        string $textBody = ''
    ): bool {
        try {
            // IMPORTANT: Nettoyer les destinataires précédents pour éviter d'envoyer à plusieurs personnes
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            $this->mail->clearCustomHeaders();
            $this->mail->clearReplyTos();
            
            // Destinataire
            $this->mail->addAddress($toEmail, $toName);
            
            // Sujet
            $this->mail->Subject = $subject;
            
            // Corps HTML
            $this->mail->isHTML(true);
            $this->mail->Body = $htmlBody;
            
            // Corps texte alternatif
            if (!empty($textBody)) {
                $this->mail->AltBody = $textBody;
            }
            
            // Envoi
            $result = $this->mail->send();
            
            // Log de succès
            if ($result) {
                error_log("Email envoyé avec succès à: $toEmail");
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Erreur PHPMailer: " . $this->mail->ErrorInfo);
            error_log("Exception: " . $e->getMessage());
            return false;
        }
    }
}

?>