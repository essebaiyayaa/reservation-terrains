<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Admin' ?></title>
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
            color: #dc2626;
            text-decoration: none;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
        }

        .user-role {
            font-size: 0.875rem;
            color: #64748b;
        }

        .btn {
            padding: 0.6rem 1.2rem;
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

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 5%;
        }

        .welcome-section {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 4px solid #dc2626;
        }

        .stat-icon {
            font-size: 2rem;
            color: #dc2626;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .action-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .action-card:hover {
            border-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .action-icon {
            font-size: 3rem;
            color: #dc2626;
            margin-bottom: 1rem;
        }

        .action-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .action-description {
            color: #64748b;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 1rem;
            }

            .stats-grid, .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="welcome-section">
            <h1 class="welcome-title">Bonjour, <?= htmlspecialchars($currentUser->prenom) ?> !</h1>
            <p>Bienvenue dans votre espace d'administration</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value"><?= $stats['total_reservations'] ?? 0 ?></div>
                <div class="stat-label">Réservations totales</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-futbol"></i>
                </div>
                <div class="stat-value"><?= $stats['total_terrains'] ?? 0 ?></div>
                <div class="stat-label">Terrains enregistrés</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value"><?= $stats['total_gerants'] ?? 0 ?></div>
                <div class="stat-label">Gérants actifs</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['ca_mois'] ?? 0, 2) ?> DH</div>
                <div class="stat-label">CA ce mois</div>
            </div>
        </div>

        <div class="actions-grid">
            <a href="<?= UrlHelper::url('admin/reservations') ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-list"></i>
                </div>
                <h3 class="action-title">Toutes les réservations</h3>
                <p class="action-description">Consultez et gérez toutes les réservations</p>
            </a>

            <a href="<?= UrlHelper::url('admin/terrains') ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3 class="action-title">Gérer les terrains</h3>
                <p class="action-description">Consultez tous les terrains</p>
            </a>

            <a href="<?= UrlHelper::url('terrain/create') ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h3 class="action-title">Ajouter un terrain</h3>
                <p class="action-description">Créez un nouveau terrain</p>
            </a>

            <a href="<?= UrlHelper::url('admin/gerants') ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="action-title">Gérer les gérants</h3>
                <p class="action-description">Consultez et créez des gérants</p>
            </a>
        </div>
    </div>
</body>
</html>