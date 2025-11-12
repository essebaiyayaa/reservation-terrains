<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Options</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
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

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 px-4 py-3 rounded-lg mb-6 animate-pulse">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-700 font-medium"><?= htmlspecialchars($_SESSION['success']) ?></span>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- En-tête -->
        <div class="mb-8 text-center">
            <div class="inline-block bg-purple-100 rounded-full p-4 mb-4">
                <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Gérer les Options Supplémentaires</h1>
            <p class="text-gray-600">
                Terrain: <span class="font-bold text-purple-600"><?= htmlspecialchars(is_object($terrain) ? $terrain->nom_terrain : $terrain['nom_terrain']) ?></span>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Formulaire d'ajout -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Ajouter une Option
                    </h2>
                </div>
                
                <form method="POST" action="<?= UrlHelper::url('gerant/options/' . (is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'])) ?>" class="p-6 space-y-6">
                    <!-- Nom de l'option -->
                    <div>
                        <label for="nom_option" class="block text-sm font-bold text-gray-700 mb-2">
                            Nom de l'Option <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nom_option" 
                               name="nom_option" 
                               placeholder="Ex: Ballons, Vestiaires, Éclairage..."
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                               required>
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="prix" class="block text-sm font-bold text-gray-700 mb-2">
                            Prix (MAD) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="prix" 
                                   name="prix" 
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-medium">MAD</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-lg hover:from-green-700 hover:to-green-800 font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Ajouter l'option
                    </button>
                </form>
            </div>

            <!-- Liste des options existantes -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6">
                    <h2 class="text-2xl font-bold text-white flex items-center justify-between">
                        <span class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Options Actuelles
                        </span>
                        <span class="bg-white text-purple-600 px-3 py-1 rounded-full text-sm font-bold">
                            <?= count($options) ?>
                        </span>
                    </h2>
                </div>

                <div class="p-6">
                    <?php if (empty($options)): ?>
                        <div class="text-center py-16">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Aucune option</h3>
                            <p class="text-gray-500">
                                Commencez par ajouter une option supplémentaire.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                            <?php foreach ($options as $option): ?>
                                <?php 
                                $optionId = is_object($option) ? $option->id_option : $option['id_option'];
                                $optionNom = is_object($option) ? $option->nom_option : $option['nom_option'];
                                $optionPrix = is_object($option) ? $option->prix : $option['prix'];
                                ?>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border-2 border-purple-200 hover:shadow-lg hover:border-purple-400 transition-all">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0 bg-purple-500 rounded-lg p-2 mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 text-lg">
                                                <?= htmlspecialchars($optionNom) ?>
                                            </h4>
                                            <p class="text-sm text-green-600 font-bold mt-1">
                                                <?= number_format($optionPrix, 2) ?> MAD
                                            </p>
                                        </div>
                                    </div>
                                    <button onclick="deleteOption(<?= $optionId ?>)" 
                                            class="ml-4 bg-red-500 hover:bg-red-600 text-white p-3 rounded-lg transition-all transform hover:scale-110 hover:shadow-lg"
                                            title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-6">
            <div class="flex">
                <svg class="h-6 w-6 text-yellow-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-bold text-yellow-800 mb-1">Information Importante</h4>
                    <p class="text-sm text-yellow-700">
                        Les options supplémentaires seront proposées aux clients lors de la réservation. 
                        Le prix de chaque option sélectionnée sera ajouté au prix total de la réservation.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function deleteOption(optionId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette option ?')) {
            return;
        }

        fetch('<?= UrlHelper::url("gerant/delete-option/") ?>' + optionId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + (data.message || 'Impossible de supprimer l\'option'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
    </script>
</body>
</html>