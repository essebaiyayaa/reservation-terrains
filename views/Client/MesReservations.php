<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - FootBooking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f9fafb;
        }

        /* Header & Navigation */
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

        .logo svg {
            width: 32px;
            height: 32px;
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

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-outline {
            background: white;
            color: #16a34a;
            border: 2px solid #16a34a;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: #f0fdf4;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 3rem 5%;
            text-align: center;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 5%;
        }

        /* Messages */
        .message {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .message.success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #16a34a;
        }

        .message.error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #dc2626;
        }

        .message i {
            font-size: 1.5rem;
        }

        /* Filtres */
        .filters-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .filters-section h2 {
            font-size: 1.5rem;
            color: #1f2937;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group select,
        .filter-group input {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #16a34a;
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .btn-filter {
            background: #16a34a;
            color: white;
        }

        .btn-filter:hover {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        .btn-reset {
            background: #6b7280;
            color: white;
        }

        .btn-reset:hover {
            background: #4b5563;
        }

        .results-count {
            color: #6b7280;
            font-size: 0.9rem;
            margin-right: auto;
        }

        /* Réservations Grid */
        .reservations-grid {
            display: grid;
            gap: 2rem;
        }

        .reservation-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .reservation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .reservation-header {
            padding: 1.5rem;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .reservation-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .reservation-title h3 {
            font-size: 1.3rem;
            color: #1f2937;
        }

        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-badge.confirmee {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.annulee {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.en-attente {
            background: #fef3c7;
            color: #92400e;
        }

        .reservation-body {
            padding: 1.5rem;
        }

        .reservation-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: #dcfce7;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .detail-content h4 {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .detail-content p {
            font-size: 1rem;
            color: #1f2937;
            font-weight: 500;
        }

        .commentaires {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .commentaires h4 {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .commentaires p {
            color: #1f2937;
            line-height: 1.6;
        }

        .reservation-actions {
            display: flex;
            gap: 0.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            flex-wrap: wrap;
        }

        .btn-modifier {
            background: #fed7aa;
            color: #9a3412;
            border: 1px solid #fdba74;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.875rem;
        }

        .btn-modifier:hover:not(:disabled) {
            background: #fdba74;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(251, 146, 60, 0.3);
        }

        .btn-annuler {
            background: #fecaca;
            color: #991b1b;
            border: 1px solid #fca5a5;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.875rem;
        }

        .btn-annuler:hover:not(:disabled) {
            background: #fca5a5;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .warning-text {
            color: #dc2626;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .no-reservations {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .no-reservations i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .no-reservations h3 {
            font-size: 1.5rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .no-reservations p {
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .btn-primary {
            background: #16a34a;
            color: white;
            padding: 0.8rem 2rem;
            font-size: 1rem;
            text-decoration: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        /* Footer */
        footer {
            background: #1f2937;
            color: white;
            padding: 3rem 5%;
            text-align: center;
            margin-top: 4rem;
        }

        footer p {
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .results-count {
                margin-right: 0;
                text-align: center;
            }

            .reservation-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .reservation-details {
                grid-template-columns: 1fr;
            }

            .reservation-actions {
                flex-direction: row;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>

    <!-- Page Header -->
    <section class="page-header">
        <h1><i class="fas fa-calendar-check"></i> Mes Réservations</h1>
        <p>Gérez toutes vos réservations de terrains de football</p>
    </section>

    <!-- Main Content -->
    <div class="container">
        <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message_type'] ?? 'success'; ?>">
                <i class="fas fa-<?php echo ($_SESSION['message_type'] ?? 'success') === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <span><?php echo htmlspecialchars($_SESSION['message']); ?></span>
            </div>
            <?php 
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <!-- Filtres de recherche -->
        <div class="filters-section">
            <h2><i class="fas fa-filter"></i> Filtres de recherche</h2>
            <form method="GET" action="">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="statut"><i class="fas fa-flag"></i> Statut</label>
                        <select name="statut" id="statut">
                            <option value="">Tous les statuts</option>
                            <option value="Confirmée" <?= ($_GET['statut'] ?? '') === 'Confirmée' ? 'selected' : '' ?>>Confirmée</option>
                            <option value="En attente" <?= ($_GET['statut'] ?? '') === 'En attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="Annulée" <?= ($_GET['statut'] ?? '') === 'Annulée' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="terrain"><i class="fas fa-map-marker-alt"></i> Terrain</label>
                        <select name="terrain" id="terrain">
                            <option value="">Tous les terrains</option>
                            <?php
                            $uniqueTerrains = [];
                            foreach ($reservations as $reservation) {
                                $terrainKey = $reservation['nom_terrain'] . '|' . $reservation['ville'];
                                if (!in_array($terrainKey, $uniqueTerrains)) {
                                    $uniqueTerrains[] = $terrainKey;
                                    $selected = ($_GET['terrain'] ?? '') === $reservation['nom_terrain'] ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($reservation['nom_terrain']) . "\" $selected>" . 
                                         htmlspecialchars($reservation['nom_terrain'] . ' - ' . $reservation['ville']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="date_debut"><i class="fas fa-calendar"></i> Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" value="<?= $_GET['date_debut'] ?? '' ?>">
                    </div>

                    <div class="filter-group">
                        <label for="date_fin"><i class="fas fa-calendar"></i> Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" value="<?= $_GET['date_fin'] ?? '' ?>">
                    </div>
                </div>

                <div class="filter-actions">
                    <div class="results-count">
                        <?php
                        $filteredReservations = $reservations;
                        
                        // Appliquer les filtres
                        if (isset($_GET['statut']) && $_GET['statut'] !== '') {
                            $filteredReservations = array_filter($filteredReservations, fn($r) => $r['statut'] === $_GET['statut']);
                        }
                        
                        if (isset($_GET['terrain']) && $_GET['terrain'] !== '') {
                            $filteredReservations = array_filter($filteredReservations, fn($r) => $r['nom_terrain'] === $_GET['terrain']);
                        }
                        
                        if (isset($_GET['date_debut']) && $_GET['date_debut'] !== '') {
                            $filteredReservations = array_filter($filteredReservations, fn($r) => $r['date_reservation'] >= $_GET['date_debut']);
                        }
                        
                        if (isset($_GET['date_fin']) && $_GET['date_fin'] !== '') {
                            $filteredReservations = array_filter($filteredReservations, fn($r) => $r['date_reservation'] <= $_GET['date_fin']);
                        }
                        
                        $count = count($filteredReservations);
                        echo $count . " réservation" . ($count > 1 ? 's' : '') . " trouvée" . ($count > 1 ? 's' : '');
                        ?>
                    </div>
                    
                    <button type="submit" class="btn btn-filter">
                        <i class="fas fa-search"></i> Appliquer les filtres
                    </button>
                    
                    <a href="mes-reservations" class="btn btn-reset">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <?php
        function canModifyReservation($date_reservation, $heure_debut) {
            $reservation_datetime = new DateTime($date_reservation . ' ' . $heure_debut);
            $now = new DateTime();
            $diff = $now->diff($reservation_datetime);
            $hours_until = ($diff->days * 24) + $diff->h;
            
            // Si la date est passée, retourner false
            if ($reservation_datetime < $now) {
                return false;
            }
            
            // Vérifier s'il reste au moins 48 heures
            return $hours_until >= 48;
        }
        ?>

        <?php if (empty($filteredReservations)): ?>
            <div class="no-reservations">
                <i class="fas fa-calendar-times"></i>
                <h3>Aucune réservation trouvée</h3>
                <p>Aucune réservation ne correspond à vos critères de recherche.</p>
                <a href="reservation" class="btn-primary">
                    <i class="fas fa-plus"></i> Faire une réservation
                </a>
            </div>
        <?php else: ?>
            <div class="reservations-grid">
                <?php foreach ($filteredReservations as $reservation): ?>
                    <?php 
                    $can_modify = canModifyReservation($reservation['date_reservation'], $reservation['heure_debut']);
                    $is_cancelled = $reservation['statut'] === 'Annulée';
                    $is_past = strtotime($reservation['date_reservation'] . ' ' . $reservation['heure_debut']) < time();
                    $can_interact = !$is_cancelled && !$is_past;
                    ?>
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <div class="reservation-title">
                                <h3><?php echo htmlspecialchars($reservation['nom_terrain']); ?></h3>
                                <span class="status-badge <?php echo strtolower(str_replace(['é', ' '], ['e', '-'], $reservation['statut'])); ?>">
                                    <?php echo htmlspecialchars($reservation['statut']); ?>
                                </span>
                            </div>
                            <span style="color: #6b7280; font-size: 0.9rem;">
                                Réservé le <?php echo date('d/m/Y', strtotime($reservation['date_creation'])); ?>
                            </span>
                        </div>

                        <div class="reservation-body">
                            <div class="reservation-details">
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="detail-content">
                                        <h4>Date</h4>
                                        <p><?php echo date('d/m/Y', strtotime($reservation['date_reservation'])); ?></p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="detail-content">
                                        <h4>Horaire</h4>
                                        <p><?php echo substr($reservation['heure_debut'], 0, 5) . ' - ' . substr($reservation['heure_fin'], 0, 5); ?></p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="detail-content">
                                        <h4>Ville</h4>
                                        <p><?php echo htmlspecialchars($reservation['ville']); ?></p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-expand"></i>
                                    </div>
                                    <div class="detail-content">
                                        <h4>Taille</h4>
                                        <p><?php echo htmlspecialchars($reservation['taille']); ?></p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-futbol"></i>
                                    </div>
                                    <div class="detail-content">
                                        <h4>Type de terrain</h4>
                                        <p><?php echo htmlspecialchars($reservation['type']); ?></p>
                                    </div>
                                </div>

                                <?php if ($reservation['options']): ?>
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <div class="detail-content">
                                        <h4>Options</h4>
                                        <p><?php echo htmlspecialchars($reservation['options']); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($reservation['commentaires']): ?>
                            <div class="commentaires">
                                <h4>Commentaires</h4>
                                <p><?php echo nl2br(htmlspecialchars($reservation['commentaires'])); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if (!$is_cancelled && !$is_past): ?>
                            <div class="reservation-actions">
                                <a href="modifier/id/<?php echo $reservation['id_reservation']; ?>" 
                                   class="btn-modifier" 
                                   <?php echo !$can_modify ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                                    <i class="fas fa-edit"></i>
                                    Modifier
                                </a>
                                
                                <a href="annuler/id/<?php echo $reservation['id_reservation']; ?>" 
                                   class="btn-annuler"
                                   onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                    <i class="fas fa-times-circle"></i>
                                    Annuler
                                </a>
                            </div>
                            
                            <?php if (!$can_modify): ?>
                            <div class="warning-text">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>La modification n'est plus possible (moins de 48h avant le match)</span>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>