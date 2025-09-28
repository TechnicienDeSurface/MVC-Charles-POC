<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="src/assets/styles/index.css">
</head>

<body class="min-h-screen flex flex-col">
    <?php include 'templates/part/header.html'; ?>

    <div class="flex-grow flex flex-col items-center justify-center bg-gray-100">
        <h1 class="text-5xl font-bold mb-8 animate-spin-slow">
            Bienvenue Charles !
        </h1>

        <a href="templates/auth/login.php">
            <button class="bg-red-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-red-700 transition">
                Connexion
            </button>
        </a>
    </div>
    <?php include 'templates/part/footer.html'; ?>
</body>

</html>