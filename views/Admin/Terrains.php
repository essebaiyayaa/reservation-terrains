<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gestion des Terrains' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
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

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
        }

        .back-btn:hover {
            color: #dc2626;
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 5%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            color: #1e293b;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #dc2626;
            color: white;
        }

        .btn-primary:hover {
            background: #b91c1c;
        }

        /* Filters */
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .filters-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .form-select {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn-filter {
            padding: 0.75rem 1.5rem;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Terrains Grid */
        .terrains-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .terrain-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: all 0.3s;
        }

        .terrain-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .terrain-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 1.5rem;
        }

        .terrain-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .terrain-location {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .terrain-body {
            padding: 1.5rem;
        }

        .terrain-details {
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
        }

        .detail-icon {
            width: 35px;
            height: 35px;
            background: #fee2e2;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
            font-size: 0.875rem;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-weight: 600;
            color: #1e293b;
        }

        .terrain-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 1rem 0;
        }

        .stat-box {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #dc2626;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .gerant-info {
            padding: 1rem;
            background: #f0fdf4;
            border-left: 3px solid #16a34a;
            border-radius: 4px;
            margin-top: 1rem;
        }

        .gerant-info.no-gerant {
            background: #fef3c7;
            border-left-color: #f59e0b;
        }

        .gerant-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .gerant-name {
            font-weight: 600;
            color: #1e293b;
        }

        .gerant-email {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .terrain-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-action {
            flex: 1;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-view {
            background: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background: #2563eb;
        }

        .btn-edit {
            background: #f59e0b;
            color: white;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .pagination-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            color: #374151;
        }

        .pagination-btn:hover:not(.disabled) {
            background: #f8fafc;
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .filters-form {
                grid-template-columns: 1fr;
            }

            .terrains-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>


    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Gestion des Terrains</h1>
            <a href="<?= UrlHelper::url('terrain/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Ajouter un terrain
            </a>
        </div>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" action="<?= UrlHelper::url('admin/terrains') ?>" class="filters-form">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-city"></i> Ville
                    </label>
                    <select name="ville" class="form-select">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= htmlspecialchars($ville) ?>" 
                                    <?= ($filters['ville'] ?? '') === $ville ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ville) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-layer-group"></i> Type
                    </label>
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="Gazon naturel" <?= ($filters['type'] ?? '') === 'Gazon naturel' ? 'selected' : '' ?>>Gazon naturel</option>
                        <option value="Gazon artificiel" <?= ($filters['type'] ?? '') === 'Gazon artificiel' ? 'selected' : '' ?>>Gazon artificiel</option>
                        <option value="Terrain dur" <?= ($filters['type'] ?? '') === 'Terrain dur' ? 'selected' : '' ?>>Terrain dur</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <?php if (empty($terrains)): ?>
            <div class="empty-state">
                <i class="fas fa-map-marked-alt"></i>
                <h2>Aucun terrain trouvé</h2>
                <p style="color: #6b7280; margin-top: 0.5rem;">
                    Essayez de modifier vos filtres ou ajoutez un nouveau terrain
                </p>
            </div>
        <?php else: ?>
            <div class="terrains-grid">
                <?php foreach ($terrains as $terrain): ?>
                    <?php 
                    // Support both object and array format
                    if (is_object($terrain)) {
                        $terrainId = $terrain->id_terrain;
                        $nomTerrain = $terrain->nom_terrain;
                        $ville = $terrain->ville;
                        $adresse = $terrain->adresse ?? '';
                        $taille = $terrain->taille;
                        $type = $terrain->type;
                        $prixHeure = $terrain->prix_heure;
                        $gerantPrenom = $terrain->gerant_prenom ?? null;
                        $gerantNom = $terrain->gerant_nom ?? null;
                        $gerantEmail = $terrain->gerant_email ?? null;
                        $nbReservations = $terrain->nb_reservations ?? 0;
                    } else {
                        $terrainId = $terrain['id_terrain'];
                        $nomTerrain = $terrain['nom_terrain'];
                        $ville = $terrain['ville'];
                        $adresse = $terrain['adresse'] ?? '';
                        $taille = $terrain['taille'];
                        $type = $terrain['type'];
                        $prixHeure = $terrain['prix_heure'];
                        $gerantPrenom = $terrain['gerant_prenom'] ?? null;
                        $gerantNom = $terrain['gerant_nom'] ?? null;
                        $gerantEmail = $terrain['gerant_email'] ?? null;
                        $nbReservations = $terrain['nb_reservations'] ?? 0;
                    }
                    ?>
                    <div class="terrain-card">
                        <!-- Header -->
                        <div class="terrain-header">
                            <div class="terrain-name"><?= htmlspecialchars($nomTerrain) ?></div>
                            <div class="terrain-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($ville) ?>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="terrain-body">
                            <!-- Details -->
                            <div class="terrain-details">
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Adresse</div>
                                        <div class="detail-value"><?= htmlspecialchars($adresse) ?></div>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-ruler-combined"></i>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Taille</div>
                                        <div class="detail-value"><?= htmlspecialchars($taille) ?></div>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Type de surface</div>
                                        <div class="detail-value"><?= htmlspecialchars($type) ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="terrain-stats">
                                <div class="stat-box">
                                    <div class="stat-value"><?= number_format($prixHeure, 0) ?> DH</div>
                                    <div class="stat-label">Prix/heure</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value"><?= $nbReservations ?></div>
                                    <div class="stat-label">Réservations</div>
                                </div>
                            </div>

                            <!-- Gerant Info -->
                            <div class="gerant-info <?= !$gerantPrenom ? 'no-gerant' : '' ?>">
                                <div class="gerant-label">Gérant assigné</div>
                                <?php if ($gerantPrenom): ?>
                                    <div class="gerant-name">
                                        <i class="fas fa-user-tie"></i>
                                        <?= htmlspecialchars($gerantPrenom . ' ' . $gerantNom) ?>
                                    </div>
                                    <div class="gerant-email">
                                        <i class="fas fa-envelope"></i>
                                        <?= htmlspecialchars($gerantEmail) ?>
                                    </div>
                                <?php else: ?>
                                    <div class="gerant-name">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Aucun gérant assigné
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <div class="terrain-actions">
                                <a href="<?= UrlHelper::url('terrain/id/' . $terrainId) ?>" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <a href="<?= UrlHelper::url('terrain/edit/' . $terrainId) ?>" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <a href="<?= UrlHelper::url('admin/terrains?page=' . ($page - 1) . '&ville=' . urlencode($filters['ville'] ?? '') . '&type=' . urlencode($filters['type'] ?? '')) ?>" 
                       class="pagination-btn <?= $page <= 1 ? 'disabled' : '' ?>">
                        <i class="fas fa-chevron-left"></i>
                        Précédent
                    </a>
                    
                    <span>Page <?= $page ?> sur <?= $totalPages ?></span>
                    
                    <a href="<?= UrlHelper::url('admin/terrains?page=' . ($page + 1) . '&ville=' . urlencode($filters['ville'] ?? '') . '&type=' . urlencode($filters['type'] ?? '')) ?>" 
                       class="pagination-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        Suivant
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>