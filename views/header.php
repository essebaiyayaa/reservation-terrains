<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FootBooking' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #334155;
        }

        header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #16a34a;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .nav-links a:hover {
            color: #16a34a;
        }

        .nav-links a.active {
            color: #16a34a;
            font-weight: 600;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline {
            background: white;
            color: #16a34a;
            border: 2px solid #16a34a;
        }

        .btn-outline:hover {
            background: #f0fdf4;
        }

        .btn-primary {
            background: #16a34a;
            color: white;
        }

        .btn-primary:hover {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }
        .user-menu {
            position: relative;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .user-button:hover {
            background: #15803d;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            min-width: 220px;
            display: none;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
            animation: fadeIn 0.2s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: #374151;
            text-decoration: none;
            transition: background 0.2s;
        }

        .dropdown-menu a:hover {
            background: #f3f4f6;
        }

        .dropdown-menu a.logout {
            color: #dc2626;
        }

        .dropdown-menu a.logout:hover {
            background: #fee2e2;
        }

        .dropdown-menu hr {
            margin: 0.5rem 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }
        .admin-badge {
            background: #dc2626;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header & Navigation -->
    <header>
        <nav>
            <a href="<?= UrlHelper::url('/') ?>" class="logo">
                <i class="fas fa-futbol"></i>
                FootBooking
                <?php if (isset($currentUser) && $currentUser->role === 'admin'): ?>
                    <span class="admin-badge">ADMIN</span>
                <?php endif; ?>
            </a>
            
            <ul class="nav-links">
                <?php if (isset($currentUser) && $currentUser->role === 'admin'): ?>
                    <!-- Menu spécifique pour l'admin -->
                    <li>
                        <a href="<?= UrlHelper::url('admin') ?>" 
                           class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/dashboard') !== false && !strpos($_SERVER['REQUEST_URI'], '/admin/terrains') && !strpos($_SERVER['REQUEST_URI'], '/admin/reservations') && !strpos($_SERVER['REQUEST_URI'], '/admin/gerants') ? 'active' : '' ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de bord
                        </a>
                    </li>
                    <li>
                        <a href="<?= UrlHelper::url('admin/terrains') ?>" 
                           class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/terrains') !== false ? 'active' : '' ?>">
                            <i class="fas fa-list"></i>
                            Tous les terrains
                        </a>
                    </li>
                    <li>
                        <a href="<?= UrlHelper::url('terrain/create') ?>" 
                           class="<?= strpos($_SERVER['REQUEST_URI'], '/terrain/create') !== false ? 'active' : '' ?>">
                            <i class="fas fa-plus"></i>
                            Ajouter un terrain
                        </a>
                    </li>
                    <li>
                        <a href="<?= UrlHelper::url('admin/reservations') ?>" 
                           class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/reservations') !== false ? 'active' : '' ?>">
                            <i class="fas fa-calendar-alt"></i>
                            Toutes les réservations
                        </a>
                    </li>
                    <li>
                        <a href="<?= UrlHelper::url('admin/gerants') ?>" 
                           class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/gerants') !== false ? 'active' : '' ?>">
                            <i class="fas fa-users"></i>
                            Gestion des gérants
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="<?= UrlHelper::url('/') ?>">Accueil</a></li>
                    <li><a href="<?= UrlHelper::url('terrains') ?>">Liste des terrains</a></li>
                    <?php if (isset($currentUser)): ?>
                        <li><a href="<?= UrlHelper::url('reservation') ?>">Réserver un terrain</a></li>
                        <?php if ($currentUser->role === 'client'): ?>
                            <li>
                                <a href="<?= UrlHelper::url('tournoi/index') ?>" 
                                   class="<?= strpos($_SERVER['REQUEST_URI'], '/tournoi') !== false ? 'active' : '' ?>">
                                    Tournois
                                </a>
                            </li>
                            <li><a href="<?= UrlHelper::url('mes-reservations') ?>">Mes réservations</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <div class="auth-buttons">
                <?php if (isset($currentUser)): ?>
                    <!-- Utilisateur connecté -->
                    <div class="user-menu">
                        <button class="user-button" onclick="toggleUserMenu()">
                            <i class="fa-solid fa-user-circle"></i>
                            <?php echo htmlspecialchars($currentUser->prenom ?? 'User'); ?>
                            <?php if ($currentUser->role === 'admin'): ?>
                                <i class="fas fa-crown" style="font-size: 0.8rem;"></i>
                            <?php endif; ?>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="<?= UrlHelper::url('profile') ?>">
                                <i class="fa-solid fa-user"></i> Mon profil
                            </a>
                            <?php if ($currentUser->role === 'client'): ?>
                                <a href="<?= UrlHelper::url('mes-reservations') ?>">
                                    <i class="fa-solid fa-calendar-check"></i> Mes réservations
                                </a>
                                <a href="<?= UrlHelper::url('tournoi/mesparticipations') ?>">
                                    <i class="fa-solid fa-trophy"></i> Mes tournois
                                </a>
                            <?php endif; ?>
                            <?php if ($currentUser->role === 'admin'): ?>
                                <a href="<?= UrlHelper::url('admin') ?>">
                                    <i class="fa-solid fa-gauge"></i> Dashboard Admin
                                </a>
                            <?php elseif ($currentUser->role === 'gerant_terrain'): ?>
                                <a href="<?= UrlHelper::url('dashboard/gerant') ?>">
                                    <i class="fa-solid fa-gauge"></i> Dashboard Gérant
                                </a>
                            <?php endif; ?>
                            <hr>
                            <a href="<?= UrlHelper::url('logout') ?>" class="logout">
                                <i class="fa-solid fa-right-from-bracket"></i> Se déconnecter
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Utilisateur non connecté -->
                    <a href="<?= UrlHelper::url('login') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Se connecter
                    </a>
                    <a href="<?= UrlHelper::url('register') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-user-plus"></i>
                        S'inscrire
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Contenu de la page -->
    <?php 
    $viewFile = __DIR__ . '/' . $viewName . '.php';
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        echo "<p style='padding: 2rem; text-align: center; color: red;'>Vue introuvable : $viewName</p>";
    }
    ?>

    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }
        // Fermer le menu si on clique en dehors
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu');
            if (userMenu && !userMenu.contains(event.target)) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown) {
                    dropdown.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>