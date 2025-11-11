<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background: #f9fafb;
    color: #333;
    line-height: 1.6;
  }

  /* Header */
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

  .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
  }

  .back-btn:hover {
    color: #16a34a;
  }

  /* Container */
  .container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 0 5%;
  }

  /* Facture */
  .invoice {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    margin-bottom: 2rem;
  }

  .invoice-header {
    text-align: center;
    margin-bottom: 2rem;
    border-bottom: 3px solid #16a34a;
    padding-bottom: 1rem;
  }

  .invoice-title {
    color: #16a34a;
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
  }

  .invoice-subtitle {
    color: #6b7280;
    font-size: 1.2rem;
  }

  .invoice-number {
    background: #16a34a;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-block;
    margin-top: 0.5rem;
    font-weight: bold;
  }

  .section {
    margin-bottom: 2rem;
  }

  .section-title {
    background: #f0fdf4;
    padding: 1rem;
    border-left: 4px solid #16a34a;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #065f46;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  @media (max-width: 768px) {
    .info-grid {
      grid-template-columns: 1fr;
    }
  }

  .info-item {
    margin-bottom: 0.75rem;
  }

  .info-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
  }

  .info-value {
    color: #6b7280;
  }

  .table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
  }

  .table th {
    background: #16a34a;
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
  }

  .table td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
  }

  .table tr:last-child td {
    border-bottom: none;
  }

  .total-section {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 12px;
    margin-top: 2rem;
  }

  .total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
  }

  .total-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
  }

  .grand-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: #16a34a;
    border-top: 2px solid #16a34a;
    padding-top: 1rem;
    margin-top: 1rem;
  }

  .actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
  }

  .btn {
    padding: 1rem 2rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
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

  .btn-secondary {
    background: #6b7280;
    color: white;
  }

  .btn-secondary:hover {
    background: #374151;
    transform: translateY(-2px);
  }

  .status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
  }

  .status-confirmed {
    background: #d1fae5;
    color: #065f46;
  }

  .comment-section {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 1rem;
  }

  .comment-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
  }

  .comment-content {
    color: #6b7280;
    line-height: 1.5;
  }
</style>

<div class="container">
  <div class="invoice">
    <!-- Entte de la facture -->
    <div class="invoice-header">
      <h1 class="invoice-title">FOOTBOOKING</h1>
      <p class="invoice-subtitle">FACTURE</p>
      <div class="invoice-number">
        Réservation #<?php echo $reservation['id_reservation']; ?>
      </div>
    </div>

    <!-- Informations de la reservation -->
    <div class="section">
      <h3 class="section-title">
        <i class="fas fa-calendar-check"></i>
        Informations de la réservation
      </h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Numéro de réservation</div>
          <div class="info-value">
            #<?php echo $reservation['id_reservation']; ?>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Date de réservation</div>
          <div class="info-value">
            <?php echo date('d/m/Y', strtotime($reservation['date_reservation'])); ?>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Créneau horaire</div>
          <div class="info-value">
            <?php echo date('H:i', strtotime($reservation['heure_debut'])); ?>
            -
            <?php echo date('H:i', strtotime($reservation['heure_fin'])); ?>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Statut</div>
          <div class="info-value">
            <span class="status-badge status-confirmed">
              <?php echo $reservation['statut']; ?>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Informations du terrain -->
    <div class="section">
      <h3 class="section-title">
        <i class="fas fa-futbol"></i>
        Informations du terrain
      </h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Terrain</div>
          <div class="info-value">
            <?php echo htmlspecialchars($reservation['nom_terrain']); ?>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Adresse</div>
          <div class="info-value">
            <?php echo htmlspecialchars($reservation['adresse'] . ', ' . $reservation['ville']); ?>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Prix horaire</div>
          <div class="info-value">
            <?php echo number_format($reservation['prix_heure'], 2); ?>
            DH
          </div>
        </div>
      </div>
    </div>

    <!-- Informations client -->
    <div class="section">
      <h3 class="section-title">
        <i class="fas fa-user"></i>
        Informations client
      </h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Nom complet</div>
          <div class="info-value">
            <?php echo htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom']); ?>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Email</div>
          <div class="info-value">
            <?php echo htmlspecialchars($reservation['email']); ?>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Téléphone</div>
          <div class="info-value">
            <?php echo htmlspecialchars($reservation['telephone'] ?? 'Non renseigné'); ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Details de la facturation -->
    <div class="section">
      <h3 class="section-title">
        <i class="fas fa-receipt"></i>
        Détails de la facturation
      </h3>

      <table class="table">
        <thead>
          <tr>
            <th>Description</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              Location du terrain
              <?php echo htmlspecialchars($reservation['nom_terrain']); ?>
            </td>
            <td>
              <?php echo number_format($reservation['prix_heure'], 2); ?>
              DH
            </td>
            <td>1 heure</td>
            <td>
              <?php echo number_format($reservation['prix_heure'], 2); ?>
              DH
            </td>
          </tr>

          <?php foreach ($options as $option): ?>
          <tr>
            <td><?php echo htmlspecialchars($option['nom_option']); ?></td>
            <td>
              <?php echo number_format($option['prix'], 2); ?>
              DH
            </td>
            <td>1</td>
            <td>
              <?php echo number_format($option['prix'], 2); ?>
              DH
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="total-section">
        <div class="total-row">
          <span>Sous-total terrain:</span>
          <span
            ><?php echo number_format($reservation['prix_heure'], 2); ?>
            DH</span
          >
        </div>

        <?php if ($total_options >
        0): ?>
        <div class="total-row">
          <span>Options supplémentaires:</span>
          <span
            ><?php echo number_format($total_options, 2); ?>
            DH</span
          >
        </div>
        <?php endif; ?>

        <div class="total-row grand-total">
          <span>TOTAL:</span>
          <span
            ><?php echo number_format($total_general, 2); ?>
            DH</span
          >
        </div>
      </div>
    </div>

    <!-- Commentaires -->
    <?php if (!empty($reservation['commentaires'])): ?>
    <div class="section">
      <h3 class="section-title">
        <i class="fas fa-comment"></i>
        Commentaires
      </h3>
      <div class="comment-section">
        <div class="comment-content">
          <?php echo nl2br(htmlspecialchars($reservation['commentaires'])); ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="actions">
      <form method="POST" style="display: inline">
        <button type="submit" name="download_pdf" class="btn btn-primary">
          <i class="fas fa-download"></i>
          Télécharger la facture (PDF)
        </button>
      </form>
     
    </div>
  </div>
</div>
