<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background: #f9fafb;
  }

  header {
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

  .btn-primary {
    background: #16a34a;
    color: white;
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
  }

  .btn-primary:hover {
    background: #15803d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
  }

  .page-header {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    padding: 3rem 5%;
    text-align: center;
  }

  .page-header h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
  }

  .container {
    max-width: 800px;
    margin: 3rem auto;
    padding: 0 5%;
  }

  .confirmation-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 2.5rem;
  }

  .warning-icon {
    text-align: center;
    margin-bottom: 2rem;
  }

  .warning-icon i {
    font-size: 5rem;
    color: #dc2626;
  }

  .confirmation-card h2 {
    text-align: center;
    font-size: 2rem;
    color: #1f2937;
    margin-bottom: 1rem;
  }

  .confirmation-card > p {
    text-align: center;
    color: #6b7280;
    font-size: 1.1rem;
    margin-bottom: 2rem;
  }

  .reservation-summary {
    background: #f9fafb;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 2rem 0;
  }

  .summary-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .summary-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e7eb;
  }

  .summary-item:last-child {
    border-bottom: none;
  }

  .summary-label {
    color: #6b7280;
    font-weight: 500;
  }

  .summary-value {
    color: #1f2937;
    font-weight: 600;
  }

  .warning-message {
    background: #fef2f2;
    border-left: 4px solid #dc2626;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin: 2rem 0;
  }

  .warning-message p {
    color: #991b1b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
  }

  .btn {
    flex: 1;
    padding: 1rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
  }

  .btn-cancel {
    background: #e5e7eb;
    color: #1f2937;
  }

  .btn-cancel:hover {
    background: #d1d5db;
  }

  .btn-confirm {
    background: #dc2626;
    color: white;
  }

  .btn-confirm:hover {
    background: #b91c1c;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
  }

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

  @media (max-width: 768px) {
    .page-header h1 {
      font-size: 2rem;
    }

    .confirmation-card {
      padding: 1.5rem;
    }

    .actions {
      flex-direction: column;
    }
  }
</style>

<section class="page-header">
  <h1><i class="fas fa-exclamation-triangle"></i> Annuler la Réservation</h1>
  <p>Confirmation d'annulation</p>
</section>

<div class="container">
  <div class="confirmation-card">
    <div class="warning-icon">
      <i class="fas fa-exclamation-circle"></i>
    </div>

    <h2>Confirmer l'annulation</h2>
    <p>Êtes-vous sûr de vouloir annuler cette réservation ?</p>

    <div class="reservation-summary">
      <div class="summary-title">
        <i class="fas fa-info-circle"></i>
        Détails de la réservation
      </div>
      <div class="summary-item">
        <span class="summary-label">Terrain</span>
        <span class="summary-value"
          ><?php echo htmlspecialchars($reservation['nom_terrain']); ?></span
        >
      </div>
      <div class="summary-item">
        <span class="summary-label">Ville</span>
        <span class="summary-value"
          ><?php echo htmlspecialchars($reservation['ville']); ?></span
        >
      </div>
      <div class="summary-item">
        <span class="summary-label">Date</span>
        <span class="summary-value"
          ><?php echo date('d/m/Y', strtotime($reservation['date_reservation'])); ?></span
        >
      </div>
      <div class="summary-item">
        <span class="summary-label">Horaire</span>
        <span class="summary-value">
          <?php echo substr($reservation['heure_debut'], 0, 5) . ' - ' . substr($reservation['heure_fin'], 0, 5); ?>
        </span>
      </div>
    </div>

    <div class="warning-message">
      <p>
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Attention:</strong> Cette action est irréversible. Une fois
        annulée, vous devrez créer une nouvelle réservation si vous changez
        d'avis.
      </p>
    </div>

    <form method="POST">
      <div class="actions">
        <a href="/reservation-terrains/public/mes-reservations" class="btn btn-cancel">
          <i class="fas fa-arrow-left"></i>
          Retour
        </a>
        <button
          type="submit"
          name="confirmer_annulation"
          class="btn btn-confirm"
        >
          <i class="fas fa-check"></i>
          Confirmer l'annulation
        </button>
      </div>
    </form>
  </div>
</div>
