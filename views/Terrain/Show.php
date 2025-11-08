<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($terrain->nom_terrain) ?> - FootBooking</title>
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
            color: #16a34a;
        }

        .terrain-header {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .terrain-hero {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .terrain-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .terrain-hero .location {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        .terrain-details {
            padding: 2rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-card {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .detail-icon {
            width: 50px;
            height: 50px;
            background: #f0fdf4;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }

        .detail-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .price-card {
            background: #f0fdf4;
            border: 2px solid #16a34a;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 2rem;
        }

        .price-label {
            font-size: 1rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .price-value {
            font-size: 3rem;
            font-weight: 700;
            color: #16a34a;
        }

        .options-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .option-item {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .option-name {
            font-weight: 600;
            color: #1e293b;
        }

        .option-price {
            color: #16a34a;
            font-weight: 700;
        }

        .gerant-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .gerant-card {
            background: #f0fdf4;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #16a34a;
        }

        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 1rem;
        }

        .btn-primary {
            background: #16a34a;
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background: #15803d;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #3b82f6;
            color: white;
        }

        .btn-secondary:hover {
            background: #2563eb;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        @media (max-width: 768px) {
            .terrain-hero h1 {
                font-size: 1.75rem;
            }
            
            .price-value {
                font-size: 2rem;
            }

            .actions {
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
        <a href="/terrains" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Retour aux terrains
        </a>

        <div class="terrain-header">
            <div class="terrain-hero">
                <h1><i class="fas fa-futbol"></i> <?= htmlspecialchars($terrain->nom_terrain) ?></h1>
                <p class="location">
                    <i class="fas fa-map-marker-alt"></i>
                    <?= htmlspecialchars($terrain->adresse . ', ' . $terrain->ville) ?>
                </p>
            </div>

            <div class="terrain-details">
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-ruler-combined"></i>
                        </div>
                        <div class="detail-label">Taille</div>
                        <div class="detail-value"><?= htmlspecialchars($terrain->taille) ?></div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="detail-label">Type de surface</div>
                        <div class="detail-value"><?= htmlspecialchars($terrain->type) ?></div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-city"></i>
                        </div>
                        <div class="detail-label">Ville</div>
                        <div class="detail-value"><?= htmlspecialchars($terrain->ville) ?></div>
                    </div>
                </div>

                <div class="price-card">
                    <div class="price-label">Prix de location</div>
                    <div class="price-value"><?= number_format($terrain->prix_heure, 0) ?> DH</div>
                    <div style="color: #6b7280; margin-top: 0.5rem;">par heure</div>
                </div>

                <div class="actions">
                    <?php if ($currentUser): ?>
                        <a href="/reservation/create/<?= $terrain->id_terrain ?>" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i>
                            Réserver maintenant
                        </a>
                    <?php else: ?>
                        <a href="/login?redirect=/terrain/id/<?= $terrain->id_terrain ?>" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            Connectez-vous pour réserver
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Options Supplémentaires -->
        <?php if (!empty($options)): ?>
            <div class="options-section">
                <h2 class="section-title">
                    <i class="fas fa-plus-circle"></i>
                    Options Supplémentaires Disponibles
                </h2>
                <div class="options-grid">
                    <?php foreach ($options as $option): ?>
                        <div class="option-item">
                            <div class="option-name">
                                <i class="fas fa-check-circle" style="color: #16a34a;"></i>
                                <?= htmlspecialchars($option->nom_option) ?>
                            </div>
                            <div class="option-price">
                                <?= $option->prix > 0 ? number_format($option->prix, 2) . ' DH' : 'Gratuit' ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Informations Gérant -->
        <?php if ($terrain->gerant_prenom): ?>
            <div class="gerant-section">
                <h2 class="section-title">
                    <i class="fas fa-user-tie"></i>
                    Contact du Gérant
                </h2>
                <div class="gerant-card">
                    <h3 style="margin-bottom: 1rem; color: #1e293b;">
                        <?= htmlspecialchars($terrain->gerant_prenom . ' ' . $terrain->gerant_nom) ?>
                    </h3>
                    <p style="color: #6b7280; margin-bottom: 0.5rem;">
                        <i class="fas fa-envelope"></i>
                        <?= htmlspecialchars($terrain->gerant_email) ?>
                    </p>
                    <?php if (!empty($terrain->gerant_telephone)): ?>
                        <p style="color: #6b7280;">
                            <i class="fas fa-phone"></i>
                            <?= htmlspecialchars($terrain->gerant_telephone) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>