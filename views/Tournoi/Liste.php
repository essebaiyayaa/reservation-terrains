<?php 
$title = 'Tournois Disponibles - FootBooking';
require_once __DIR__ . '/../header.php';
?>

<style>
    .tournoi-hero {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: white;
        padding: 4rem 5% 5rem;
        text-align: center;
    }

    .tournoi-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .tournoi-hero p {
        font-size: 1.2rem;
        opacity: 0.95;
    }
    .tournois-section {
        padding: 3rem 5%;
        background: #f9fafb;
        min-height: 60vh;
    }

    .tournois-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border-left: 4px solid #16a34a;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #dc2626;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }
    .tournois-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    .tournoi-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .tournoi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .tournoi-header {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: white;
        padding: 1.5rem;
    }

    .tournoi-header h3 {
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }

    .tournoi-status {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .tournoi-body {
        padding: 1.5rem;
    }

    .tournoi-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        color: #4b5563;
    }

    .info-row i {
        color: #16a34a;
        width: 20px;
        text-align: center;
    }

    .info-row strong {
        color: #1f2937;
        min-width: 100px;
    }

    .tournoi-description {
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }
    .places-info {
        background: #f0fdf4;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .places-info.limited {
        background: #fef3c7;
    }

    .places-count {
        font-size: 1.5rem;
        font-weight: bold;
        color: #16a34a;
    }

    .places-info.limited .places-count {
        color: #d97706;
    }

    .places-label {
        font-size: 0.9rem;
        color: #6b7280;
        margin-top: 0.3rem;
    }
    .tournoi-footer {
        padding: 0 1.5rem 1.5rem;
        display: flex;
        gap: 1rem;
    }

    .btn {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
        display: inline-block;
        flex: 1;
    }

    .btn-primary {
        background: #16a34a;
        color: white;
    }

    .btn-primary:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    .btn-success {
        background: #059669;
        color: white;
    }

    .btn-disabled {
        background: #d1d5db;
        color: #6b7280;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-disabled:hover {
        transform: none;
        background: #d1d5db;
    }
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #9ca3af;
    }
    .badge-inscrit {
        background: #059669;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .tournoi-hero h1 {
            font-size: 2rem;
        }

        .tournois-grid {
            grid-template-columns: 1fr;
        }

        .info-row {
            flex-wrap: wrap;
        }
    }
</style>

<section class="tournoi-hero">
    <h1><i class="fa-solid fa-trophy"></i> Tournois Disponibles</h1>
    <p>Inscrivez votre équipe et participez aux tournois de football !</p>
</section>

<section class="tournois-section">
    <div class="tournois-container">
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= htmlspecialchars($_SESSION['success']) ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?= htmlspecialchars($_SESSION['error']) ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($tournois)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-trophy"></i>
                <h3>Aucun tournoi disponible</h3>
                <p>Il n'y a pas de tournois ouverts pour le moment. Revenez plus tard !</p>
            </div>
        <?php else: ?>
            <div class="tournois-grid">
                <?php foreach ($tournois as $tournoi): ?>
                    <div class="tournoi-card">
                        <div class="tournoi-header">
                            <h3><?= htmlspecialchars($tournoi->nom_tournoi) ?></h3>
                            <span class="tournoi-status">
                                <?= htmlspecialchars($tournoi->statut) ?>
                            </span>
                        </div>
                        <div class="tournoi-body">
                            <div class="tournoi-info">
                                <div class="info-row">
                                    <i class="fa-solid fa-calendar"></i>
                                    <strong>Date:</strong>
                                    <span>
                                        <?= date('d/m/Y', strtotime($tournoi->date_debut)) ?>
                                        <?php if ($tournoi->date_debut != $tournoi->date_fin): ?>
                                            - <?= date('d/m/Y', strtotime($tournoi->date_fin)) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <strong>Lieu:</strong>
                                    <span><?= htmlspecialchars($tournoi->nom_terrain) ?>, <?= htmlspecialchars($tournoi->ville) ?></span>
                                </div>

                                <div class="info-row">
                                    <i class="fa-solid fa-user-tie"></i>
                                    <strong>Organisateur:</strong>
                                    <span><?= htmlspecialchars($tournoi->prenom_gerant . ' ' . $tournoi->nom_gerant) ?></span>
                                </div>

                                <?php if ($tournoi->prix_inscription > 0): ?>
                                    <div class="info-row">
                                        <i class="fa-solid fa-money-bill"></i>
                                        <strong>Prix:</strong>
                                        <span><?= number_format($tournoi->prix_inscription, 2) ?> MAD</span>
                                    </div>
                                <?php else: ?>
                                    <div class="info-row">
                                        <i class="fa-solid fa-gift"></i>
                                        <strong>Prix:</strong>
                                        <span style="color: #16a34a; font-weight: bold;">Gratuit</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($tournoi->description)): ?>
                                <div class="tournoi-description">
                                    <?= nl2br(htmlspecialchars(substr($tournoi->description, 0, 150))) ?>
                                    <?= strlen($tournoi->description) > 150 ? '...' : '' ?>
                                </div>
                            <?php endif; ?>
                            <div class="places-info <?= $tournoi->places_disponibles <= 3 ? 'limited' : '' ?>">
                                <div class="places-count">
                                    <?= $tournoi->places_disponibles ?> / <?= $tournoi->nombre_max_equipes ?>
                                </div>
                                <div class="places-label">places disponibles</div>
                            </div>
                        </div>
                        <!-- Footer - Boutons -->
                        <div class="tournoi-footer">
                            <?php if ($tournoi->dejainscrit): ?>
                                <!-- Cas 1 : Déjà inscrit -->
                                <div class="badge-inscrit">
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>Déjà inscrit</span>
                                </div>
                            <?php elseif (in_array(strtolower($tournoi->statut), ['en cours', 'terminé', 'annulé'])): ?>
                                <!-- Cas 2 : Tournoi non disponible (en cours, terminé ou annulé) -->
                                <button class="btn btn-disabled" disabled>
                                    <i class="fa-solid fa-ban"></i> 
                                    <?php 
                                        $statut_lower = strtolower($tournoi->statut);
                                        if ($statut_lower === 'en cours') echo 'En cours';
                                        elseif ($statut_lower === 'terminé') echo 'Terminé';
                                        elseif ($statut_lower === 'annulé') echo 'Annulé';
                                    ?>
                                </button>
                            <?php elseif ($tournoi->places_disponibles <= 0): ?>
                                <!-- Cas 3 : Tournoi complet -->
                                <button class="btn btn-disabled" disabled>
                                    <i class="fa-solid fa-ban"></i> Complet
                                </button>
                            <?php else: ?>
                                <!-- Cas 4 : Places disponibles -->
                                <a href="<?= UrlHelper::url('tournoi/participer?id=' . $tournoi->id_tournoi) ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-pen-to-square"></i> Participer
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($tournois)): ?>
            <div style="text-align: center; margin-top: 3rem;">
                <a href="<?= UrlHelper::url('tournoi/mesparticipations') ?>" class="btn btn-success" style="display: inline-block; max-width: 300px;">
                    <i class="fa-solid fa-list"></i> Voir mes participations
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../footer.php'; ?>