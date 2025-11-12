<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Terrain</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Navigation -->
        <div class="mb-6">
            <a href="<?= UrlHelper::url('gerant/terrain/' . (is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'])) ?>" 
               class="text-blue-600 hover:text-blue-800 flex items-center font-semibold transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour au terrain
            </a>
        </div>

        <!-- En-tête -->
        <div class="mb-8 text-center">
            <div class="inline-block bg-blue-100 rounded-full p-4 mb-4">
                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Modifier le Terrain</h1>
            <p class="text-gray-600">Modifiez les informations de votre terrain</p>
        </div>

        <!-- Messages d'erreur -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold text-red-800 mb-1">Erreurs de validation</p>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                <h2 class="text-xl font-bold text-white">Informations Modifiables</h2>
            </div>
            
            <form method="POST" action="<?= UrlHelper::url('gerant/edit-terrain/' . (is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'])) ?>" class="p-6 space-y-6">
                
                <!-- Nom du terrain -->
                <div>
                    <label for="nom_terrain" class="block text-sm font-bold text-gray-700 mb-2">
                        Nom du Terrain <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nom_terrain" 
                           name="nom_terrain" 
                           value="<?= htmlspecialchars(is_object($terrain) ? $terrain->nom_terrain : $terrain['nom_terrain']) ?>"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ex: Terrain Central"
                           required>
                </div>

                <!-- Adresse -->
                <div>
                    <label for="adresse" class="block text-sm font-bold text-gray-700 mb-2">
                        Adresse <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="adresse" 
                           name="adresse" 
                           value="<?= htmlspecialchars(is_object($terrain) ? $terrain->adresse : $terrain['adresse']) ?>"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ex: 123 Avenue Mohammed V"
                           required>
                </div>

                <!-- Prix par heure -->
                <div>
                    <label for="prix_heure" class="block text-sm font-bold text-gray-700 mb-2">
                        Prix par Heure (MAD) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="prix_heure" 
                               name="prix_heure" 
                               step="0.01" 
                               min="0"
                               value="<?= htmlspecialchars(is_object($terrain) ? $terrain->prix_heure : $terrain['prix_heure']) ?>"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="0.00"
                               required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">MAD</span>
                        </div>
                    </div>
                </div>

                <!-- Informations non modifiables -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-6 border-2 border-gray-200">
                    <div class="flex items-start mb-4">
                        <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 mb-1">Informations Verrouillées</h3>
                            <p class="text-xs text-gray-500">Ces champs ne peuvent être modifiés que par un administrateur</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Ville</p>
                            <p class="font-bold text-gray-900"><?= htmlspecialchars(is_object($terrain) ? $terrain->ville : $terrain['ville']) ?></p>
                        </div>
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Type</p>
                            <p class="font-bold text-gray-900"><?= htmlspecialchars(is_object($terrain) ? $terrain->type : $terrain['type']) ?></p>
                        </div>
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Taille</p>
                            <p class="font-bold text-gray-900"><?= htmlspecialchars(is_object($terrain) ? $terrain->taille : $terrain['taille']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-lg hover:from-blue-700 hover:to-blue-800 font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                    <a href="<?= UrlHelper::url('gerant/terrain/' . (is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'])) ?>" 
                       class="flex-1 bg-gray-200 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-300 font-bold text-center transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>

        <!-- Aide -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex">
                <svg class="h-6 w-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-bold text-blue-800 mb-1">Informations sur les modifications</h4>
                    <p class="text-sm text-blue-700">
                        En tant que gérant, vous pouvez modifier le nom, l'adresse et le prix de votre terrain. 
                        Pour modifier d'autres paramètres (ville, type, taille), veuillez contacter un administrateur.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>