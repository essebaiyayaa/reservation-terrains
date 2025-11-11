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

        .status-badge.modifiee {
            background: #dbeafe;
            color: #1e40af;
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

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.875rem;
        }

        .btn-modifier {
            background: #fed7aa;
            color: #9a3412;
            border: 1px solid #fdba74;
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

            .btn {
                flex: 0 0 auto;
            }
        }
    </style>



 <!-- Page Header -->
    <section class="page-header">
        <h1><i class="fas fa-calendar-check"></i> Mes Réservations</h1>
        <p>Gérez toutes vos réservations de terrains de football</p>
    </section>

    <!-- Main Content -->
    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

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

        <?php if (empty($reservations)): ?>
            <div class="no-reservations">
                <i class="fas fa-calendar-times"></i>
                <h3>Aucune réservation</h3>
                <p>Vous n'avez pas encore effectué de réservation.</p>
                <a href="reservation.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Faire une réservation
                </a>
            </div>
        <?php else: ?>
            <div class="reservations-grid">
                <?php foreach ($reservations as $reservation): ?>
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
                                <span class="status-badge <?php echo strtolower(str_replace('é', 'e', $reservation['statut'])); ?>">
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
                                <a href="modifier-reservation.php?id=<?php echo $reservation['id_reservation']; ?>" 
                                   class="btn btn-modifier" 
                                   <?php echo !$can_modify ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                                    <i class="fas fa-edit"></i>
                                    Modifier
                                </a>
                                
                                <a href="annuler-reservation.php?id=<?php echo $reservation['id_reservation']; ?>" 
                                   class="btn btn-annuler"
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