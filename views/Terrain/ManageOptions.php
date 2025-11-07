<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Options - <?= htmlspecialchars($terrain->nom_terrain) ?></title>
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
            color: #16a34a;
            text-decoration: none;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 5%;
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

        .terrain-info {
            background: #f0fdf4;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
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

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 2rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
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

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #16a34a;
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
            background: #16a34a;
            color: white;
        }

        .btn-primary:hover {
            background: #15803d;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .options-list {
            list-style: none;
        }

        .option-item {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .option-item:hover {
            background: #f3f4f6;
        }

        .option-info {
            flex: 1;
        }

        .option-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .option-price {
            color: #16a34a;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="/" class="logo">
                <i class="fas fa-futbol"></i> FootBooking
            </a>
            <a href="/gerant/dashboard" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au tableau de bord
            </a>
        </nav>
    </header>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-cog"></i>
                Gérer les Options Supplémentaires
            </h1>
            <div class="terrain-info">
                <strong>Terrain:</strong> <?= htmlspecialchars($terrain->nom_terrain) ?> - 
                <?= htmlspecialchars($terrain->ville) ?>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Erreur(s):</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="grid">
            <!-- Ajouter une option -->
            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter une Option
                </h2>
                <form method="POST" action="/terrain/options/<?= $terrain->id_terrain ?>">
                    <div class="form-group">
                        <label class="form-label">
                            Nom de l'option <span style="color: #dc2626;">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nom_option" 
                            class="form-input" 
                            placeholder="Ex: Ballon, Vestiaires, Douches"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Prix (DH) <span style="color: #dc2626;">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="prix" 
                            class="form-input" 
                            placeholder="0.00"
                            min="0"
                            step="0.01"
                            required
                        >
                        <small style="color: #6b7280; margin-top: 0.25rem; display: block;">
                            Entrez 0 pour une option gratuite
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Ajouter l'Option
                    </button>
                </form>
            </div>

            <!-- Liste des options -->
            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-list"></i>
                    Options Actuelles (<?= count($options) ?>)
                </h2>

                <?php if (empty($options)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Aucune option</h3>
                        <p>Ajoutez votre première option supplémentaire</p>
                    </div>
                <?php else: ?>
                    <ul class="options-list">
                        <?php foreach ($options as $option): ?>
                            <li class="option-item">
                                <div class="option-info">
                                    <div class="option-name">
                                        <i class="fas fa-check-circle" style="color: #16a34a;"></i>
                                        <?= htmlspecialchars($option->nom_option) ?>
                                    </div>
                                    <div class="option-price">
                                        <?= $option->prix > 0 ? number_format($option->prix, 2) . ' DH' : 'Gratuit' ?>
                                    </div>
                                </div>
                                <button 
                                    onclick="deleteOption(<?= $option->id_option ?>)" 
                                    class="btn btn-danger"
                                    style="padding: 0.5rem 1rem;"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function deleteOption(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette option ?')) {
                return;
            }

            fetch(`/terrain/option/delete/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Erreur lors de la suppression');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Erreur réseau');
            });
        }
    </script>
</body>
</html>