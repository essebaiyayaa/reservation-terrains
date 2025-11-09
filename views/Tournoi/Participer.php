<?php 
$title = 'Participer au Tournoi - FootBooking';
require_once __DIR__ . '/../header.php';
?>

<style>
    .participer-hero {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: white;
        padding: 3rem 5%;
        text-align: center;
    }

    .participer-hero h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .participer-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 5%;
    }

    .participation-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .section-header {
        background: #f9fafb;
        padding: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .section-header h2 {
        font-size: 1.3rem;
        color: #1f2937;
        margin: 0;
    }

    .tournoi-info {
        padding: 1.5rem;
        background: #f0fdf4;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item {
        display: flex;
        gap: 0.8rem;
    }

    .info-item i {
        color: #16a34a;
        margin-top: 0.2rem;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.2rem;
    }

    .info-value {
        font-weight: 600;
        color: #1f2937;
    }

    .form-section {
        padding: 2rem 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .required {
        color: #dc2626;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #16a34a;
    }

    .form-help {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 0.4rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid #e5e7eb;
    }

    .btn {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        border: none;
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: #16a34a;
        color: white;
    }

    .btn-primary:hover {
        background: #15803d;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    @media (max-width: 768px) {
        .participer-hero h1 {
            font-size: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>

<section class="participer-hero">
    <h1><i class="fa-solid fa-pen-to-square"></i> Inscription au Tournoi</h1>
</section>

<div class="participer-container">
    <div class="participation-card">
        
        <div class="section-header">
            <h2><i class="fa-solid fa-trophy"></i> <?= htmlspecialchars($tournoi->nom_tournoi) ?></h2>
        </div>

        <div class="tournoi-info">
            <div class="info-grid">
                <div class="info-item">
                    <i class="fa-solid fa-calendar"></i>
                    <div>
                        <div class="info-label">Date</div>
                        <div class="info-value">
                            <?= date('d/m/Y', strtotime($tournoi->date_debut)) ?>
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <div class="info-label">Lieu</div>
                        <div class="info-value">
                            <?= htmlspecialchars($tournoi->nom_terrain) ?>
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-<?= $tournoi->prix_inscription > 0 ? 'money-bill' : 'gift' ?>"></i>
                    <div>
                        <div class="info-label">Prix</div>
                        <div class="info-value">
                            <?php if ($tournoi->prix_inscription > 0): ?>
                                <?= number_format($tournoi->prix_inscription, 2) ?> MAD
                            <?php else: ?>
                                Gratuit
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-users"></i>
                    <div>
                        <div class="info-label">Places restantes</div>
                        <div class="info-value">
                            <?= $tournoi->places_disponibles ?> / <?= $tournoi->nombre_max_equipes ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($tournoi->places_disponibles <= 3): ?>
                <div class="alert alert-warning" style="margin-top: 1rem;">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    <span>Places limitées ! Dépêchez-vous.</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-section">
            <h3 style="margin-bottom: 1.5rem; color: #1f2937;">Informations de votre équipe</h3>

            <form method="POST" action="<?= UrlHelper::url('tournoi/inscrire') ?>" id="participationForm">
                <input type="hidden" name="id_tournoi" value="<?= $tournoi->id_tournoi ?>">

                <div class="form-group">
                    <label for="nom_equipe">
                        Nom de l'équipe <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="nom_equipe" 
                        name="nom_equipe" 
                        placeholder="Ex: Les Champions FC" 
                        required
                        minlength="3"
                        maxlength="50"
                    >
                    <div class="form-help">
                        Choisissez un nom unique (3-50 caractères)
                    </div>
                </div>

                <div class="form-group">
                    <label for="nombre_joueurs">
                        Nombre de joueurs <span class="required">*</span>
                    </label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="nombre_joueurs" 
                        name="nombre_joueurs" 
                        placeholder="Ex: 11" 
                        required
                        min="5"
                        max="11"
                    >
                    <div class="form-help">
                        Entre 5 et 11 joueurs
                    </div>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: start; gap: 0.8rem; cursor: pointer; font-weight: 400;">
                        <input 
                            type="checkbox" 
                            name="confirmation" 
                            id="confirmation" 
                            required
                            style="margin-top: 0.2rem; width: 18px; height: 18px;"
                        >
                        <span>
                            Je confirme les informations et j'accepte les conditions de participation
                            <span class="required">*</span>
                        </span>
                    </label>
                </div>

                <div class="form-actions">
                    <a href="<?= UrlHelper::url('tournoi') ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Confirmer l'inscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('participationForm').addEventListener('submit', function(e) {
        const nomEquipe = document.getElementById('nom_equipe').value.trim();
        const nombreJoueurs = parseInt(document.getElementById('nombre_joueurs').value);
        const confirmation = document.getElementById('confirmation').checked;

        if (nomEquipe.length < 3) {
            e.preventDefault();
            alert('Le nom de l\'équipe doit contenir au moins 3 caractères.');
            return false;
        }

        if (nombreJoueurs < 5 || nombreJoueurs > 11) {
            e.preventDefault();
            alert('Le nombre de joueurs doit être entre 5 et 11.');
            return false;
        }

        if (!confirmation) {
            e.preventDefault();
            alert('Veuillez confirmer les conditions de participation.');
            return false;
        }
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>