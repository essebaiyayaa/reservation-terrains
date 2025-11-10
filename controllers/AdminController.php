<?php

/**
 * Class AdminController
 * 
 * Handles all admin-specific operations - FIXED VERSION (Sans mailer.php)
 */
class AdminController extends BaseController
{
    private ?object $currentUser = null;
    private TerrainModel $terrainModel;
    private UserModel $userModel;
    private ReservationModel $reservationModel;

    public function __construct()
    {
        // Check authentication and admin role
        $token = Utils::getCookieValue('auth_token');
        if (!$token) {
            UrlHelper::redirect('login');
        }

        $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
        if ($decoded === false || $decoded->role !== 'admin') {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            exit;
        }

        $this->currentUser = $decoded;
        $this->terrainModel = $this->loadModel('TerrainModel');
        $this->userModel = $this->loadModel('UserModel');
        $this->reservationModel = $this->loadModel('ReservationModel');
    }

    /**
     * View all gerants - AVEC GESTION DE LA CREATION
     */
    public function gerants(): void
    {
        // Si c'est une requête POST avec action=create, créer le gérant
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
            $this->handleCreateGerant();
            return;
        }
        
        // Sinon, afficher la liste
        $gerants = $this->userModel->getAllByRole('gerant_terrain');
        
        // Get terrain count and reservation count for each gerant
        foreach ($gerants as &$gerant) {
            $terrains = $this->terrainModel->getByGerantId($gerant->id_utilisateur);
            $gerant->nb_terrains = count($terrains);
            
            $nbReservations = 0;
            foreach ($terrains as $terrain) {
                $terrainId = is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'];
                $reservations = $this->reservationModel->getByTerrainId($terrainId);
                $nbReservations += count($reservations);
            }
            $gerant->nb_reservations = $nbReservations;
        }

