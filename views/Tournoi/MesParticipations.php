<?php 
$title = 'Mes Participations - FootBooking';
require_once __DIR__ . '/../header.php';
?>

<style>
    .participation-hero {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: white;
        padding: 4rem 5% 5rem;
        text-align: center;
    }

    .participation-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .participation-hero p {
        font-size: 1.2rem;
        opacity: 0.95;
    }
    .participations-section {
        padding: 3rem 5%;
        background: #f9fafb;
        min-height: 60vh;
    }

    .participations-container {
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
        background: #f0fdf4;
        color: #166534;
        border-left: 4px solid #16a34a;
    }
    .participations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    .participation-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid #16a34a;
    }

    .participation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .participation-header {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: white;
        padding: 1.5rem;
    }

    .participation-header h3 {
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }

    .participation-status {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .participation-body {
        padding: 1.5rem;
    }
    .equipe-info {
        background: #f0fdf4;
        border: 2px solid #16a34a;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .equipe-name {
        font-size: 1.3rem;
        font-weight: bold;
        color: #15803d;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .equipe-details {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.8rem;
        font-size: 0.95rem;
        color: #4b5563;
    }

    .equipe-details > div {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .equipe-details i {
        color: #16a34a;
    }
    .tournoi-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
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
    .date-inscription {
        background: #f0fdf4;
        padding: 0.8rem;
        border-radius: 8px;
        margin-top: 1.5rem;
        text-align: center;
        font-size: 0.9rem;
        color: #166534;
    }

    .date-inscription i {
        margin-right: 0.5rem;
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
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #9ca3af;
        margin-bottom: 2rem;
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
        background: #16a34a;
        color: white;
    }

    .btn-success:hover {
        background: #15803d;
        transform: translateY(-2px);
    }
    .back-button {
        margin-bottom: 2rem;
    }

    .btn-back {
        background: #6b7280;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: #4b5563;
    }
    .stats-section {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #16a34a;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .participation-hero h1 {
            font-size: 2rem;
        }

        .participations-grid {
            grid-template-columns: 1fr;
        }

        .equipe-details {
            flex-direction: column;
            gap: 0.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="participation-hero">
    <h1><i class="fa-solid fa-list-check"></i> Mes Participations</h1>
    <p>Retrouvez tous les tournois auxquels vous participez</p>
</section>

<section class="participations-section">
    <div class="participations-container">
        
        <div class="back-button">
            <a href="<?= UrlHelper::url('tournoi/index') ?>" class="btn btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Retour aux tournois
            </a>
        </div>

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

        <?php if (empty($mesTournois)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-clipboard-list"></i>
                <h3>Aucune participation</h3>
                <p>Vous ne participez à aucun tournoi pour le moment.</p>
                <a href="<?= UrlHelper::url('tournoi/index') ?>" class="btn btn-success">
                    <i class="fa-solid fa-trophy"></i> Découvrir les tournois
                </a>
            </div>
        <?php else: ?>
            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?= count($mesTournois) ?></div>
                        <div class="stat-label">Tournoi<?= count($mesTournois) > 1 ? 's' : '' ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">
                            <?php 
                            $totalJoueurs = 0;
                            foreach ($mesTournois as $tournoi) {
                                $totalJoueurs += $tournoi->nombre_joueurs;
                            }
                            echo $totalJoueurs;
                            ?>
                        </div>
                        <div class="stat-label">Joueurs inscrits</div>
                    </div>
                </div>
            </div>
            <div class="participations-grid">
                <?php foreach ($mesTournois as $tournoi): ?>
                    <div class="participation-card">
                        <!-- Header -->
                        <div class="participation-header">
                            <h3><?= htmlspecialchars($tournoi->nom_tournoi) ?></h3>
                            <span class="participation-status">
                                <?= htmlspecialchars($tournoi->statut) ?>
                            </span>
                        </div>
                        <div class="participation-body">
                            <div class="equipe-info">
                                <div class="equipe-name">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <?= htmlspecialchars($tournoi->nom_equipe) ?>
                                </div>
                                <div class="equipe-details">
                                    <div>
                                        <i class="fa-solid fa-users"></i>
                                        <strong><?= $tournoi->nombre_joueurs ?></strong> joueurs
                                    </div>
                                </div>
                            </div>
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

                                <?php if ($tournoi->prix_inscription > 0): ?>
                                    <div class="info-row">
                                        <i class="fa-solid fa-money-bill"></i>
                                        <strong>Prix:</strong>
                                        <span><?= number_format($tournoi->prix_inscription, 2) ?> MAD</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="date-inscription">
                                <i class="fa-solid fa-clock"></i>
                                Inscrit le <?= date('d/m/Y à H:i', strtotime($tournoi->date_inscription)) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../footer.php'; ?>