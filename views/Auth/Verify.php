<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification - FootBooking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .verify-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 2.5rem;
            text-align: center;
        }

        .verify-header {
            margin-bottom: 2rem;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 2rem;
            font-weight: bold;
            color: #16a34a;
            margin-bottom: 1rem;
        }

        .verify-header h1 {
            font-size: 1.8rem;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .verify-header p {
            color: #6b7280;
            line-height: 1.6;
        }

        .verification-icon {
            width: 80px;
            height: 80px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: #16a34a;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert ul {
            margin-left: 1.5rem;
            margin-top: 0.5rem;
        }

        .email-display {
            background: #f0fdf4;
            border: 2px solid #bbf7d0;
            border-radius: 12px;
            padding: 1rem;
            margin: 1.5rem 0;
            color: #15803d;
            font-weight: 600;
        }

        .verification-form {
            margin: 2rem 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            text-align: center;
        }

        .code-input-container {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin: 1.5rem 0;
        }

        .code-digit {
            width: 50px;
            height: 60px;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .code-digit:focus {
            outline: none;
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .code-digit.filled {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        /* Alternative: Single input field */
        .single-code-input {
            width: 100%;
            padding: 1rem;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            letter-spacing: 0.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .single-code-input:focus {
            outline: none;
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .form-help {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        .instructions {
            background: #f9fafb;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: left;
        }

        .instructions h3 {
            color: #1f2937;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .instructions ol {
            margin-left: 1.5rem;
            color: #6b7280;
            line-height: 1.6;
        }

        .instructions li {
            margin-bottom: 0.5rem;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            font-size: 1rem;
        }

        .btn-primary {
            background: #16a34a;
            color: white;
        }

        .btn-primary:hover {
            background: #15803d;
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
            margin-top: 1rem;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .resend-info {
            margin-top: 1.5rem;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .resend-link {
            color: #16a34a;
            text-decoration: none;
            font-weight: 600;
        }

        .resend-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .verify-container {
                padding: 1.5rem;
            }
            
            .code-digit {
                width: 40px;
                height: 50px;
                font-size: 1.2rem;
            }

            .single-code-input {
                font-size: 1.2rem;
                letter-spacing: 0.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-header">
            <div class="logo">
                <i class="fas fa-futbol"></i>
                FootBooking
            </div>
            <div class="verification-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h1>Vérifiez votre email</h1>
            <p>Nous avons envoyé un code de vérification à :</p>
        </div>

        <div class="email-display">
            <i class="fas fa-envelope"></i>
            <?= htmlspecialchars($email ?? 'votre@email.com') ?>
        </div>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Erreur(s) :</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= UrlHelper::url('verify/submit') ?>" class="verification-form">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-key"></i>
                    Entrez le code de vérification
                </label>
                <input 
                    type="text" 
                    name="verification_code" 
                    id="verification_code"
                    class="single-code-input"
                    placeholder="12345678"
                    maxlength="8"
                    pattern="[0-9]{8}"
                    required
                    autocomplete="off"
                    inputmode="numeric"
                >
                <p class="form-help">
                    <i class="fas fa-info-circle"></i>
                    Le code contient 8 chiffres
                </p>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check-circle"></i>
                Vérifier le code
            </button>

            <a href="<?= UrlHelper::url('/') ?>" class="btn btn-secondary">
                <i class="fas fa-home"></i>
                Retour à l'accueil
            </a>
        </form>

        <div class="instructions">
            <h3><i class="fas fa-lightbulb"></i> Instructions :</h3>
            <ol>
                <li>Vérifiez votre boîte de réception (et vos spams)</li>
                <li>Copiez le code de vérification à 8 chiffres</li>
                <li>Collez-le dans le champ ci-dessus</li>
                <li>Cliquez sur "Vérifier le code"</li>
            </ol>
        </div>

        <div class="resend-info">
            <p>Vous n'avez pas reçu l'email ? 
                <a href="<?= UrlHelper::url('resend-verification') ?>" class="resend-link">
                    Renvoyer le code
                </a>
            </p>
            <p style="margin-top: 0.5rem; font-size: 0.85rem;">
                Le code expire dans 30 minutes
            </p>
        </div>
    </div>

    <script>
        // Auto-format et validation du code
        const codeInput = document.getElementById('verification_code');
        
        codeInput.addEventListener('input', function(e) {
            // Supprimer tout ce qui n'est pas un chiffre
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limiter à 8 chiffres
            if (this.value.length > 8) {
                this.value = this.value.slice(0, 8);
            }
        });

        // Auto-submit quand 8 chiffres sont entrés (optionnel)
        codeInput.addEventListener('input', function(e) {
            if (this.value.length === 8) {
                // Optionnel: soumettre automatiquement
                // this.form.submit();
            }
        });

        // Focus automatique au chargement
        window.addEventListener('load', function() {
            codeInput.focus();
        });
    </script>
</body>
</html>