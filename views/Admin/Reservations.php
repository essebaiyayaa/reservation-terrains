<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gestion des Réservations' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #334155;
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
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
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-title i {
            color: #dc2626;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        /* Stats Cards */
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.total {
            background: #dbeafe;
            color: #1e40af;
        }

        .stat-icon.paye {
            background: #d1fae5;
            color: #065f46;
        }

        .stat-icon.attente {
            background: #fef3c7;
            color: #92400e;
        }

        .stat-icon.annule {
            background: #fee2e2;
            color: #991b1b;
        }

        .stat-content h3 {
            font-size: 1.75rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .stat-content p {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* Filters */
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-select, .form-input {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
        }

        .form-select:focus, .form-input:focus {
            outline: none;
            border-color: #dc2626;
        }

        .btn-filter {
            padding: 0.75rem 1.5rem;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-filter:hover {
            background: #b91c1c;
        }

        /* Reservations Table */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: #f8fafc;
        }

        .table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-paye {
            background: #d1fae5;
            color: #065f46;
        }

        .status-attente {
            background: #fef3c7;
            color: #92400e;
        }

        .status-annule {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Client Info */
        .client-info {
            display: flex;
            flex-direction: column;
        }

        .client-name {
            font-weight: 600;
            color: #1e293b;
        }

        .client-contact {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        /* Terrain Info */
        .terrain-info {
            display: flex;
            flex-direction: column;
        }

        .terrain-name {
            font-weight: 600;
            color: #1e293b;
        }

        .terrain-location {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        /* Date Info */
        .date-info {
            display: flex;
            flex-direction: column;
        }

        .date-main {
            font-weight: 600;
            color: #1e293b;
        }

        .time-slot {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        /* Price */
        .price {
            font-weight: 700;
            color: #dc2626;
            font-size: 1.1rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #94a3b8;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-btn:hover:not(.disabled) {
            background: #f8fafc;
            border-color: #dc2626;
            color: #dc2626;
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filters-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-calendar-check"></i>
                Gestion des Réservations
            </h1>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $total ?? 0 ?></h3>
                    <p>Total réservations</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon paye">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= count(array_filter($reservations ?? [], fn($r) => $r->statut_paiement === 'paye')) ?></h3>
                    <p>Payées</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon attente">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?= count(array_filter($reservations ?? [], fn($r) => $r->statut_paiement === 'en_attente')) ?></h3>
                    <p>En attente</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon annule">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= count(array_filter($reservations ?? [], fn($r) => $r->statut_paiement === 'annule')) ?></h3>
                    <p>Annulées</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" action="<?= UrlHelper::url('admin/reservations') ?>" class="filters-form">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-flag"></i> Statut
                    </label>
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="paye" <?= ($filters['statut'] ?? '') === 'paye' ? 'selected' : '' ?>>Payée</option>
                        <option value="en_attente" <?= ($filters['statut'] ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                        <option value="annule" <?= ($filters['statut'] ?? '') === 'annule' ? 'selected' : '' ?>>Annulée</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar"></i> Date
                    </label>
                    <input type="date" name="date" class="form-input" value="<?= $filters['date'] ?? '' ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt"></i> Terrain
                    </label>
                    <select name="terrain" class="form-select">
                        <option value="">Tous les terrains</option>
                        <?php foreach ($terrains ?? [] as $terrain): ?>
                            <option value="<?= $terrain->id_terrain ?>" 
                                    <?= ($filters['terrainId'] ?? '') == $terrain->id_terrain ? 'selected' : '' ?>>
                                <?= htmlspecialchars($terrain->nom_terrain) ?> - <?= htmlspecialchars($terrain->ville) ?>
                            </option>
                        <?php endforeach; ?>
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

        <!-- Reservations Table -->
        <?php if (empty($reservations)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Aucune réservation trouvée</h3>
                <p>Aucune réservation ne correspond à vos critères de recherche</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Terrain</th>
                            <th>Date & Horaire</th>
                            <th>Prix</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td>#<?= $reservation->id_reservation ?></td>
                                
                                <td>
                                    <div class="client-info">
                                        <span class="client-name">
                                            <?= htmlspecialchars($reservation->client_prenom . ' ' . $reservation->client_nom) ?>
                                        </span>
                                        <span class="client-contact">
                                            <i class="fas fa-envelope"></i> <?= htmlspecialchars($reservation->client_email ?? '') ?>
                                            <?php if (!empty($reservation->telephone)): ?>
                                                <br><i class="fas fa-phone"></i> <?= htmlspecialchars($reservation->telephone) ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="terrain-info">
                                        <span class="terrain-name"><?= htmlspecialchars($reservation->nom_terrain) ?></span>
                                        <span class="terrain-location">
                                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($reservation->ville) ?>
                                        </span>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="date-info">
                                        <span class="date-main">
                                            <i class="fas fa-calendar"></i>
                                            <?= date('d/m/Y', strtotime($reservation->date_reservation)) ?>
                                        </span>
                                        <span class="time-slot">
                                            <i class="fas fa-clock"></i>
                                            <?= date('H:i', strtotime($reservation->heure_debut)) ?> 
                                            - 
                                            <?= date('H:i', strtotime($reservation->heure_fin)) ?>
                                        </span>
                                    </div>
                                </td>
                                
                                <td>
                                    <span class="price"><?= number_format($reservation->prix_total, 2) ?> DH</span>
                                </td>
                                
                                <td>
                                    <?php
                                    $statusClass = match($reservation->statut_paiement) {
                                        'paye' => 'status-paye',
                                        'en_attente' => 'status-attente',
                                        'annule' => 'status-annule',
                                        default => 'status-attente'
                                    };
                                    
                                    $statusLabel = match($reservation->statut_paiement) {
                                        'paye' => 'Payée',
                                        'en_attente' => 'En attente',
                                        'annule' => 'Annulée',
                                        default => 'En attente'
                                    };
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (($totalPages ?? 1) > 1): ?>
                <div class="pagination">
                    <a href="<?= UrlHelper::url('admin/reservations?page=' . (($page ?? 1) - 1) . '&statut=' . urlencode($filters['statut'] ?? '') . '&date=' . urlencode($filters['date'] ?? '') . '&terrain=' . urlencode($filters['terrainId'] ?? '')) ?>" 
                       class="pagination-btn <?= ($page ?? 1) <= 1 ? 'disabled' : '' ?>">
                        <i class="fas fa-chevron-left"></i>
                        Précédent
                    </a>
                    
                    <span>Page <?= $page ?? 1 ?> sur <?= $totalPages ?? 1 ?></span>
                    
                    <a href="<?= UrlHelper::url('admin/reservations?page=' . (($page ?? 1) + 1) . '&statut=' . urlencode($filters['statut'] ?? '') . '&date=' . urlencode($filters['date'] ?? '') . '&terrain=' . urlencode($filters['terrainId'] ?? '')) ?>" 
                       class="pagination-btn <?= ($page ?? 1) >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                        Suivant
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>