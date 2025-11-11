<style>
.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #16a34a;
    text-decoration: none;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.back-link:hover {
    color: #15803d;
}

.tournoi-header {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.tournoi-header h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.statut-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 0.5rem;
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

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 1.5rem;
}

.card h2 {
    font-size: 1.5rem;
    color: #1e293b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row i {
    color: #16a34a;
    width: 24px;
    text-align: center;
}

.info-row strong {
    color: #64748b;
    min-width: 120px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}

.stat-box {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
}

.stat-box .number {
    font-size: 2rem;
    font-weight: bold;
    color: #16a34a;
}

.stat-box .label {
    color: #64748b;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.equipes-section {
    grid-column: 1 / -1;
}

.equipes-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.equipes-table thead {
    background: #f8fafc;
}

.equipes-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #334155;
    border-bottom: 2px solid #e2e8f0;
}

.equipes-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.equipes-table tr:hover {
    background: #f8fafc;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #94a3b8;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
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

@media (max-width: 968px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .equipes-table {
        font-size: 0.9rem;
    }
    
    .equipes-table th,
    .equipes-table td {
        padding: 0.75rem 0.5rem;
    }
}
</style>

<div class="container">
    <a href="<?= UrlHelper::url('tournoi/mestournois') ?>" class="back-link">
        <i class="fas fa-arrow-left"></i> Retour à mes tournois
    </a>

    <div class="tournoi-header">
        <h1><?= htmlspecialchars($tournoi->nom_tournoi) ?></h1>
        <?php
            $statusClass = match($tournoi->statut) {
                'En préparation' => 'statut-preparation',
                'En cours' => 'statut-encours',
                'Terminé' => 'statut-termine',
                'Annulé' => 'statut-annule',
                default => 'statut-preparation'
            };
        ?>
        <span class="statut-badge <?= $statusClass ?>">
            <?= htmlspecialchars($tournoi->statut) ?>
        </span>
    </div>

    <div class="content-grid">
        <!-- Informations du tournoi -->
        <div class="card">
            <h2><i class="fas fa-info-circle"></i> Informations</h2>
            
            <?php if ($tournoi->description): ?>
                <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                    <?= nl2br(htmlspecialchars($tournoi->description)) ?>
                </div>
            <?php endif; ?>

            <div class="info-row">
                <i class="fas fa-calendar"></i>
                <strong>Période</strong>
                <span>
                    <?= date('d/m/Y', strtotime($tournoi->date_debut)) ?> 
                    - 
                    <?= date('d/m/Y', strtotime($tournoi->date_fin)) ?>
                </span>
            </div>

            <div class="info-row">
                <i class="fas fa-map-marker-alt"></i>
                <strong>Terrain</strong>
                <span><?= htmlspecialchars($tournoi->nom_terrain) ?></span>
            </div>

            <div class="info-row">
                <i class="fas fa-city"></i>
                <strong>Ville</strong>
                <span><?= htmlspecialchars($tournoi->ville) ?></span>
            </div>

            <div class="info-row">
                <i class="fas fa-location-arrow"></i>
                <strong>Adresse</strong>
                <span><?= htmlspecialchars($tournoi->adresse) ?></span>
            </div>

            <div class="info-row">
                <i class="fas fa-coins"></i>
                <strong>Inscription</strong>
                <span>
                    <?php if ($tournoi->prix_inscription > 0): ?>
                        <?= number_format($tournoi->prix_inscription, 2) ?> DH / équipe
                    <?php else: ?>
                        <span style="color: #16a34a; font-weight: 600;">Gratuit</span>
                    <?php endif; ?>
                </span>
            </div>

            <div class="info-row">
                <i class="fas fa-user"></i>
                <strong>Contact</strong>
                <span>
                    <?= htmlspecialchars($tournoi->nom_gerant . ' ' . $tournoi->prenom_gerant) ?>
                    <?php if ($tournoi->telephone_gerant): ?>
                        <br><small><?= htmlspecialchars($tournoi->telephone_gerant) ?></small>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card">
            <h2><i class="fas fa-chart-bar"></i> Statistiques</h2>
            
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="number"><?= $tournoi->nombre_equipes_inscrites ?></div>
                    <div class="label">Équipes inscrites</div>
                </div>
                <div class="stat-box">
                    <div class="number"><?= $tournoi->places_disponibles ?></div>
                    <div class="label">Places restantes</div>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #64748b; font-size: 0.9rem;">Taux de remplissage</span>
                    <span style="font-weight: 600; color: #16a34a;">
                        <?= $tournoi->nombre_max_equipes > 0 
                            ? round(($tournoi->nombre_equipes_inscrites / $tournoi->nombre_max_equipes) * 100) 
                            : 0 
                        ?>%
                    </span>
                </div>
                <div style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden;">
                    <div style="
                        background: #16a34a; 
                        height: 100%; 
                        width: <?= $tournoi->nombre_max_equipes > 0 
                            ? ($tournoi->nombre_equipes_inscrites / $tournoi->nombre_max_equipes) * 100 
                            : 0 
                        ?>%;
                        transition: width 0.3s;
                    "></div>
                </div>
            </div>

            <?php if ($tournoi->statut !== 'Terminé' && $tournoi->statut !== 'Annulé'): ?>
                <div style="margin-top: 1.5rem;">
                    <a href="<?= UrlHelper::url('tournoi/edit/' . $tournoi->id_tournoi) ?>" 
                       class="btn btn-primary" 
                       style="width: 100%;">
                        <i class="fas fa-edit"></i> Modifier le tournoi
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Liste des équipes inscrites -->
        <div class="card equipes-section">
            <h2>
                <i class="fas fa-users"></i> 
                Équipes inscrites (<?= count($equipes) ?>)
            </h2>

            <?php if (empty($equipes)): ?>
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h3>Aucune équipe inscrite</h3>
                    <p>Les équipes apparaîtront ici dès qu'elles s'inscriront au tournoi.</p>
                </div>
            <?php else: ?>
                <table class="equipes-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom de l'équipe</th>
                            <th>Responsable</th>
                            <th>Contact</th>
                            <th>Joueurs</th>
                            <th>Date d'inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($equipes as $equipe): ?>
                            <tr>
                                <td><strong><?= $i++ ?></strong></td>
                                <td>
                                    <strong style="color: #16a34a;">
                                        <?= htmlspecialchars($equipe->nom_equipe) ?>
                                    </strong>
                                </td>
                                <td>
                                    <?= htmlspecialchars($equipe->responsable_prenom . ' ' . $equipe->responsable_nom) ?>
                                </td>
                                <td>
                                    <small>
                                        <?= htmlspecialchars($equipe->responsable_email) ?>
                                        <?php if ($equipe->responsable_telephone): ?>
                                            <br><?= htmlspecialchars($equipe->responsable_telephone) ?>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        <?= $equipe->nombre_joueurs ?> joueurs
                                    </span>
                                </td>
                                <td>
                                    <?= date('d/m/Y H:i', strtotime($equipe->date_inscription)) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>