<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter - <?= SITE_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Newsletter</h1>
            <p class="text-gray-600">Envoyez des messages groupés à tous les utilisateurs</p>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Formulaire d'envoi -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Nouvelle Newsletter</h2>
            
            <form method="POST" action="<?= UrlHelper::url('newsletter/create') ?>" enctype="multipart/form-data" class="space-y-4">
                
                <!-- Sujet -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Sujet *
                    </label>
                    <input 
                        type="text" 
                        name="sujet" 
                        required
                        value="<?= htmlspecialchars($_SESSION['form_data']['sujet'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Ex: Nouvelles promotions disponibles"
                    >
                </div>

                <!-- Contenu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Contenu *
                    </label>
                    <textarea 
                        name="contenu" 
                        rows="8" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Rédigez votre message..."
                    ><?= htmlspecialchars($_SESSION['form_data']['contenu'] ?? '') ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">Le formatage basique (sauts de ligne) sera préservé</p>
                </div>

                <!-- Pièce jointe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Pièce jointe (optionnel)
                    </label>
                    <input 
                        type="file" 
                        name="piece_jointe"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    <p class="text-xs text-gray-500 mt-1">
                        Formats acceptés: PDF, DOC, DOCX, JPG, PNG, ZIP (5MB max)
                    </p>
                </div>

                <!-- Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Info:</strong> Cette newsletter sera envoyée à <strong>tous les utilisateurs vérifiés</strong> de la plateforme.
                    </p>
                </div>

                <!-- Bouton -->
                <button 
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                >
                    Envoyer la Newsletter
                </button>

            </form>
        </div>

        <!-- Retour -->
        <div class="mt-8">
            <a 
                href="<?= UrlHelper::url($currentUser->role === 'admin' ? 'admin' : 'gerant/dashboard') ?>"
                class="inline-flex items-center text-gray-600 hover:text-gray-800"
            >
                ← Retour au tableau de bord
            </a>
        </div>

    </div>

    <?php unset($_SESSION['form_data']); ?>
</body>
</html>