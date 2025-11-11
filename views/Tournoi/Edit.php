<style>
.container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.page-header {
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #64748b;
}

.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 2rem;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.alert-warning {
    background: #fef3c7;
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}

.form-group label.required::after {
    content: " *";
    color: #dc2626;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-help {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 0.25rem;
}

.info-box {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #16a34a;
    margin-bottom: 2rem;
}

.info-box h3 {
    font-size: 1rem;
    color: #16a34a;
    margin-bottom: 0.5rem;
}

.info-box p {
    color: #64748b;
    font-size: 0.9rem;
    margin: 0.25rem 0;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
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
    justify-content: center;
}

.btn-primary {
    background: #16a34a;
    color: white;
    flex: 1;
}

.btn-primary:hover {
    background: #15803d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn-secondary:hover {
    background: #475569;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<div class="container">
    <div class="page-header">
        <h1>Modifier le tournoi</h1>
        <p><?= htmlspecialchars($tournoi->nom_tournoi) ?></p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if ($tournoi->nombre_equipes_inscrites > 0): ?>
        <div class="alert alert-warning">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Attention :</strong> Des équipes sont déjà inscrites à ce tournoi. 
                Certaines modifications peuvent affecter les participants.
            </div>
        </div>
    <?php endif; ?>

    <div class="info-box">
        <h3><i class="fas fa-users"></i> Informations actuelles</h3>
        <p><strong>Équipes inscrites :</strong> <?= $tournoi->nombre_equipes_inscrites ?> / <?= $tournoi->nombre_max_equipes ?></p>
        <p><strong>Places disponibles :</strong> <?= $tournoi->places_disponibles ?></p>
        <p><strong>Statut :</strong> <?= htmlspecialchars($tournoi->statut) ?></p>
    </div>

    <div class="form-card">
        <form action="<?= UrlHelper::url('tournoi/edit/' . $tournoi->id_tournoi) ?>" method="POST">
            <!-- Informations générales -->
            <div class="form-group">
                <label for="nom_tournoi" class="required">Nom du tournoi</label>
                <input 
                    type="text" 
                    id="nom_tournoi" 
                    name="nom_tournoi" 
                    class="form-control"
                    required
                    minlength="3"
                    maxlength="100"
                    value="<?= htmlspecialchars($tournoi->nom_tournoi) ?>"
                >
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control"
                ><?= htmlspecialchars($tournoi->description ?? '') ?></textarea>
            </div>

            <!-- Dates -->
            <div class="form-row">
                <div class="form-group">
                    <label for="date_debut" class="required">Date de début</label>
                    <input 
                        type="date" 
                        id="date_debut" 
                        name="date_debut" 
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($tournoi->date_debut) ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="date_fin" class="required">Date de fin</label>
                    <input 
                        type="date" 
                        id="date_fin" 
                        name="date_fin" 
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($tournoi->date_fin) ?>"
                    >
                </div>
            </div>

            <!-- Terrain -->
            <div class="form-group">
                <label for="id_terrain" class="required">Terrain</label>
                <select id="id_terrain" name="id_terrain" class="form-control" required>
                    <?php foreach ($mesTerrains as $terrain): ?>
                        <option value="<?= $terrain->id_terrain ?>"
                                <?= $terrain->id_terrain == $tournoi->id_terrain ? 'selected' : '' ?>>
                            <?= htmlspecialchars($terrain->nom_terrain) ?> - <?= htmlspecialchars($terrain->ville) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Configuration -->
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre_max_equipes" class="required">Nombre maximum d'équipes</label>
                    <input 
                        type="number" 
                        id="nombre_max_equipes" 
                        name="nombre_max_equipes" 
                        class="form-control"
                        min="<?= $tournoi->nombre_equipes_inscrites ?>"
                        max="32"
                        value="<?= $tournoi->nombre_max_equipes ?>"
                        required
                    >
                    <div class="form-help">
                        Minimum: <?= $tournoi->nombre_equipes_inscrites ?> (équipes déjà inscrites)
                    </div>
                </div>

                <div class="form-group">
                    <label for="prix_inscription" class="required">Prix d'inscription (DH)</label>
                    <input 
                        type="number" 
                        id="prix_inscription" 
                        name="prix_inscription" 
                        class="form-control"
                        min="0"
                        step="0.01"
                        value="<?= $tournoi->prix_inscription ?>"
                        required
                    >
                </div>
            </div>

            <!-- Statut -->
            <div class="form-group">
                <label for="statut" class="required">Statut</label>
                <select id="statut" name="statut" class="form-control" required>
                    <option value="En préparation" <?= $tournoi->statut === 'En préparation' ? 'selected' : '' ?>>
                        En préparation
                    </option>
                    <option value="En cours" <?= $tournoi->statut === 'En cours' ? 'selected' : '' ?>>
                        En cours
                    </option>
                    <option value="Terminé" <?= $tournoi->statut === 'Terminé' ? 'selected' : '' ?>>
                        Terminé
                    </option>
                </select>
                <div class="form-help">
                    Changez le statut selon l'avancement du tournoi
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="<?= UrlHelper::url('tournoi/mestournois') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validation des dates
document.getElementById('date_debut').addEventListener('change', function() {
    const dateDebut = this.value;
    const dateFinInput = document.getElementById('date_fin');
    dateFinInput.min = dateDebut;
    
    if (dateFinInput.value && dateFinInput.value < dateDebut) {
        dateFinInput.value = dateDebut;
    }
});
</script>