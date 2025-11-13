
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Terrains - FootBooking</title>
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
        }

        .nav-links a:hover {
            color: #16a34a;
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

        .btn-primary {
            background: #16a34a;
            color: white;
        }

        .btn-primary:hover {
            background: #15803d;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .hero {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 3rem 5%;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .filters {
            background: white;
            padding: 2rem 5%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 3rem;
        }

        .filters-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }

        .filter-group select {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
        }

        .terrains-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 5% 5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .terrains-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .terrain-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            transition: all 0.3s;
            position: relative;
        }

        .terrain-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        .terrain-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
            position: relative;
        }

        .terrain-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255,255,255,0.95);
            color: #16a34a;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .terrain-content {
            padding: 1.5rem;
        }

        .terrain-name {
            font-size: 1.4rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .terrain-location {
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .terrain-details {
            margin: 1rem 0;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: #f0fdf4;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
        }

        .terrain-price {
            background: #f0fdf4;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            margin: 1rem 0;
        }

        .price-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #16a34a;
        }

        .gerant-info {
            padding: 1rem;
            background: #f9fafb;
            border-radius: 8px;
            margin: 1rem 0;
            font-size: 0.875rem;
        }

        .terrain-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-reserve {
            flex: 1;
            background: #16a34a;
            color: white;
            padding: 0.9rem;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-details {
            background: white;
            color: #16a34a;
            border: 2px solid #16a34a;
            padding: 0.9rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .admin-actions {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-edit {
            background: rgba(59, 130, 246, 0.9);
            color: white;
        }

        .btn-delete {
            background: rgba(220, 38, 38, 0.9);
            color: white;
        }

        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .no-results i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .terrains-grid {
                grid-template-columns: 1fr;
            }
            
            .filters-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
   

    <section class="hero">
        <h1><i class="fas fa-futbol"></i> Nos Terrains</h1>
        <p>Découvrez nos terrains de football disponibles</p>
    </section>

    <section class="filters">
        <form method="GET" action="<?= UrlHelper::url('terrains') ?>" class="filters-container">
            <div class="filter-group">
                <label for="taille"><i class="fas fa-ruler-combined"></i> Taille</label>
                <select id="taille" name="taille">
                    <option value="">Toutes les tailles</option>
                    <option value="Mini foot" <?= $filters['taille'] === 'Mini foot' ? 'selected' : '' ?>>Mini foot</option>
                    <option value="Terrain moyen" <?= $filters['taille'] === 'Terrain moyen' ? 'selected' : '' ?>>Terrain moyen</option>
                    <option value="Grand terrain" <?= $filters['taille'] === 'Grand terrain' ? 'selected' : '' ?>>Grand terrain</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="type"><i class="fas fa-layer-group"></i> Type</label>
                <select id="type" name="type">
                    <option value="">Tous les types</option>
                    <option value="Gazon naturel" <?= $filters['type'] === 'Gazon naturel' ? 'selected' : '' ?>>Gazon naturel</option>
                    <option value="Gazon artificiel" <?= $filters['type'] === 'Gazon artificiel' ? 'selected' : '' ?>>Gazon artificiel</option>
                    <option value="Terrain dur" <?= $filters['type'] === 'Terrain dur' ? 'selected' : '' ?>>Terrain dur</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="ville"><i class="fas fa-city"></i> Ville</label>
                <select id="ville" name="ville">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?= htmlspecialchars($ville) ?>" <?= $filters['ville'] === $ville ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ville) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </form>
    </section>

    <section class="terrains-section">
        <div class="section-header">
            <h2>Terrains Disponibles (<?= count($terrains) ?>)</h2>
            <?php if ($currentUser && $currentUser->role === 'admin'): ?>
                <a href="<?= UrlHelper::url('terrain/add') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un Terrain
                </a>
            <?php endif; ?>
        </div>

        <?php if (empty($terrains)): ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h2>Aucun terrain trouvé</h2>
                <p>Essayez de modifier vos filtres</p>
            </div>
        <?php else: ?>
            <div class="terrains-grid">
                <?php foreach ($terrains as $terrain): ?>
                    <div class="terrain-card">
                        <?php if ($currentUser && $currentUser->role === 'admin'): ?>
                            <div class="admin-actions">
                                <a href="<?= UrlHelper::url('terrain/edit/' . $terrain->id_terrain) ?>" class="btn-icon btn-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteTerrain(<?= $terrain->id_terrain ?>)" class="btn-icon btn-delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        <?php endif; ?>

                        <div class="terrain-image">
                            <i class="fas fa-futbol"></i>
                            <span class="terrain-badge"><?= htmlspecialchars($terrain->taille) ?></span>
                        </div>

                        <div class="terrain-content">
                            <h3 class="terrain-name"><?= htmlspecialchars($terrain->nom_terrain) ?></h3>
                            <p class="terrain-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($terrain->ville) ?>
                            </p>

                            <div class="terrain-details">
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <small>Type</small><br>
                                        <strong><?= htmlspecialchars($terrain->type) ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="terrain-price">
                                <p style="color: #6b7280; font-size: 0.9rem;">Prix par heure</p>
                                <p class="price-value"><?= number_format($terrain->prix_heure, 0) ?> DH</p>
                            </div>

                            <?php if ($terrain->gerant_prenom): ?>
                                <div class="gerant-info">
                                    <i class="fas fa-user-tie"></i>
                                    <strong>Gérant:</strong> <?= htmlspecialchars($terrain->gerant_prenom . ' ' . $terrain->gerant_nom) ?>
                                </div>
                            <?php endif; ?>

                            <div class="terrain-actions">
                                <a href="<?= UrlHelper::url('terrain/id/' . $terrain->id_terrain) ?>" class="btn-reserve">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <script>
        function deleteTerrain(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce terrain ?')) return;

            fetch(`<?= UrlHelper::url('terrain/delete/') ?>${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Erreur lors de la suppression');
                }
            })
            .catch(err => alert('Erreur réseau'));
        }
    </script>
</body>
</html>
<?php include '../views/footer.php'; ?>