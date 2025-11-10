<?php 
$title = 'Gestion des Tournois - FootBooking';

?>

<style>
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
        font-weight: 600;
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
        transition: all 0.3s;
    }

    .btn-filter:hover {
        background: #b91c1c;
    }

    /* Tournois Grid */
    .tournois-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 1.5rem;
    }

    .tournoi-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s;
    }

    .tournoi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .tournoi-header {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: start;
    }

    .tournoi-title {
        flex: 1;
    }

    .tournoi-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .tournoi-id {
        font-size: 0.875rem;
        opacity: 0.8;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .status-badge.ouvert {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .status-badge.en-cours {
        background: #fef3c7;
        color: #d97706;
    }

    .status-badge.termine {
        background: #e5e7eb;
        color: #6b7280;
    }

    .status-badge.annule {
        background: #fee2e2;
        color: #dc2626;
    }

    .tournoi-body {
        padding: 1.5rem;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-icon {
        width: 40px;
        height: 40px;
        background: #fee2e2;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        font-size: 0.9rem;
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .detail-value.price {
        color: #16a34a;
        font-size: 1.1rem;
    }

    .detail-value.free {
        color: #16a34a;
    }

    .places-section {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .places-section.warning {
        background: #fef3c7;
    }

    .places-section.full {
        background: #fee2e2;
    }

    .places-info {
        flex: 1;
    }

    .places-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .places-count {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }

    .places-count.warning {
        color: #d97706;
    }

    .places-count.full {
        color: #dc2626;
    }

    .progress-bar {
        width: 100px;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #16a34a;
        transition: width 0.3s;
    }

    .progress-fill.warning {
        background: #f59e0b;
    }

    .progress-fill.full {
        background: #dc2626;
    }

    .gerant-info {
        margin-top: 1rem;
        padding: 1rem;
        background: #f0fdf4;
        border-left: 3px solid #16a34a;
        border-radius: 4px;
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
        margin-bottom: 0.25rem;
    }

    .gerant-contact {
        font-size: 0.875rem;
        color: #64748b;
    }

    .description-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .description-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .description-text {
        color: #4b5563;
        line-height: 1.6;
        font-size: 0.9rem;
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

    .empty-state h3 {
        font-size: 1.5rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .filters-form {
            grid-template-columns: 1fr;
        }

        .tournois-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Gestion des Tournois</h1>
    </div>

    <!-- Filters -->
    <div class="filters">
        <form method="GET" action="<?= UrlHelper::url('admin/tournois') ?>" class="filters-form">
            <div class="form-group">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="ouvert" <?= ($filters['statut'] ?? '') === 'ouvert' ? 'selected' : '' ?>>Ouvert</option>
                    <option value="en cours" <?= ($filters['statut'] ?? '') === 'en cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="terminé" <?= ($filters['statut'] ?? '') === 'terminé' ? 'selected' : '' ?>>Terminé</option>
                    <option value="annulé" <?= ($filters['statut'] ?? '') === 'annulé' ? 'selected' : '' ?>>Annulé</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Ville</label>
                <select name="ville" class="form-select">
                    <option value="">Toutes les villes</option>
                    <?php if (isset($villes)): ?>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= htmlspecialchars($ville) ?>" 
                                    <?= ($filters['ville'] ?? '') === $ville ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ville) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

    <?php if (empty($tournois)): ?>
        <div class="empty-state">
            <i class="fas fa-trophy"></i>
            <h3>Aucun tournoi trouvé</h3>
            <p style="color: #6b7280; margin-top: 0.5rem;">
                Aucun tournoi ne correspond à vos critères de recherche
            </p>
        </div>
    <?php else: ?>
        <div class="tournois-grid">
            <?php foreach ($tournois as $tournoi): ?>
                <?php
                // Handle both object and array format
                if (is_object($tournoi)) {
                    $id = $tournoi->id_tournoi;
                    $nom = $tournoi->nom_tournoi;
                    $statut = strtolower($tournoi->statut);
                    $dateDebut = $tournoi->date_debut;
                    $dateFin = $tournoi->date_fin;
                    $terrain = $tournoi->nom_terrain;
                    $ville = $tournoi->ville;
                    $gerantPrenom = $tournoi->prenom_gerant ?? '';
                    $gerantNom = $tournoi->nom_gerant ?? '';
                    $gerantEmail = $tournoi->email_gerant ?? '';
                    $prix = $tournoi->prix_inscription ?? 0;
                    $maxEquipes = $tournoi->nombre_max_equipes;
                    $placesDisponibles = $tournoi->places_disponibles ?? $maxEquipes;
                    $description = $tournoi->description ?? '';
                } else {
                    $id = $tournoi['id_tournoi'];
                    $nom = $tournoi['nom_tournoi'];
                    $statut = strtolower($tournoi['statut']);
                    $dateDebut = $tournoi['date_debut'];
                    $dateFin = $tournoi['date_fin'];
                    $terrain = $tournoi['nom_terrain'];
                    $ville = $tournoi['ville'];
                    $gerantPrenom = $tournoi['prenom_gerant'] ?? '';
                    $gerantNom = $tournoi['nom_gerant'] ?? '';
                    $gerantEmail = $tournoi['email_gerant'] ?? '';
                    $prix = $tournoi['prix_inscription'] ?? 0;
                    $maxEquipes = $tournoi['nombre_max_equipes'];
                    $placesDisponibles = $tournoi['places_disponibles'] ?? $maxEquipes;
                    $description = $tournoi['description'] ?? '';
                }

                $equipesInscrites = $maxEquipes - $placesDisponibles;
                $fillPercentage = ($equipesInscrites / $maxEquipes) * 100;
                
                $statusClass = str_replace([' ', 'é'], ['', 'e'], $statut);
                $placesClass = '';
                $progressClass = '';
                
                if ($placesDisponibles <= 0) {
                    $placesClass = 'full';
                    $progressClass = 'full';
                } elseif ($placesDisponibles <= 3) {
                    $placesClass = 'warning';
                    $progressClass = 'warning';
                }
                ?>
                <div class="tournoi-card">
                    <!-- Header -->
                    <div class="tournoi-header">
                        <div class="tournoi-title">
                            <div class="tournoi-name"><?= htmlspecialchars($nom) ?></div>
                            <div class="tournoi-id">ID: #<?= $id ?></div>
                        </div>
                        <span class="status-badge <?= $statusClass ?>">
                            <?= htmlspecialchars($statut) ?>
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="tournoi-body">
                        <!-- Details -->
                        <div class="detail-row">
                            <div class="detail-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Dates</div>
                                <div class="detail-value">
                                    <?= date('d/m/Y', strtotime($dateDebut)) ?>
                                    <?php if ($dateDebut != $dateFin): ?>
                                        - <?= date('d/m/Y', strtotime($dateFin)) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Lieu</div>
                                <div class="detail-value"><?= htmlspecialchars($terrain) ?>, <?= htmlspecialchars($ville) ?></div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Prix d'inscription</div>
                                <?php if ($prix > 0): ?>
                                    <div class="detail-value price"><?= number_format($prix, 2) ?> DH</div>
                                <?php else: ?>
                                    <div class="detail-value free">Gratuit</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Places disponibles -->
                        <div class="places-section <?= $placesClass ?>">
                            <div class="places-info">
                                <div class="places-label">Places disponibles</div>
                                <div class="places-count <?= $placesClass ?>">
                                    <?= $placesDisponibles ?> / <?= $maxEquipes ?>
                                </div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill <?= $progressClass ?>" 
                                     style="width: <?= $fillPercentage ?>%"></div>
                            </div>
                        </div>

                        <!-- Gerant Info -->
                        <div class="gerant-info">
                            <div class="gerant-label">Organisateur</div>
                            <div class="gerant-name">
                                <?= htmlspecialchars($gerantPrenom . ' ' . $gerantNom) ?>
                            </div>
                            <div class="gerant-contact">
                                <i class="fas fa-envelope"></i>
                                <?= htmlspecialchars($gerantEmail) ?>
                            </div>
                        </div>

                        <!-- Description -->
                        <?php if (!empty($description)): ?>
                            <div class="description-section">
                                <div class="description-label">Description</div>
                                <div class="description-text">
                                    <?= nl2br(htmlspecialchars($description)) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

