<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mes Réservations' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #334155;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            text-align: center;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        .hero-content h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .hero-content p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto 2rem;
            padding: 0 2rem;
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
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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

        .stat-icon.revenue {
            background: #fce7f3;
            color: #9f1239;
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
        }

        .form-select:focus, .form-input:focus {
            outline: none;
            border-color: #16a34a;
        }

        .btn-filter {
            padding: 0.75rem 1.5rem;
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-filter:hover {
            background: #15803d;
        }

        /* Reservations Grid */
        .reservations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 1.5rem;
        }

        .reservation-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s;
        }

        .reservation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .reservation-header {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .reservation-id {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .reservation-date {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 0.25rem;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-paye {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .status-attente {
            background: #fef3c7;
            color: #92400e;
        }

        .status-annule {
            background: #fee2e2;
            color: #991b1b;
        }

        .reservation-body {
            padding: 1.5rem;
        }

        .info-section {
            margin-bottom: 1.5rem;
        }

        .info-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
        }

        .info-icon {
            width: 35px;
            height: 35px;
            background: #f0fdf4;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            font-size: 0.875rem;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.75rem;
            color: #64748b;
        }

        .info-value {
            font-weight: 600;
            color: #1e293b;
            margin-top: 0.125rem;
        }

        .client-card {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border-left: 3px solid #16a34a;
        }

        .client-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .client-contact {
            font-size: 0.875rem;
            color: #64748b;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .price-section {
            background: #f0fdf4;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            margin-top: 1rem;
        }

        .price-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .price-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #16a34a;
        }

        .reservation-actions {
            display: flex;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
        }

        .btn-action {
            flex: 1;
            padding: 0.75rem;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
        }

        .btn-payer {
            background: #16a34a;
            color: white;
        }

        .btn-payer:hover {
            background: #15803d;
        }

        .btn-annuler {
            background: #dc2626;
            color: white;
        }

        .btn-annuler:hover {
            background: #b91c1c;
        }

        .btn-attente {
            background: #f59e0b;
            color: white;
        }

        .btn-attente:hover {
            background: #d97706;
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

        /* Modal for status change */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .modal-header i {
            font-size: 2rem;
            color: #16a34a;
        }

        .modal-header h3 {
            font-size: 1.5rem;
            color: #1e293b;
        }

        .modal-body {
            margin-bottom: 1.5rem;
        }

        .status-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .status-option {
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .status-option:hover {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        .status-option input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .status-option-content {
            flex: 1;
        }

        .status-option-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .status-option-desc {
            font-size: 0.875rem;
            color: #64748b;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-modal {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-cancel {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-cancel:hover {
            background: #d1d5db;
        }

        .btn-confirm {
            background: #16a34a;
            color: white;
        }

        .btn-confirm:hover {
            background: #15803d;
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 1.75rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filters-form {
                grid-template-columns: 1fr;
            }

            .reservations-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1><i class="fas fa-calendar-check"></i> Mes Réservations</h1>
            <p>Gérez le statut de paiement des réservations de vos terrains</p>
        </div>
    </section>

    <div class="container">
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
                    <h3><?= count($reservations ?? []) ?></h3>
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
                <div class="stat-icon revenue">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-content">
                    <h3><?= number_format(array_sum(array_map(fn($r) => $r->prix_total, array_filter($reservations ?? [], fn($r) => $r->statut_paiement === 'paye'))), 0) ?> DH</h3>
                    <p>Revenu total</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" action="<?= UrlHelper::url('gerant/reservations') ?>" class="filters-form">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-flag"></i> Statut de paiement
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
                        <option value="">Tous mes terrains</option>
                        <?php foreach ($terrains ?? [] as $terrain): ?>
                            <option value="<?= $terrain->id_terrain ?>" 
                                    <?= ($filters['terrainId'] ?? '') == $terrain->id_terrain ? 'selected' : '' ?>>
                                <?= htmlspecialchars($terrain->nom_terrain) ?>
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

        <!-- Reservations Grid -->
        <?php if (empty($reservations)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Aucune réservation trouvée</h3>
                <p>Aucune réservation ne correspond à vos critères de recherche</p>
            </div>
        <?php else: ?>
            <div class="reservations-grid">
                <?php foreach ($reservations as $reservation): ?>
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
                    <div class="reservation-card">
                        <!-- Header -->
                        <div class="reservation-header">
                            <div>
                                <div class="reservation-id">
                                    Réservation #<?= $reservation->id_reservation ?>
                                </div>
                                <div class="reservation-date">
                                    <i class="fas fa-calendar"></i>
                                    <?= date('d/m/Y', strtotime($reservation->date_reservation)) ?>
                                </div>
                            </div>
                            <span class="status-badge <?= $statusClass ?>">
                                <?= $statusLabel ?>
                            </span>
                        </div>

                        <!-- Body -->
                        <div class="reservation-body">
                            <!-- Terrain Info -->
                            <div class="info-section">
                                <div class="section-title">
                                    <i class="fas fa-map-marker-alt"></i> Terrain
                                </div>
                                <div class="info-row">
                                    <div class="info-icon">
                                        <i class="fas fa-futbol"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-value"><?= htmlspecialchars($reservation->nom_terrain) ?></div>
                                        <div class="info-label"><?= htmlspecialchars($reservation->ville) ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Time Info -->
                            <div class="info-section">
                                <div class="section-title">
                                    <i class="fas fa-clock"></i> Horaire
                                </div>
                                <div class="info-row">
                                    <div class="info-icon">
                                        <i class="fas fa-hourglass-start"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-value">
                                            <?= date('H:i', strtotime($reservation->heure_debut)) ?> 
                                            - 
                                            <?= date('H:i', strtotime($reservation->heure_fin)) ?>
                                        </div>
                                        <div class="info-label">
                                            <?php
                                                $debut = new DateTime($reservation->heure_debut);
                                                $fin = new DateTime($reservation->heure_fin);
                                                $duree = $debut->diff($fin);
                                                echo $duree->h . 'h' . ($duree->i > 0 ? ' ' . $duree->i . 'min' : '');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Client Info -->
                            <div class="info-section">
                                <div class="section-title">
                                    <i class="fas fa-user"></i> Client
                                </div>
                                <div class="client-card">
                                    <div class="client-name">
                                        <i class="fas fa-user-circle"></i>
                                        <?= htmlspecialchars($reservation->client_prenom . ' ' . $reservation->client_nom) ?>
                                    </div>
                                    <div class="client-contact">
                                        <?php if (!empty($reservation->client_email)): ?>
                                            <span>
                                                <i class="fas fa-envelope"></i>
                                                <?= htmlspecialchars($reservation->client_email) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($reservation->client_telephone)): ?>
                                            <span>
                                                <i class="fas fa-phone"></i>
                                                <?= htmlspecialchars($reservation->client_telephone) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="price-section">
                                <div class="price-label">Prix total</div>
                                <div class="price-value"><?= number_format($reservation->prix_total, 2) ?> DH</div>
                            </div>
                        </div>

                        <!-- Actions - Statut de paiement uniquement -->
                        <div class="reservation-actions">
                            <button class="btn-action btn-payer"
                                    onclick="openStatusModal(<?= $reservation->id_reservation ?>, '<?= $reservation->statut_paiement ?>')">
                                <i class="fas fa-edit"></i>
                                Modifier statut paiement
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal for status change -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-credit-card"></i>
                <h3>Modifier le statut de paiement</h3>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 1.5rem; color: #64748b;">
                    Sélectionnez le nouveau statut de paiement pour cette réservation :
                </p>
                <div class="status-options">
                    <label class="status-option">
                        <input type="radio" name="payment_status" value="paye">
                        <div class="status-option-content">
                            <div class="status-option-title">
                                <i class="fas fa-check-circle" style="color: #16a34a;"></i>
                                Payée
                            </div>
                            <div class="status-option-desc">Le client a effectué le paiement</div>
                        </div>
                    </label>

                    <label class="status-option">
                        <input type="radio" name="payment_status" value="en_attente">
                        <div class="status-option-content">
                            <div class="status-option-title">
                                <i class="fas fa-clock" style="color: #f59e0b;"></i>
                                En attente
                            </div>
                            <div class="status-option-desc">En attente de paiement du client</div>
                        </div>
                    </label>

                    <label class="status-option">
                        <input type="radio" name="payment_status" value="annule">
                        <div class="status-option-content">
                            <div class="status-option-title">
                                <i class="fas fa-times-circle" style="color: #dc2626;"></i>
                                Annulée
                            </div>
                            <div class="status-option-desc">Réservation annulée, aucun paiement</div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-cancel" onclick="closeStatusModal()">
                    Annuler
                </button>
                <button class="btn-modal btn-confirm" onclick="confirmStatusChange()">
                    <i class="fas fa-check"></i>
                    Confirmer
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentReservationId = null;

        function openStatusModal(reservationId, currentStatus) {
            currentReservationId = reservationId;
            const modal = document.getElementById('statusModal');
            modal.classList.add('active');
            
            // Pre-select current status
            const radio = document.querySelector(`input[name="payment_status"][value="${currentStatus}"]`);
            if (radio) {
                radio.checked = true;
            }
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            modal.classList.remove('active');
            currentReservationId = null;
            
            // Clear selection
            document.querySelectorAll('input[name="payment_status"]').forEach(radio => {
                radio.checked = false;
            });
        }

        function confirmStatusChange() {
            const selectedStatus = document.querySelector('input[name="payment_status"]:checked');
            
            if (!selectedStatus) {
                alert('Veuillez sélectionner un statut de paiement');
                return;
            }

            const newStatus = selectedStatus.value;
            
            if (!currentReservationId) {
                alert('Erreur: ID de réservation manquant');
                return;
            }

            // Show loading state
            const confirmBtn = document.querySelector('.btn-confirm');
            const originalText = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour...';
            confirmBtn.disabled = true;

            // Send update request
            fetch('<?= UrlHelper::url('gerant/update-reservation-status') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: currentReservationId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success - reload page to show updated status
                    window.location.reload();
                } else {
                    // Error
                    alert(data.message || 'Erreur lors de la mise à jour du statut');
                    confirmBtn.innerHTML = originalText;
                    confirmBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la mise à jour du statut de paiement');
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            });
        }

        // Close modal when clicking outside
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeStatusModal();
            }
        });
    </script>
</body>
</html>