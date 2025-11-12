<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Terrain</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php
    $nom = is_object($terrain) ? $terrain->nom_terrain : $terrain['nom_terrain'];
    $adresse = is_object($terrain) ? $terrain->adresse : $terrain['adresse'];
    $ville = is_object($terrain) ? $terrain->ville : $terrain['ville'];
    $taille = is_object($terrain) ? $terrain->taille : $terrain['taille'];
    $type = is_object($terrain) ? $terrain->type : $terrain['type'];
    $prix = is_object($terrain) ? $terrain->prix_heure : $terrain['prix_heure'];
    $id = is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'];
    ?>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation -->
        <div class="mb-6">
            <a href="<?= UrlHelper::url('gerant/dashboard') ?>" 
               class="text-blue-600 hover:text-blue-800 flex items-center font-semibold transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour au tableau de bord
            </a>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-700 font-medium"><?= htmlspecialchars($_SESSION['success']) ?></span>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- En-tête du terrain -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-3">
                            <?= htmlspecialchars($nom) ?>
                        </h1>
                        <div class="flex items-center text-blue-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span><?= htmlspecialchars($adresse) ?>, <?= htmlspecialchars($ville) ?></span>
                        </div>
                    </div>
                    <a href="<?= UrlHelper::url('gerant/edit-terrain/' . $id) ?>" 
                       class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 font-bold shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Caractéristiques -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Caractéristiques du Terrain
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border-2 border-blue-200">
                            <p class="text-sm text-blue-600 font-medium mb-2">Type de terrain</p>
                            <p class="text-2xl font-bold text-blue-900"><?= htmlspecialchars($type) ?></p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border-2 border-green-200">
                            <p class="text-sm text-green-600 font-medium mb-2">Taille</p>
                            <p class="text-2xl font-bold text-green-900"><?= htmlspecialchars($taille) ?></p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border-2 border-yellow-200">
                            <p class="text-sm text-yellow-600 font-medium mb-2">Prix par heure</p>
                            <p class="text-3xl font-bold text-yellow-900">
                                <?= number_format($prix, 2) ?> <span class="text-lg">MAD</span>
                            </p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border-2 border-purple-200">
                            <p class="text-sm text-purple-600 font-medium mb-2">Ville</p>
                            <p class="text-2xl font-bold text-purple-900"><?= htmlspecialchars($ville) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Options Supplémentaires -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Options Supplémentaires
                        </h2>
                        <a href="<?= UrlHelper::url('gerant/options/' . $id) ?>" 
                           class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-2 rounded-lg hover:from-purple-700 hover:to-purple-800 font-bold text-sm shadow-lg transition-all flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Gérer les options
                        </a>
                    </div>

                    <?php if (empty($options)): ?>
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Aucune option supplémentaire configurée</p>
                            <a href="<?= UrlHelper::url('gerant/options/' . $id) ?>" 
                               class="inline-block mt-3 text-purple-600 hover:text-purple-800 font-semibold">
                                Ajouter des options →
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($options as $option): ?>
                                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border-2 border-purple-200 hover:shadow-md transition-shadow">
                                    <div class="flex items-center">
                                        <div class="bg-purple-500 rounded-full p-2 mr-3">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <p class="font-bold text-gray-900 text-lg">
                                            <?= htmlspecialchars(is_object($option) ? $option->nom_option : $option['nom_option']) ?>
                                        </p>
                                    </div>
                                    <p class="font-bold text-green-600 text-xl">
                                        <?= number_format(is_object($option) ? $option->prix : $option['prix'], 2) ?> MAD
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Carte latérale -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Actions Rapides
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="<?= UrlHelper::url('gerant/edit-terrain/' . $id) ?>" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 text-center font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier le terrain
                        </a>
                        
                        <a href="<?= UrlHelper::url('gerant/options/' . $id) ?>" 
                           class="block w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-3 rounded-lg hover:from-purple-700 hover:to-purple-800 text-center font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Gérer les options
                        </a>
                        
                        <a href="<?= UrlHelper::url('gerant/reservations') ?>?terrain=<?= $id ?>" 
                           class="block w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-3 rounded-lg hover:from-green-700 hover:to-green-800 text-center font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Voir les réservations
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t-2 border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            Adresse Complète
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 leading-relaxed">
                                <?= htmlspecialchars($adresse) ?><br>
                                <span class="font-bold"><?= htmlspecialchars($ville) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>