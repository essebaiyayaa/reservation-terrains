<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Modifier un Terrain' ?></title>
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
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc2626;
            text-decoration: none;
        }

        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 5%;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .back-btn:hover {
            color: #dc2626;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }

        .required {
            color: #dc2626;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #dc2626;
        }

        .form-help {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: #dc2626;
            color: white;
        }

        .btn-primary:hover {
            background: #b91c1c;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .info-box p {
            margin: 0;
            color: #1e40af;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>


    <div class="container">
        <?php 
        // Extract terrain data 
        if (is_object($terrain)) {
            $terrainId = $terrain->id_terrain;
            $nomTerrain = $terrain->nom_terrain;
            $adresse = $terrain->adresse;
            $ville = $terrain->ville;
            $description = $terrain->description ?? '';
            $taille = $terrain->taille;
            $type = $terrain->type;
            $prixHeure = $terrain->prix_heure;
            $idUtilisateur = $terrain->id_utilisateur ?? null;
        } else {
            $terrainId = $terrain['id_terrain'];
            $nomTerrain = $terrain['nom_terrain'];
            $adresse = $terrain['adresse'];
            $ville = $terrain['ville'];
            $description = $terrain['description'] ?? '';
            $taille = $terrain['taille'];
            $type = $terrain['type'];
            $prixHeure = $terrain['prix_heure'];
            $idUtilisateur = $terrain['id_utilisateur'] ?? null;
        }
        ?>

        <a href="<?= UrlHelper::url('terrain/id/' . $terrainId) ?>" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Retour aux détails du terrain
        </a>

        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-edit"></i>
                Modifier le terrain
            </h1>
            <p style="color: #6b7280;">
                <?= htmlspecialchars($nomTerrain) ?> - <?= htmlspecialchars($ville) ?>
            </p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Erreur(s) détectée(s):</strong>
                <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (isset($isAdmin) && $isAdmin): ?>
            <div class="info-box">
                <p>
                    <i class="fas fa-info-circle"></i>
                    <strong>Mode Administrateur :</strong> Vous pouvez modifier tous les champs incluant l'assignation du gérant.
                </p>
            </div>
        <?php else: ?>
            <div class="info-box">
                <p>
                    <i class="fas fa-info-circle"></i>
                    <strong>Mode Gérant :</strong> Vous pouvez modifier le nom, l'adresse, la description et le prix de votre terrain.
                </p>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="<?= UrlHelper::url('terrain/edit/' . $terrainId) ?>">
                
                <!-- Informations générales -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informations générales
                    </h2>

                    <div class="form-group">
                        <label class="form-label">
                            Nom du terrain <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nom_terrain" 
                            class="form-input" 
                            placeholder="Ex: Terrain Central"
                            value="<?= htmlspecialchars($nomTerrain) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Adresse complète <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="adresse" 
                            class="form-input" 
                            placeholder="Ex: 123 Rue du Stade"
                            value="<?= htmlspecialchars($adresse) ?>"
                            required
                        >
                    </div>

                    <?php if (isset($isAdmin) && $isAdmin): ?>
                        <div class="form-group">
                            <label class="form-label">
                                Ville <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="ville" 
                                class="form-input" 
                                placeholder="Ex: Casablanca"
                                value="<?= htmlspecialchars($ville) ?>"
                                required
                            >
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label class="form-label">Ville</label>
                            <input 
                                type="text" 
                                class="form-input" 
                                value="<?= htmlspecialchars($ville) ?>"
                                disabled
                                style="background: #f3f4f6; cursor: not-allowed;"
                            >
                            <p class="form-help">La ville ne peut pas être modifiée par les gérants</p>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea 
                            name="description" 
                            class="form-textarea" 
                            placeholder="Décrivez les particularités du terrain..."
                        ><?= htmlspecialchars($description) ?></textarea>
                        <p class="form-help">Description optionnelle du terrain</p>
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-sliders-h"></i>
                        Caractéristiques du terrain
                    </h2>

                    <?php if (isset($isAdmin) && $isAdmin): ?>
                        <div class="form-group">
                            <label class="form-label">
                                Taille du terrain <span class="required">*</span>
                            </label>
                            <select name="taille" class="form-select" required>
                                <option value="">Sélectionnez la taille</option>
                                <option value="Mini foot" <?= $taille === 'Mini foot' ? 'selected' : '' ?>>Mini foot (5x5)</option>
                                <option value="Terrain moyen" <?= $taille === 'Terrain moyen' ? 'selected' : '' ?>>Terrain moyen (7x7)</option>
                                <option value="Grand terrain" <?= $taille === 'Grand terrain' ? 'selected' : '' ?>>Grand terrain (11x11)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Type de surface <span class="required">*</span>
                            </label>
                            <select name="type" class="form-select" required>
                                <option value="">Sélectionnez le type</option>
                                <option value="Gazon naturel" <?= $type === 'Gazon naturel' ? 'selected' : '' ?>>Gazon naturel</option>
                                <option value="Gazon artificiel" <?= $type === 'Gazon artificiel' ? 'selected' : '' ?>>Gazon artificiel</option>
                                <option value="Terrain dur" <?= $type === 'Terrain dur' ? 'selected' : '' ?>>Terrain dur</option>
                                <option value="Parquet" <?= $type === 'Parquet' ? 'selected' : '' ?>>Parquet</option>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label class="form-label">Taille</label>
                            <input 
                                type="text" 
                                class="form-input" 
                                value="<?= htmlspecialchars($taille) ?>"
                                disabled
                                style="background: #f3f4f6; cursor: not-allowed;"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Type de surface</label>
                            <input 
                                type="text" 
                                class="form-input" 
                                value="<?= htmlspecialchars($type) ?>"
                                disabled
                                style="background: #f3f4f6; cursor: not-allowed;"
                            >
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label">
                            Prix par heure (DH) <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="prix_heure" 
                            class="form-input" 
                            placeholder="Ex: 150"
                            value="<?= htmlspecialchars($prixHeure) ?>"
                            min="0"
                            step="10"
                            required
                        >
                        <p class="form-help">Le prix de location du terrain par heure en Dirhams</p>
                    </div>
                </div>

                <!-- Assignation gérant (Admin seulement) -->
                <?php if (isset($isAdmin) && $isAdmin): ?>
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-user-tie"></i>
                            Gérant assigné
                        </h2>

                        <div class="form-group">
                            <label class="form-label">Assigner à un gérant</label>
                            <select name="id_gerant" class="form-select">
                                <option value="">Aucun gérant</option>
                                <?php if (!empty($gerants)): ?>
                                    <?php foreach ($gerants as $gerant): ?>
                                        <option value="<?= $gerant->id_utilisateur ?>" 
                                                <?= $idUtilisateur == $gerant->id_utilisateur ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($gerant->prenom . ' ' . $gerant->nom . ' (' . $gerant->email . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p class="form-help">Le gérant sera responsable de la gestion de ce terrain</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Gestion des options (lien pour les gérants) -->
                <?php if (!isset($isAdmin) || !$isAdmin): ?>
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-plus-circle"></i>
                            Options supplémentaires
                        </h2>
                        <p style="color: #6b7280; margin-bottom: 1rem;">
                            Gérez les options supplémentaires (ballons, vestiaires, etc.) pour ce terrain.
                        </p>
                        <a href="<?= UrlHelper::url('terrain/options/' . $terrainId) ?>" class="btn btn-warning">
                            <i class="fas fa-cog"></i>
                            Gérer les options
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="<?= UrlHelper::url('terrain/id/' . $terrainId) ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>