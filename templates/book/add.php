

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Ajouter un livre') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-green-900 via-green-600 to-emerald-400 flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Add Book Container -->
    <div class="glass-effect border border-white/20 rounded-3xl shadow-2xl p-8 w-full max-w-lg relative z-10 transform hover:scale-105 transition-all duration-300">

        <!-- Logo/Icon -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-gradient-to-r from-green-500 to-emerald-400 rounded-full animate-glow">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mt-4 mb-2">Ajouter un livre</h1>
            <p class="text-green-100 opacity-80">Enrichissez votre bibliothèque</p>
        </div>

        <!-- Messages de retour -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-400/30 text-red-100">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Navigation -->
        <div class="mb-6">
            <a href="../main.php" class="inline-flex items-center text-green-100 hover:text-white transition-colors duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
        </div>

        <form method="POST" action="" class="space-y-6">

            <!-- Titre du livre -->
            <div class="space-y-2">
                <label for="bookTitle" class="text-sm font-medium text-white block">
                    Titre du livre <span class="text-red-300">*</span>
                </label>
                <div class="relative">
                    <input
                        type="text"
                        id="bookTitle"
                        name="bookTitle"
                        placeholder="Ex: Le Petit Prince"
                        value="<?= isset($_POST['bookTitle']) ? htmlspecialchars($_POST['bookTitle']) : '' ?>"
                        required
                        maxlength="255"
                        class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:border-green-300 focus:bg-white/20 transition-all duration-300 input-glow">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="w-5 h-5 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Auteur -->
            <div class="space-y-2">
                <label for="bookAuthor" class="text-sm font-medium text-white block">
                    Auteur <span class="text-red-300">*</span>
                </label>
                <div class="relative">
                    <input
                        type="text"
                        id="bookAuthor"
                        name="bookAuthor"
                        placeholder="Ex: Antoine de Saint-Exupéry"
                        value="<?= isset($_POST['bookAuthor']) ? htmlspecialchars($_POST['bookAuthor']) : '' ?>"
                        required
                        maxlength="255"
                        class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:border-green-300 focus:bg-white/20 transition-all duration-300 input-glow">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="w-5 h-5 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Nombre de pages -->
            <div class="space-y-2">
                <label for="nbPages" class="text-sm font-medium text-white block">
                    Nombre de pages <span class="text-red-300">*</span>
                </label>
                <div class="relative">
                    <input
                        type="number"
                        id="nbPages"
                        name="nbPages"
                        placeholder="Ex: 96"
                        value="<?= isset($_POST['nbPages']) ? htmlspecialchars($_POST['nbPages']) : '' ?>"
                        required
                        min="1"
                        max="9999"
                        class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:border-green-300 focus:bg-white/20 transition-all duration-300 input-glow">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    </div>
                </div>
                <p class="text-xs text-green-200">Entre 1 et 9999 pages</p>
            </div>

            <!-- Note d'information -->
            <div class="p-4 bg-green-500/10 border border-green-400/20 rounded-xl">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-300 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-green-200">
                            <strong>Information :</strong> Le livre sera automatiquement marqué comme disponible à l'emprunt après sa création.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex space-x-4">
                <!-- Bouton Annuler -->
                <a href="/books" 
                   class="flex-1 bg-gray-500/20 hover:bg-gray-500/30 border border-gray-400/30 text-gray-200 font-semibold py-3 px-4 rounded-xl transition-all duration-300 text-center">
                    Annuler
                </a>

                <!-- Bouton Ajouter -->
                <button
                    type="submit"
                    name="submit"
                    class="flex-1 bg-gradient-to-r from-green-500 to-emerald-400 hover:from-green-600 hover:to-emerald-500 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-green-300/50">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter le livre
                    </span>
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <p class="text-green-100 text-sm">
                <span class="text-red-300">*</span> Champs obligatoires
            </p>
        </div>
    </div>

</body>
</html>