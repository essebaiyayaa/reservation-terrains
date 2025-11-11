<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Newsletter - <?= SITE_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        
        <!-- Header avec retour -->
        <div class="mb-6">
            <a 
                href="<?= UrlHelper::url('newsletter') ?>"
                class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-4"
            >
                ← Retour aux newsletters
            </a>
            <h1 class="text-3xl font-bold text-gray-800">📧 Détails de la Newsletter</h1>
        </div>

        <!-- Card principale -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            
            <!-- Header de la card avec statut -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-2">
                            <?= htmlspecialchars($newsletter->sujet) ?>
                        </h2>
                        <p class="text-green-100">
                            Envoyée par <strong><?= htmlspecialchars($newsletter->prenom . ' ' . $newsletter->nom) ?></strong>
                            (<?= ucfirst($newsletter->role_expediteur) ?>)
                        </p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold <?= 
                        $newsletter->statut === 'envoye' ? 'bg-white text-green-600' : 
                        ($newsletter->statut === 'echoue' ? 'bg-red-500 text-white' : 'bg-yellow-400 text-gray-800')
                    ?>">
                        <?= ucfirst(str_replace('_', ' ', $newsletter->statut)) ?>
                    </span>
                </div>
            </div>

            <!-- Informations -->
            <div class="p-6">
                
                <!-- Statistiques -->
                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-blue-600 text-sm font-medium mb-1">Date d'envoi</div>
                        <div class="text-gray-800 font-semibold">
                            <?= date('d/m/Y à H:i', strtotime($newsletter->date_envoi)) ?>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-green-600 text-sm font-medium mb-1">Destinataires</div>
                        <div class="text-gray-800 font-semibold text-2xl">
                            <?= $newsletter->nombre_destinataires ?>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="text-purple-600 text-sm font-medium mb-1">Pièce jointe</div>
                        <div class="text-gray-800 font-semibold">
                            <?php if ($newsletter->piece_jointe): ?>
                                <span class="text-green-600">✓ Oui</span>
                            <?php else: ?>
                                <span class="text-gray-400">✗ Non</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">📝 Contenu du message</h3>
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="prose max-w-none text-gray-700">
                            <?= nl2br(htmlspecialchars($newsletter->contenu)) ?>
                        </div>
                    </div>
                </div>

                <!-- Pièce jointe -->
                <?php if ($newsletter->piece_jointe): ?>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">📎 Pièce jointe</h3>
                        <div class="bg-blue-50 rounded-lg p-4 flex items-center justify-between border border-blue-200">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-800">
                                        <?= htmlspecialchars($newsletter->piece_jointe) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php 
                                        $filePath = __DIR__ . '/../../uploads/newsletters/' . $newsletter->piece_jointe;
                                        if (file_exists($filePath)) {
                                            $fileSize = filesize($filePath);
                                            echo round($fileSize / 1024, 2) . ' KB';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <a 
                                href="<?= UrlHelper::url('uploads/newsletters/' . $newsletter->piece_jointe) ?>"
                                download
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                            >
                                Télécharger
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Aperçu email -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">👁️ Aperçu de l'email envoyé</h3>
                    <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                            <div class="text-xs text-gray-600">
                                <strong>De:</strong> <?= SITE_NAME ?> &lt;<?= MAIL_FROM_ADDRESS ?>&gt;
                            </div>
                            <div class="text-xs text-gray-600">
                                <strong>Sujet:</strong> <?= htmlspecialchars($newsletter->sujet) ?>
                            </div>
                        </div>
                        <div class="p-4 bg-white max-h-96 overflow-y-auto">
                            <?php
                            // Générer l'aperçu avec le template
                            $previewHtml = Template::render(Template::$NEWSLETTER_TEMPLATE, [
                                'name' => 'Utilisateur Example',
                                'content' => nl2br(htmlspecialchars($newsletter->contenu))
                            ]);
                            echo $previewHtml;
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <a 
                        href="<?= UrlHelper::url('newsletter') ?>"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center font-semibold py-3 px-6 rounded-lg transition duration-200"
                    >
                        ← Retour
                    </a>
                    
                    <?php if ($currentUser->role === 'admin'): ?>
                        <button 
                            onclick="deleteNewsletter(<?= $newsletter->id_newsletter ?>)"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                        >
                            🗑️ Supprimer
                        </button>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </div>

    <script>
        function deleteNewsletter(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette newsletter ?\n\nCette action est irréversible.')) {
                return;
            }

            fetch('<?= UrlHelper::url('newsletter/delete/') ?>' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Newsletter supprimée avec succès');
                    window.location.href = '<?= UrlHelper::url('newsletter') ?>';
                } else {
                    alert('Erreur: ' + (data.message || 'Impossible de supprimer la newsletter'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la suppression');
            });
        }
    </script>
</body>
</html>