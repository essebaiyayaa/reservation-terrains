<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter - <?= SITE_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📧 Newsletter</h1>
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

        <div class="grid md:grid-cols-2 gap-8">
            
            <!-- Formulaire d'envoi -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">✉️ Nouvelle Newsletter</h2>
                
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
                            ℹ️ Cette newsletter sera envoyée à <strong>tous les utilisateurs vérifiés</strong> de la plateforme.
                        </p>
                    </div>

                    <!-- Bouton -->
                    <button 
                        type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                    >
                        📨 Envoyer la Newsletter
                    </button>

                </form>
            </div>

            <!-- Historique -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">📋 Historique d'envoi</h2>
                
                <?php if (empty($newsletters)): ?>
                    <p class="text-gray-500 text-center py-8">Aucune newsletter envoyée pour le moment</p>
                <?php else: ?>
                    <div class="space-y-3 max-h-[600px] overflow-y-auto">
                        <?php foreach ($newsletters as $newsletter): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-800 flex-1">
                                        <?= htmlspecialchars($newsletter->sujet) ?>
                                    </h3>
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full <?= 
                                        $newsletter->statut === 'envoye' ? 'bg-green-100 text-green-800' : 
                                        ($newsletter->statut === 'echoue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
                                    ?>">
                                        <?= ucfirst($newsletter->statut) ?>
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                    <?= htmlspecialchars(substr($newsletter->contenu, 0, 100)) ?>...
                                </p>
                                
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>
                                        👤 <?= htmlspecialchars($newsletter->prenom . ' ' . $newsletter->nom) ?>
                                    </span>
                                    <span>
                                        📧 <?= $newsletter->nombre_destinataires ?> envoi(s)
                                    </span>
                                    <span>
                                        📅 <?= date('d/m/Y H:i', strtotime($newsletter->date_envoi)) ?>
                                    </span>
                                </div>

                                <?php if ($newsletter->piece_jointe): ?>
                                    <div class="mt-2 text-xs text-gray-500">
                                        📎 Pièce jointe: <?= htmlspecialchars($newsletter->piece_jointe) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-3 flex gap-2">
                                    <a 
                                        href="<?= UrlHelper::url('newsletter/show' . $id_newsletter) ?>"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        Voir détails →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

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