<?php
require '../../config/config.php';

// Démarrage d'une session
session_start();

$message = '';
$messageType = '';

// Cas où le formulaire est validé
if (isset($_POST['submit'])) {
    // Vérifier que les champs sont remplis
    if (isset($_POST['username'], $_POST['password']) && 
        !empty(trim($_POST['username'])) && 
        !empty(trim($_POST['password']))) {
        
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        try {
            // Connexion à la BD
            $co = connectBD();
            
            // Préparation de la requête avec le bon nom de colonne
            $query = $co->prepare('SELECT id, username, email, password_hash FROM users WHERE username = :login');
            
            // Association des paramètres aux variables/valeurs
            $query->bindParam(':login', $username);
            
            // Exécution de la requête
            $query->execute();
            
            // Récupération du résultat
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            if (empty($result)) {
                // Si la requête ne retourne rien, l'utilisateur n'existe pas
                $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                $messageType = 'error';
            } else {
                // Vérification du mot de passe avec le bon nom de colonne
                $password_hash = $result["password_hash"];
                $valid = password_verify($password, $password_hash);
                
                if ($valid) {
                    // Définir les variables de session
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['username'] = $result['username'];
                    $_SESSION['email'] = $result['email'];
                    $_SESSION['logged_in'] = true;
                    
                    // Redirection vers la page d'accueil
                    header("Location: ../main.php");
                    exit(); // Important : arrêter l'exécution après la redirection
                } else {
                    $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                    $messageType = 'error';
                }
            }
        } catch (Exception $e) {
            $message = "Erreur de connexion : " . $e->getMessage();
            $messageType = 'error';
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
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-600 to-indigo-400 flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Floating Elements Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute top-3/4 right-1/4 w-72 h-72 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-80 h-80 bg-blue-700 rounded-full mix-blend-multiply filter blur-xl opacity-15 animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/2 right-1/3 w-56 h-56 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-25 animate-pulse-slow"></div>
    </div>

    <!-- Login Container -->
    <div class="glass-effect border border-white/20 rounded-3xl shadow-2xl p-8 w-full max-w-md relative z-10 transform hover:scale-105 transition-all duration-300">

        <!-- Logo/Icon -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-gradient-to-r from-blue-500 to-indigo-400 rounded-full animate-glow">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mt-4 mb-2">Connexion</h1>
            <p class="text-blue-100 opacity-80">Accédez à votre compte</p>
        </div>

        <!-- Messages de retour -->
        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 rounded-xl <?= $messageType === 'success' ? 'bg-green-500/20 border border-green-400/30 text-green-100' : 'bg-red-500/20 border border-red-400/30 text-red-100' ?>">
                <p><?= htmlspecialchars($message) ?></p>
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
                        class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-blue-200 focus:outline-none focus:border-blue-300 focus:bg-white/20 transition-all duration-300 input-glow">
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
                        class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-blue-200 focus:outline-none focus:border-blue-300 focus:bg-white/20 transition-all duration-300 input-glow">
                    <button type="button" class="absolute right-3 top-3 text-blue-200 hover:text-white transition-colors">
                    </button>
                </div>
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                name="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-400 hover:from-blue-600 hover:to-indigo-500 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300/50">
                <span class="flex items-center justify-center">
                    Se connecter
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </span>
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-blue-100">
                Pas encore de compte ?
                <a href="register.php" class="text-white font-semibold hover:text-blue-200 transition-colors duration-300 hover:underline">
                    S'inscrire
                </a>
            </p>
        </div>
    </div>

</body>
</html>