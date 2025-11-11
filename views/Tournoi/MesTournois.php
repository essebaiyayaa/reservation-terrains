<style>
    .hero-section {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white;
    text-align: center;
    padding: 4rem 2rem;
    max-width: 1400px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.hero-content h2 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
    line-height: 1.4;
}

.hero-content .btn {
    background: white;
    color: #15803d;
    font-weight: 600;
}

.hero-content .btn:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
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

.page-header h1 {
    font-size: 2rem;
    color: #1e293b;
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
}

.btn-primary {
    background: #16a34a;
    color: white;
}

.btn-primary:hover {
    background: #15803d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
}

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

.tournois-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.tournoi-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s;
}

.tournoi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.tournoi-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white;
}

.tournoi-header h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.statut-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.statut-preparation {
    background: #fef3c7;
    color: #92400e;
}

.statut-encours {
    background: #dbeafe;
    color: #1e40af;
}

.statut-termine {
    background: #e5e7eb;
    color: #374151;
}

.statut-annule {
    background: #fee2e2;
    color: #991b1b;
}

.tournoi-body {
    padding: 1.5rem;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    color: #64748b;
}

.info-row i {
    color: #16a34a;
    width: 20px;
}

.participants-stats {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.participants-stats strong {
    color: #16a34a;
    font-size: 1.1rem;
}

.progress-bar {
    background: #e2e8f0;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 0.5rem;
}

.progress-fill {
    background: #16a34a;
    height: 100%;
    transition: width 0.3s;
}

.tournoi-actions {
    display: flex;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    flex: 1;
    justify-content: center;
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn-secondary:hover {
    background: #475569;
}

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
}

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
    margin-bottom: 1.5rem;
}
</style>

<section class="hero-section">
    <div class="hero-content">
        <h1> Mes Tournois</h1>
        <h2>Organisez vos tournois facilement et faites vibrer vos compétitions !</h2>
        <a href="<?= UrlHelper::url('tournoi/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un tournoi
        </a>
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

    <?php if (empty($mesTournois)): ?>
        <div class="empty-state">
            <i class="fas fa-trophy"></i>
            <h3>Aucun tournoi créé</h3>
            <p>Vous n'avez pas encore créé de tournoi. Commencez maintenant !</p>
            <a href="<?= UrlHelper::url('tournoi/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Créer mon premier tournoi
            </a>
        </div>
    <?php else: ?>
        <div class="tournois-grid">
            <?php foreach ($mesTournois as $tournoi): ?>
                <?php
                    $statusClass = match($tournoi->statut) {
                        'En préparation' => 'statut-preparation',
                        'En cours' => 'statut-encours',
                        'Terminé' => 'statut-termine',
                        'Annulé' => 'statut-annule',
                        default => 'statut-preparation'
                    };
                    
                    $progressPercent = $tournoi->nombre_max_equipes > 0 
                        ? ($tournoi->nombre_equipes_inscrites / $tournoi->nombre_max_equipes) * 100 
                        : 0;
                ?>
                <div class="tournoi-card">
                    <div class="tournoi-header">
                        <h3><?= htmlspecialchars($tournoi->nom_tournoi) ?></h3>
                        <span class="statut-badge <?= $statusClass ?>">
                            <?= htmlspecialchars($tournoi->statut) ?>
                        </span>
                    </div>

                    <div class="tournoi-body">
                        <div class="info-row">
                            <i class="fas fa-calendar"></i>
                            <span>
                                <?= date('d/m/Y', strtotime($tournoi->date_debut)) ?> 
                                - 
                                <?= date('d/m/Y', strtotime($tournoi->date_fin)) ?>
                            </span>
                        </div>

                        <div class="info-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= htmlspecialchars($tournoi->nom_terrain) ?> - <?= htmlspecialchars($tournoi->ville) ?></span>
                        </div>

                        <div class="info-row">
                            <i class="fas fa-coins"></i>
                            <span><?= number_format($tournoi->prix_inscription, 2) ?> DH / équipe</span>
                        </div>

                        <div class="participants-stats">
                            <div>
                                <strong><?= $tournoi->nombre_equipes_inscrites ?>/<?= $tournoi->nombre_max_equipes ?></strong>
                                <small style="display: block; color: #64748b;">équipes inscrites</small>
                            </div>
                            <div style="text-align: right;">
                                <strong style="color: <?= $tournoi->places_disponibles > 0 ? '#16a34a' : '#dc2626' ?>">
                                    <?= $tournoi->places_disponibles ?>
                                </strong>
                                <small style="display: block; color: #64748b;">places</small>
                            </div>
                        </div>

                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $progressPercent ?>%"></div>
                        </div>
                    </div>

                    <div class="tournoi-actions">
                        <a href="<?= UrlHelper::url('tournoi/show/' . $tournoi->id_tournoi) ?>" 
                           class="btn btn-sm btn-secondary">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        
                        <?php if ($tournoi->statut !== 'Terminé' && $tournoi->statut !== 'Annulé'): ?>
                            <a href="<?= UrlHelper::url('tournoi/edit/' . $tournoi->id_tournoi) ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            
                            <a href="<?= UrlHelper::url('tournoi/delete/' . $tournoi->id_tournoi) ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir annuler ce tournoi ?')">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>