        $this->renderView('Admin/Gerants', [
            'currentUser' => $this->currentUser,
            'gerants' => $gerants
        ], 'Gestion des Gérants - Admin');
    }

    /**
     * Handle gerant creation (POST) - VERSION CORRIGÉE
     */
    private function handleCreateGerant(): void
    {
        error_log("=== HANDLE CREATE GERANT START ===");
        
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');

        // Validation
        $errors = [];
        
        if (empty($nom)) $errors[] = "Le nom est obligatoire";
        if (empty($prenom)) $errors[] = "Le prénom est obligatoire";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email est invalide";
        }
        
        if (empty($errors)) {
            $emailExists = $this->userModel->emailExists($email);
            if ($emailExists) {
                $errors[] = "Cet email est déjà utilisé";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            UrlHelper::redirect('admin/gerants');
            return;
        }

        // Generate temporary password (random 12 characters)
        $passwordClair = bin2hex(random_bytes(6)); // 12 caractères hex
        $passwordHash = password_hash($passwordClair, PASSWORD_DEFAULT);

        // Create gerant
        $data = [
            'nom' => htmlspecialchars($nom),
            'prenom' => htmlspecialchars($prenom),
            'email' => $email,
            'telephone' => $telephone,
            'mot_de_passe' => $passwordHash,
            'role' => 'gerant_terrain',
            'verification_token' => null,
            'token_expiry' => null,
            'compte_verifie' => 1  // Le compte est directement vérifié
        ];

        try {
            $result = $this->userModel->add($data);
            
            if ($result) {
                error_log("Gérant créé avec succès. Envoi de l'email...");
                
                // Envoyer l'email avec Utils::sendEmail()
                $emailSent = $this->sendGerantCredentialsEmail($email, $nom, $prenom, $passwordClair);
                
                if ($emailSent) {
                    $_SESSION['success'] = "Gérant créé avec succès ! Un email a été envoyé à $email";
                    error_log("Email envoyé avec succès à $email");
                } else {
                    $_SESSION['success'] = "Gérant créé avec succès !";
                    $_SESSION['errors'] = [
                        "L'email n'a pas pu être envoyé. Mot de passe temporaire : <strong>$passwordClair</strong>",
                        "Veuillez communiquer ces identifiants manuellement au gérant."
                    ];
                    error_log("ÉCHEC de l'envoi d'email à $email");
                }
            } else {
                $_SESSION['errors'] = ["Erreur lors de la création du gérant dans la base de données"];
                error_log("ÉCHEC de la création du gérant dans la BDD");
            }
        } catch (Exception $e) {
            error_log("EXCEPTION: " . $e->getMessage());
            error_log("TRACE: " . $e->getTraceAsString());
            $_SESSION['errors'] = ["Erreur: " . $e->getMessage()];
        }

        error_log("=== HANDLE CREATE GERANT END ===");
        UrlHelper::redirect('admin/gerants');
    }

    /**
     * Send credentials email to gerant using Utils::sendEmail()
     */
    private function sendGerantCredentialsEmail(string $email, string $nom, string $prenom, string $password): bool
    {
        try {
            error_log("=== SENDING GERANT EMAIL VIA UTILS ===");
            error_log("Destinataire: $email");
            error_log("Nom: $prenom $nom");
            error_log("Password: $password");
            
            $subject = "Bienvenue sur " . SITE_NAME . " - Vos identifiants";
            
            // Générer le corps HTML avec le template
            $htmlBody = Template::render(Template::$GERANT_CREDENTIALS_EMAIL_TEMPLATE, [
                'name' => $prenom . ' ' . $nom,
                'email' => $email,
                'password' => $password,
                'login_url' => UrlHelper::url('login')
            ]);
            
            // Corps texte alternatif
            $textBody = "Bienvenue sur " . SITE_NAME . "\n\n" .
                       "Bonjour " . $prenom . " " . $nom . ",\n\n" .
                       "Votre compte gérant a été créé avec succès.\n\n" .
                       "Vos identifiants de connexion :\n" .
                       "Email : " . $email . "\n" .
                       "Mot de passe temporaire : " . $password . "\n\n" .
                       "Connectez-vous ici : " . UrlHelper::url('login') . "\n\n" .
                       "Nous vous recommandons de changer votre mot de passe après votre première connexion.\n\n" .
                       "Cordialement,\n" .
                       "L'équipe " . SITE_NAME;
            
            // Utiliser Utils::sendEmail()
            $result = Utils::sendEmail(
                $email,
                $subject,
                $htmlBody,
                $textBody
            );
            
            error_log("Résultat Utils::sendEmail(): " . ($result ? 'SUCCESS' : 'FAILED'));
            
            return $result;
            
        } catch (Exception $e) {
            error_log("EXCEPTION dans sendGerantCredentialsEmail: " . $e->getMessage());
            error_log("TRACE: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Delete gerant
     */
    public function deleteGerant(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        // Check if gerant has terrains
        $terrains = $this->terrainModel->getByGerantId($id);
        
        if (!empty($terrains)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Impossible de supprimer un gérant qui gère des terrains'
            ]);
            return;
        }

        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = "Gérant supprimé avec succès";
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

    // Required abstract methods implementation
    public function show($id): void
    {
        http_response_code(404);
        $this->renderView('Errors/404', [], 'Page non trouvée');
    }

    public function create(): void
    {
        UrlHelper::redirect('admin/gerants');
    }

    public function edit($id): void
    {
        http_response_code(404);
        $this->renderView('Errors/404', [], 'Page non trouvée');
    }

    public function delete($id): void
    {
        $this->deleteGerant($id);
    }

    /**
     * Admin Dashboard with statistics
     */
    public function index(): void
    {
        $stats = [
            'total_terrains' => $this->terrainModel->getTotalCount(),
            'total_gerants' => count($this->userModel->getAllByRole('gerant_terrain')),
            'total_reservations' => $this->reservationModel->getTotalCount(),
            'ca_mois' => $this->reservationModel->getMonthlyRevenue()
        ];

        $this->renderView('Admin/Dashboard', [
            'currentUser' => $this->currentUser,
            'stats' => $stats
        ], 'Dashboard Admin - FootBooking');
    }

    /**
     * View all terrains
     */
    public function terrains(): void
    {
        $ville = $_GET['ville'] ?? '';
        $type = $_GET['type'] ?? '';
        $filters = compact('ville', 'type');
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $terrains = $this->terrainModel->getAll($filters);
        $villes = $this->terrainModel->getDistinctVilles();
        
        $total = count($terrains);
        $totalPages = ceil($total / $limit);
        $terrains = array_slice($terrains, $offset, $limit);

        $this->renderView('Admin/Terrains', [
            'currentUser' => $this->currentUser,
            'terrains' => $terrains,
            'villes' => $villes,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages
        ], 'Gestion des Terrains - Admin');
    }

    /**
     * View all reservations
     */
    public function reservations(): void
    {
        $statut = $_GET['statut'] ?? '';
        $date = $_GET['date'] ?? '';
        $terrainId = $_GET['terrain'] ?? '';
        $filters = compact('statut', 'date', 'terrainId');
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $reservations = $this->reservationModel->getAll($filters);
        $terrains = $this->terrainModel->getAll();
        
        $total = count($reservations);
        $totalPages = ceil($total / $limit);
        $reservations = array_slice($reservations, $offset, $limit);

        $this->renderView('Admin/Reservations', [
            'currentUser' => $this->currentUser,
            'reservations' => $reservations,
            'terrains' => $terrains,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ], 'Toutes les Réservations - Admin');
    }
/**
 * View all tournaments 
 */
public function tournois(): void
{
    try {
        // Créer une instance de PDODatabase
        $db = new PDODatabase();
        
        // Récupérer les filtres
        $filters = [
            'statut' => $_GET['statut'] ?? '',
            'ville' => $_GET['ville'] ?? ''
        ];

      
        $query = "SELECT 
                    t.*,
                    ter.nom_terrain,
                    ter.ville,
                    u.prenom as prenom_gerant,
                    u.nom as nom_gerant,
                    u.email as email_gerant,
                    (t.nombre_max_equipes - COALESCE(
                        (SELECT COUNT(*) FROM Equipe WHERE id_tournoi = t.id_tournoi), 
                        0
                    )) as places_disponibles
                  FROM Tournoi t
                  LEFT JOIN terrain ter ON t.id_terrain = ter.id_terrain
                  LEFT JOIN utilisateur u ON t.id_gerant = u.id_utilisateur
                  WHERE 1=1";
        
       
        if (!empty($filters['statut'])) {
            $query .= " AND LOWER(t.statut) = LOWER(:statut)";
        }
        
        if (!empty($filters['ville'])) {
            $query .= " AND ter.ville = :ville";
        }
        
        $query .= " ORDER BY t.date_debut DESC";
        
      
        error_log("=== ADMIN TOURNOIS - SQL QUERY ===");
        error_log($query);
        
 
        $db->query($query);
        
      
        if (!empty($filters['statut'])) {
            $db->bindValue(':statut', $filters['statut'], PDO::PARAM_STR);
            error_log("Filtre statut: " . $filters['statut']);
        }
        
        if (!empty($filters['ville'])) {
            $db->bindValue(':ville', $filters['ville'], PDO::PARAM_STR);
            error_log("Filtre ville: " . $filters['ville']);
        }
        
        
        $tournois = $db->results();
        
        
        $db->query("SELECT DISTINCT ville FROM terrain WHERE ville IS NOT NULL AND ville != '' ORDER BY ville");
        $villesResult = $db->results();
        
      
        $villes = array_map(function($v) {
            return $v->ville;
        }, $villesResult);
        

        error_log("=== RÉSULTATS ===");
        error_log(" Nombre de tournois trouvés: " . count($tournois));
        error_log(" Nombre de villes: " . count($villes));
        
        if (!empty($tournois)) {
            error_log("Exemple de tournoi: " . $tournois[0]->nom_tournoi);
            error_log("   - Statut: " . $tournois[0]->statut);
            error_log("   - Places dispo: " . $tournois[0]->places_disponibles);
        } else {
            error_log(" Aucun tournoi trouvé avec ces critères");
        }
  
        if (!empty($tournois)) {
            $_SESSION['success'] = count($tournois) . " tournoi(s) trouvé(s)";
        }
        
  
        $this->renderView('Admin/Tournois', [
            'currentUser' => $this->currentUser,
            'tournois' => $tournois,
            'villes' => $villes,
            'filters' => $filters
        ], 'Gestion des Tournois - Admin');
        
    } catch (PDOException $e) {
        error_log("ERREUR PDO TOURNOIS ADMIN ");
        error_log("Message: " . $e->getMessage());
        error_log("Code: " . $e->getCode());
        error_log("File: " . $e->getFile() . " (ligne " . $e->getLine() . ")");
        error_log("Trace: " . $e->getTraceAsString());
        
        $_SESSION['error'] = "Erreur de base de données: " . $e->getMessage();
        
        $this->renderView('Admin/Tournois', [
            'currentUser' => $this->currentUser,
            'tournois' => [],
            'villes' => [],
            'filters' => []
        ], 'Gestion des Tournois - Admin');
        
    } catch (Exception $e) {
        error_log(" ERREUR GÉNÉRALE TOURNOIS ADMIN ");
        error_log("Message: " . $e->getMessage());
        error_log("Trace: " . $e->getTraceAsString());
        
        $_SESSION['error'] = "Erreur: " . $e->getMessage();
        
        $this->renderView('Admin/Tournois', [
            'currentUser' => $this->currentUser,
            'tournois' => [],
            'villes' => [],
            'filters' => []
        ], 'Gestion des Tournois - Admin');
    }
}
}