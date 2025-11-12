<?php

/**
 * Class NewsletterController
 * 
 * Gère l'envoi de newsletters aux utilisateurs
 * Accessible par les admins et gérants
 * 
 * @package Controllers
 * @author  Jihane Chouhe
 *  
 * @version 1.0
 */
class NewsletterController extends BaseController
{
    private ?object $currentUser = null;
    private NewsletterModel $newsletterModel;

    public function __construct()
    {
        // Vérifier l'authentification
        $token = Utils::getCookieValue('auth_token');
        if (!$token) {
            UrlHelper::redirect('login');
        }

        $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
        
        // Autoriser uniquement admin et gerant_terrain
        if ($decoded === false || !in_array($decoded->role, ['admin', 'gerant_terrain'])) {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            exit;
        }

        $this->currentUser = $decoded;
        $this->newsletterModel = $this->loadModel('NewsletterModel');
    }

    /**
     * Afficher le formulaire d'envoi de newsletter
     */
    public function index(): void
    {
        $newsletters = $this->newsletterModel->getAll();
        
        $this->renderView('Newsletter/Index', [
            'currentUser' => $this->currentUser,
            'newsletters' => $newsletters
        ], 'Newsletter - Envoi groupé');
    }

    /**
     * Afficher une newsletter
     */
    public function show(string $id): void
    {
        $newsletter = $this->newsletterModel->getById($id);
        
        if (!$newsletter) {
            http_response_code(404);
            $this->renderView('Errors/404', [], 'Newsletter non trouvée');
            return;
        }

        $this->renderView('Newsletter/Show', [
            'currentUser' => $this->currentUser,
            'newsletter' => $newsletter
        ], 'Détails Newsletter');
    }

    /**
     * Traiter l'envoi de la newsletter
     */
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            UrlHelper::redirect('newsletter');
            return;
        }

        $sujet = trim($_POST['sujet'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        
        $errors = [];

        // Validation
        if (empty($sujet)) {
            $errors[] = "Le sujet est obligatoire";
        }
        if (empty($contenu)) {
            $errors[] = "Le contenu est obligatoire";
        }

        // Gestion du fichier joint
        $pieceJointe = null;
        if (isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] === UPLOAD_ERR_OK) {
            $pieceJointe = $this->handleFileUpload($_FILES['piece_jointe'], $errors);
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            UrlHelper::redirect('newsletter');
            return;
        }

        // Récupérer tous les emails
        $users = $this->newsletterModel->getAllUserEmails();
        
        if (empty($users)) {
            $_SESSION['errors'] = ["Aucun utilisateur à qui envoyer la newsletter"];
            UrlHelper::redirect('newsletter');
            return;
        }

        // Enregistrer la newsletter en base
        $newsletterData = [
            'id_expediteur' => $this->currentUser->user_id,
            'sujet' => htmlspecialchars($sujet),
            'contenu' => htmlspecialchars($contenu),
            'nombre_destinataires' => 0,
            'piece_jointe' => $pieceJointe,
            'statut' => 'en_cours'
        ];

        $this->newsletterModel->add($newsletterData);

        // Envoyer les emails
        $success = 0;
        $failed = 0;

        foreach ($users as $user) {
            $htmlBody = $this->getNewsletterTemplate(
                $user->prenom . ' ' . $user->nom,
                $contenu
            );

            $attachments = null;
            if ($pieceJointe) {
                $attachments = [[
                    'path' => __DIR__ . '/../uploads/newsletters/' . $pieceJointe,
                    'name' => $pieceJointe
                ]];
            }

            $sent = Utils::sendEmail(
                $user->email,
                $sujet,
                $htmlBody,
                strip_tags($contenu),
                $attachments
            );

            if ($sent) {
                $success++;
            } else {
                $failed++;
                error_log("Échec envoi newsletter à: " . $user->email);
            }
        }

        // Mettre à jour le statut
        $finalStatus = $failed === 0 ? 'envoye' : 'echoue';
        $this->newsletterModel->update(
            $this->newsletterModel->getTotalCount(),
            [
                'statut' => $finalStatus,
                'nombre_destinataires' => $success
            ]
        );

        $_SESSION['success'] = "Newsletter envoyée avec succès à $success utilisateur(s)";
        if ($failed > 0) {
            $_SESSION['errors'] = ["$failed envoi(s) ont échoué"];
        }

        UrlHelper::redirect('newsletter');
    }

    /**
     * Gère l'upload du fichier joint
     */
    private function handleFileUpload(array $file, array &$errors): ?string
    {
        $uploadDir = __DIR__ . '/../uploads/newsletters/';
        
        // Créer le dossier si nécessaire
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Vérifier la taille (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = "Le fichier joint ne doit pas dépasser 5MB";
            return null;
        }

        // Extensions autorisées
        $allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'zip'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($fileExt, $allowedExts)) {
            $errors[] = "Type de fichier non autorisé. Extensions acceptées: " . implode(', ', $allowedExts);
            return null;
        }

        // Nom unique
        $newFileName = uniqid('newsletter_') . '.' . $fileExt;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $newFileName;
        }

        $errors[] = "Erreur lors de l'upload du fichier";
        return null;
    }

    /**
     * Template HTML pour la newsletter
     */
    private function getNewsletterTemplate(string $name, string $contenu): string
    {
        return "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Newsletter</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f9fafb;
                }
                .container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .header { 
                    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); 
                    color: white; 
                    padding: 40px 30px; 
                    text-align: center; 
                }
                .content { 
                    padding: 40px 30px; 
                }
                .footer { 
                    text-align: center; 
                    margin-top: 30px; 
                    color: #6b7280; 
                    font-size: 14px;
                    padding: 20px;
                    background: #f9fafb;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1> " . SITE_NAME . "</h1>
                </div>
                <div class='content'>
                    <p>Bonjour <strong>{$name}</strong>,</p>
                    <div style='margin: 20px 0;'>
                        " . nl2br($contenu) . "
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " " . SITE_NAME . " - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Méthodes abstraites requises
     */
    public function edit(string $id): void
    {
        http_response_code(404);
        $this->renderView('Errors/404', [], 'Page non trouvée');
    }

    public function delete(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        if ($this->newsletterModel->delete($id)) {
            $_SESSION['success'] = "Newsletter supprimée avec succès";
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
}