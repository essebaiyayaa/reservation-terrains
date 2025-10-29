<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture N°<?= htmlspecialchars($facture['id']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ----------- STYLE GLOBAL ----------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1a202c;
        }
        
        .facture-wrapper {
            max-width: 900px;
            width: 100%;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        /* ----------- EN-TÊTE ----------- */
        .facture-header {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            color: white;
            padding: 50px 40px;
            position: relative;
        }

        .facture-header img {
            width: 80px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .facture-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .facture-header p {
            font-size: 15px;
            opacity: 0.95;
        }

        .invoice-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            margin-top: 12px;
            backdrop-filter: blur(10px);
        }

        /* ----------- CONTENU ----------- */
        .facture-content {
            padding: 50px 40px;
        }

        .section-title {
            font-size: 13px;
            text-transform: uppercase;
            color: #718096;
            font-weight: 600;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        td {
            padding: 16px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        td:first-child {
            font-weight: 600;
            color: #4a5568;
            width: 45%;
        }

        td:last-child {
            text-align: right;
        }

        /* ----------- TOTAL ----------- */
        .facture-total-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
        }

        .facture-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            padding: 24px 30px;
            border-radius: 12px;
            color: white;
        }

        .facture-total span:first-child {
            font-size: 16px;
            font-weight: 600;
        }

        .facture-total span:last-child {
            font-size: 26px;
            font-weight: 700;
        }

        /* ----------- STATUT BADGE ----------- */
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* ----------- BOUTON ----------- */
        .actions {
            padding: 40px;
            background: #f7fafc;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .btn-print {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(6, 95, 70, 0.4);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 95, 70, 0.6);
        }

        /* ----------- ANIMATION ----------- */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ----------- IMPRESSION ----------- */
        @media print {
            body { background: white; }
            .actions { display: none; }
            .facture-wrapper { box-shadow: none; }
        }
    </style>
</head>
<body>
<div class="facture-wrapper">
    <!-- EN-TÊTE -->
    <div class="facture-header">
        <img src="/public/images/logo.png" alt="Logo du site">
        <h1>Facture N°<?= htmlspecialchars($facture['id']) ?></h1>
        <p>Émise le <?= htmlspecialchars($facture['date_emission']) ?></p>
        <div class="invoice-badge">Document officiel</div>
    </div>

    <!-- CONTENU -->
    <div class="facture-content">
        <h2 class="section-title">Détails de la facture</h2>
        <table>
            <tr>
                <td>ID Réservation</td>
                <td><?= htmlspecialchars($facture['id_reservation']) ?></td>
            </tr>
            <tr>
                <td>Montant total</td>
                <td><?= htmlspecialchars($facture['montant']) ?> MAD</td>
            </tr>
            <tr>
                <td>Mode de paiement</td>
                <td><?= htmlspecialchars($facture['mode_paiement']) ?></td>
            </tr>
            <tr>
                <td>Statut</td>
                <td>
                    <?php if ($facture['statut'] == 'Payée'): ?>
                        <span class="status-badge status-paid">✓ Payée</span>
                    <?php elseif ($facture['statut'] == 'En attente'): ?>
                        <span class="status-badge status-pending"> En attente</span>
                    <?php else: ?>
                        <span class="status-badge status-cancelled"> Annulée</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="facture-total-section">
            <div class="facture-total">
                <span>Total à payer</span>
                <span><?= htmlspecialchars($facture['montant']) ?> MAD</span>
            </div>
        </div>
    </div>

    <!-- BOUTON -->
    <div class="actions">
        <button class="btn-print" onclick="window.print()"> Imprimer la facture</button>
    </div>
</div>
</body>
</html>