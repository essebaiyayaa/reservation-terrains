<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Terrains</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation -->
        <div class="mb-6">
            <a href="<?= UrlHelper::url('gerant/dashboard') ?>" 
               class="text-blue-600 hover:text-blue-800 flex items-center font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour au tableau de bord
            </a>
        </div>

        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Mes Terrains</h1>
            <p class="text-gray-600">Gérez vos terrains et leurs options</p>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($terrains)): ?>
            <!-- État vide -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun terrain assigné</h3>
                <p class="text-gray-500 mb-4">
                    Vous n'avez pas encore de terrain assigné à votre compte.
                </p>
                <p class="text-sm text-gray-400">
                    Contactez un administrateur pour qu'un terrain vous soit assigné.
                </p>
            </div>
        <?php else: ?>
            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <p class="text-sm text-gray-500 font-medium mb-1">Total Terrains</p>
                    <p class="text-3xl font-bold text-gray-900"><?= count($terrains) ?></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <p class="text-sm text-gray-500 font-medium mb-1">Prix Moyen/h</p>
                    <p class="text-3xl font-bold text-green-600">
                        <?php 
                        $avgPrice = array_sum(array_map(fn($t) => is_object($t) ? $t->prix_heure : $t['prix_heure'], $terrains)) / count($terrains);
                        echo number_format($avgPrice, 2);
                        ?> MAD
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <p class="text-sm text-gray-500 font-medium mb-1">Total Réservations</p>
                    <p class="text-3xl font-bold text-blue-600">
                        <?= array_sum(array_map(fn($t) => is_object($t) ? $t->nb_reservations : $t['nb_reservations'], $terrains)) ?>
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <p class="text-sm text-gray-500 font-medium mb-1">Villes</p>
                    <p class="text-3xl font-bold text-purple-600">
                        <?= count(array_unique(array_map(fn($t) => is_object($t) ? $t->ville : $t['ville'], $terrains))) ?>
                    </p>
                </div>
            </div>

            <!-- Liste des terrains -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Terrain
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Localisation
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Type/Taille
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Prix
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Réservations
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($terrains as $terrain): ?>
                                <?php
                                $id = is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'];
                                $nom = is_object($terrain) ? $terrain->nom_terrain : $terrain['nom_terrain'];
                                $adresse = is_object($terrain) ? $terrain->adresse : $terrain['adresse'];
                                $ville = is_object($terrain) ? $terrain->ville : $terrain['ville'];
                                $type = is_object($terrain) ? $terrain->type : $terrain['type'];
                                $taille = is_object($terrain) ? $terrain->taille : $terrain['taille'];
                                $prix = is_object($terrain) ? $terrain->prix_heure : $terrain['prix_heure'];
                                $nbRes = is_object($terrain) ? $terrain->nb_reservations : $terrain['nb_reservations'];
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">
                                                    <?= htmlspecialchars($nom) ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    ID: <?= $id ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($ville) ?></div>
                                        <div class="text-xs text-gray-500"><?= htmlspecialchars($adresse) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                            <?= htmlspecialchars($type) ?>
                                        </span>
                                        <span class="ml-1 px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">
                                            <?= htmlspecialchars($taille) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">
                                            <?= number_format($prix, 2) ?> MAD/h
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <span class="font-bold"><?= $nbRes ?></span> réservations
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="<?= UrlHelper::url('gerant/terrain/' . $id) ?>" 
                                               class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                                               title="Voir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="<?= UrlHelper::url('gerant/edit-terrain/' . $id) ?>" 
                                               class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors"
                                               title="Modifier">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <a href="<?= UrlHelper::url('gerant/options/' . $id) ?>" 
                                               class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors"
                                               title="Options">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>