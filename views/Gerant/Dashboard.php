<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gérant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête avec navigation -->
      
        <!-- Messages de succès/erreur -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Réservations -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Réservations</p>
                        <p class="text-3xl font-bold text-gray-900">
                            <?= $stats['total_reservations'] ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Réservations Aujourd'hui -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm font-medium mb-1">Aujourd'hui</p>
                        <p class="text-3xl font-bold text-gray-900">
                            <?= $stats['reservations_aujourdhui'] ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Chiffre d'Affaires -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm font-medium mb-1">CA du mois</p>
                        <p class="text-3xl font-bold text-gray-900">
                            <?= number_format($stats['ca_mois'], 2) ?> <span class="text-xl">MAD</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mes Terrains -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Mes Terrains</h2>
                <div class="flex gap-4 items-center">
                    <span class="text-gray-500 font-medium bg-gray-100 px-3 py-1 rounded-full">
                        <?= count($terrains) ?> terrain(s)
                    </span>
                    <a href="<?= UrlHelper::url('gerant/terrains') ?>" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold flex items-center transition-colors">
                        Voir tous
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <?php if (empty($terrains)): ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun terrain</h3>
                    <p class="text-gray-500">
                        Vous n'avez pas encore de terrain assigné.
                    </p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($terrains as $terrain): ?>
                        <?php
                        $id = is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'];
                        $nom = is_object($terrain) ? $terrain->nom_terrain : $terrain['nom_terrain'];
                        $adresse = is_object($terrain) ? $terrain->adresse : $terrain['adresse'];
                        $type = is_object($terrain) ? $terrain->type : $terrain['type'];
                        $prix = is_object($terrain) ? $terrain->prix_heure : $terrain['prix_heure'];
                        $nbRes = is_object($terrain) ? $terrain->nb_reservations : $terrain['nb_reservations'];
                        ?>
                        <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:shadow-xl hover:border-blue-500 transition-all duration-300">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                                <h3 class="text-lg font-bold text-white truncate">
                                    <?= htmlspecialchars($nom) ?>
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    <?= htmlspecialchars($adresse) ?>
                                </p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($type) ?>
                                    </span>
                                    <span class="text-lg font-bold text-green-600">
                                        <?= number_format($prix, 2) ?> MAD/h
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span class="font-bold"><?= $nbRes ?></span> réservations
                                </div>
                                <div class="flex gap-2">
                                    <a href="<?= UrlHelper::url('gerant/terrain/' . $id) ?>" 
                                       class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center font-semibold">
                                        Voir
                                    </a>
                                    <a href="<?= UrlHelper::url('gerant/options/' . $id) ?>" 
                                       class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-center font-semibold">
                                        Options
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="<?= UrlHelper::url('gerant/reservations') ?>" 
               class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl hover:border-2 hover:border-purple-500 transition-all group">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3 group-hover:bg-purple-500 transition-colors">
                        <svg class="h-8 w-8 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900">Réservations</h3>
                        <p class="text-sm text-gray-500">Gérer les réservations</p>
                    </div>
                </div>
            </a>

            <a href="<?= UrlHelper::url('tournoi/mestournois') ?>" 
               class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl hover:border-2 hover:border-red-500 transition-all group">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-lg p-3 group-hover:bg-red-500 transition-colors">
                        <svg class="h-8 w-8 text-red-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900">Tournois</h3>
                        <p class="text-sm text-gray-500">Mes tournois</p>
                    </div>
                </div>
            </a>

            <a href="<?= UrlHelper::url('newsletter') ?>" 
               class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl hover:border-2 hover:border-indigo-500 transition-all group">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3 group-hover:bg-indigo-500 transition-colors">
                        <svg class="h-8 w-8 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900">Newsletter</h3>
                        <p class="text-sm text-gray-500">Gérer les newsletters</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</body>
</html>