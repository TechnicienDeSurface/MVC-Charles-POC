<?php

require '../../config/config.php';

$message = '';
$messageType = '';

// Cas où le formulaire est validé
if (isset($_POST['submit'])) {
    // Tests si les 3 champs ont été remplis
    if (isset($_POST['username'], $_POST['email'], $_POST['password']) && 
        !empty(trim($_POST['username'])) && 
        !empty(trim($_POST['email'])) && 
        !empty(trim($_POST['password']))) {
        
        // Récupération et nettoyage des 3 saisies du formulaire
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $plainPassword = $_POST['password'];
        
        // Validation du mot de passe avec regex
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        
        if (!preg_match($passwordRegex, $plainPassword)) {
            $message = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&).";
            $messageType = 'error';
        } elseif (stripos($plainPassword, $username) !== false) {
            $message = "Le mot de passe ne doit pas contenir votre nom d'utilisateur.";
            $messageType = 'error';
        } else {
            $password = password_hash($plainPassword, PASSWORD_ARGON2I);

            try {
                // Connexion à la BD
                $co = connectBD();

                // Vérification si l'utilisateur ou l'email existe déjà
                $checkQuery = $co->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
                $checkQuery->bindParam(':username', $username);
                $checkQuery->bindParam(':email', $email);
                $checkQuery->execute();
                
                if ($checkQuery->fetchColumn() > 0) {
                    $message = "Cet nom d'utilisateur ou cette adresse email est déjà utilisé.";
                    $messageType = 'error';
                } else {
                    // Préparation de la requête d'insertion avec le bon nom de colonne
                    $query = $co->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");

                    // Association des paramètres aux variables/valeurs
                    $query->bindParam(':username', $username);
                    $query->bindParam(':email', $email);
                    $query->bindParam(':password_hash', $password);

                    // Exécution de la requête
                    if ($query->execute()) {
                        $message = "Vous êtes inscrit avec succès.";
                        $messageType = 'success';
                    } else {
                        $message = "Erreur lors de l'inscription. Veuillez réessayer.";
                        $messageType = 'error';
                    }
                }
            } catch (Exception $e) {
                $message = "Erreur de base de données : " . $e->getMessage();
                $messageType = 'error';
            }
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../../src/assets/styles/register.css" rel="stylesheet">
    <script src="../../src/assets/scripts/register.js"></script>

</head>

<body class="min-h-screen bg-gradient-to-br from-red-900 via-red-600 to-rose-400 flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Register Container -->
    <div class="glass-effect border border-white/20 rounded-3xl shadow-2xl p-8 w-full max-w-md relative z-10 transform hover:scale-105 transition-all duration-300">

        <!-- Logo/Icon -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-gradient-to-r from-red-500 to-rose-400 rounded-full animate-glow">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mt-4 mb-2">Inscription</h1>
            <p class="text-red-100 opacity-80">Créez votre compte en quelques minutes</p>
        </div>

        <!-- Messages de retour -->
        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 rounded-xl <?= $messageType === 'success' ? 'bg-green-500/20 border border-green-400/30 text-green-100' : 'bg-red-500/20 border border-red-400/30 text-red-100' ?>">
                <?php if ($messageType === 'success'): ?>
                    <p><?= htmlspecialchars($message) ?></p>
                    <p class="mt-2">
                        <a href="login.php" class="text-green-200 hover:text-white font-semibold underline">
                            Cliquez ici pour vous connecter
                        </a>
                    </p>
                <?php else: ?>
                    <p><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5">

            <div class="space-y-2">
                <label class="text-sm font-medium text-white block">Nom d'utilisateur</label>
                <div class="relative">
                    <input
                        type="text"
                        name="username"
                        placeholder="Nom d'utilisateur"
                        value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                        required
                        class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-red-200 focus:outline-none focus:border-red-300 focus:bg-white/20 transition-all duration-300 input-glow">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-white block">Adresse email</label>
                <div class="relative">
                    <input
                        type="email"
                        name="email"
                        placeholder="votre@email.com"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        required
                        class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-red-200 focus:outline-none focus:border-red-300 focus:bg-white/20 transition-all duration-300 input-glow">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-white block">Mot de passe</label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        placeholder="Mot de passe"
                        id="password"
                        required
                        minlength="8"
                        class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-red-200 focus:outline-none focus:border-red-300 focus:bg-white/20 transition-all duration-300 input-glow">
                    <button type="button" class="absolute right-3 top-3 text-red-200 hover:text-white transition-colors">
                        <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                <p id="strength-text" class="text-xs text-red-200 mt-1">8 caractères min, majuscule, minuscule, chiffre et caractère spécial (@$!%*?&)</p>
            </div>

            <!-- Register Button -->
            <button
                type="submit"
                name="submit"
                class="w-full bg-gradient-to-r from-red-500 to-rose-400 hover:from-red-600 hover:to-rose-500 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-red-300/50 disabled:opacity-50 disabled:cursor-not-allowed"
                id="submitBtn">
                <span class="flex items-center justify-center">
                    Créer mon compte
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </span>
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-red-100">
                Déjà un compte ?
                <a href="../../templates/auth/login.php" class="text-white font-semibold hover:text-red-200 transition-colors duration-300 hover:underline">
                    Se connecter
                </a>
            </p>
        </div>
    </div>

</body>

</html>