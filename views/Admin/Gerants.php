<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gestion des Gérants' ?></title>
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
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc2626;
            text-decoration: none;
        }

        .back-btn {
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
        }

        .back-btn:hover { color: #dc2626; }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 5%;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            color: #1e293b;
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

        .layout {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            height: fit-content;
            position: sticky;
            top: 100px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 1.25rem;
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

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #dc2626;
        }

        .btn {
            width: 100%;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #dc2626;
            color: white;
        }

        .btn-primary:hover {
            background: #b91c1c;
        }

        .gerants-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .gerants-header {
            background: #f8fafc;
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .gerants-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .gerants-list {
            padding: 1rem;
        }

        .gerant-card {
            padding: 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .gerant-card:hover {
            border-color: #dc2626;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .gerant-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .gerant-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }

        .gerant-email {
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .gerant-badge {
            background: #dcfce7;
            color: #166534;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .gerant-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
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
        }

        .btn-delete {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            margin-top: 1rem;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        @media (max-width: 1024px) {
            .layout {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                position: static;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Gestion des Gérants</h1>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <strong>Erreur(s):</strong>
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <div class="layout">
            <div class="form-container">
                <h2 class="form-title">
                    <i class="fas fa-user-plus"></i>
                    Créer un nouveau gérant
                </h2>

                <!-- FORMULAIRE CORRIGÉ: pointe vers admin/gerants avec action=create -->
                <form method="POST" action="<?= UrlHelper::url('admin/gerants') ?>">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="form-group">
                        <label class="form-label">
                            Nom <span class="required">*</span>
                        </label>
                        <input type="text" name="nom" class="form-input" 
                               placeholder="Nom de famille" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Prénom <span class="required">*</span>
                        </label>
                        <input type="text" name="prenom" class="form-input" 
                               placeholder="Prénom" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" name="email" class="form-input" 
                               placeholder="email@exemple.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="telephone" class="form-input" 
                               placeholder="0612345678">
                    </div>

                    <div style="background: #eff6ff; padding: 1rem; border-radius: 8px; 
                                margin-bottom: 1rem; font-size: 0.875rem;">
                        <i class="fas fa-info-circle"></i>
                        Un mot de passe sera généré et envoyé par email
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Créer et envoyer les identifiants
                    </button>
                </form>
            </div>

            <div class="gerants-container">
                <div class="gerants-header">
                    <h2 class="gerants-title">
                        <i class="fas fa-users"></i>
                        Liste des gérants (<?= count($gerants) ?>)
                    </h2>
                </div>

                <div class="gerants-list">
                    <?php if (empty($gerants)): ?>
                        <div style="text-align: center; padding: 3rem; color: #6b7280;">
                            <i class="fas fa-user-tie" style="font-size: 3rem; margin-bottom: 1rem; color: #d1d5db;"></i>
                            <p>Aucun gérant enregistré</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($gerants as $gerant): ?>
                            <div class="gerant-card">
                                <div class="gerant-header">
                                    <div>
                                        <div class="gerant-name">
                                            <?= htmlspecialchars($gerant->prenom . ' ' . $gerant->nom) ?>
                                        </div>
                                        <div class="gerant-email">
                                            <i class="fas fa-envelope"></i>
                                            <?= htmlspecialchars($gerant->email) ?>
                                        </div>
                                        <?php if ($gerant->telephone): ?>
                                            <div class="gerant-email">
                                                <i class="fas fa-phone"></i>
                                                <?= htmlspecialchars($gerant->telephone) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="gerant-badge">Gérant</span>
                                </div>

                                <div class="gerant-stats">
                                    <div class="stat-box">
                                        <div class="stat-value"><?= $gerant->nb_terrains ?></div>
                                        <div class="stat-label">Terrain(s)</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-value"><?= $gerant->nb_reservations ?></div>
                                        <div class="stat-label">Réservation(s)</div>
                                    </div>
                                </div>

                                <button onclick="deleteGerant(<?= $gerant->id_utilisateur ?>)" 
                                        class="btn-delete">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteGerant(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce gérant ?')) return;

            fetch(`<?= UrlHelper::url('admin/gerant/delete/') ?>${id}`, {
                method: 'POST'
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