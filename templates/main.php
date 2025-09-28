<?php
include '../config/config.php';

use app\Repository\MediaRepository;

session_start();

$pdo = connectBD();

// Recherche avec Levenshtein (distance max 3)
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Paramètres de tri
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'titre';
$sortOrder = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'desc' : 'asc';

// Colonnes autorisées pour le tri
$allowedColumns = ['titre', 'auteur', 'type_media', 'date_creation', 'disponible'];
if (!in_array($sortColumn, $allowedColumns)) {
    $sortColumn = 'titre';
}

if (!empty($searchTerm)) {
    $allMedias = (new MediaRepository($pdo))->findAll();

    $medias = [];
    foreach ($allMedias as $media) {
        // Vérifier chaque champ avec Levenshtein
        $titre = strtolower($media['titre']);
        $auteur = strtolower($media['auteur']);
        $type = strtolower($media['type_media']);
        $search = strtolower($searchTerm);

        if (
            levenshtein($search, $titre) <= 3 ||
            levenshtein($search, $auteur) <= 3 ||
            levenshtein($search, $type) <= 3 ||
            strpos($titre, $search) !== false ||
            strpos($auteur, $search) !== false ||
            strpos($type, $search) !== false
        ) {
            $medias[] = $media;
        }
    }
} else {
    // Récupérer tous les médias si pas de recherche
    $stmt = $pdo->query("SELECT * FROM media");
    $medias = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction de tri
function sortMedias($medias, $column, $order)
{
    usort($medias, function ($a, $b) use ($column, $order) {
        $valueA = $a[$column];
        $valueB = $b[$column];

        // Tri spécial pour la disponibilité (booléen)
        if ($column == 'disponible') {
            $result = $valueA <=> $valueB;
        }
        // Tri pour les dates
        elseif ($column == 'date_creation') {
            $result = strtotime($valueA) <=> strtotime($valueB);
        }
        // Tri alphabétique standard
        else {
            $result = strcasecmp($valueA, $valueB);
        }

        return ($order == 'desc') ? -$result : $result;
    });
    return $medias;
}

// Appliquer le tri
$medias = sortMedias($medias, $sortColumn, $sortOrder);

// Fonction pour générer l'URL de tri
function getSortUrl($column, $currentColumn, $currentOrder, $searchTerm)
{
    $newOrder = 'asc';
    if ($column == $currentColumn && $currentOrder == 'asc') {
        $newOrder = 'desc';
    }

    $params = [
        'sort' => $column,
        'order' => $newOrder
    ];

    if (!empty($searchTerm)) {
        $params['search'] = $searchTerm;
    }

    return '?' . http_build_query($params);
}

if (!isset($_SESSION)) {
    header("Location: ../index.php");
    exit();
}



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord des médias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../src/assets/scripts/main.js"></script>
    <style>
        .sort-header {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .sort-header:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sort-header:active {
            transform: translateY(1px);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-red-900 via-red-600 to-rose-400 items-center justify-center p-4 relative">

    <nav class="bg-red-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 h-16 flex justify-between items-center">
            <h1 class="text-white text-xl font-bold">Mon Site</h1>
            <div class="flex items-center space-x-4">
                <span class="text-white">Bonjour <?= htmlspecialchars($_SESSION['username']) ?> !</span>
                <a href="../templates/auth/logout.php" class="text-white hover:text-red-200">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="glass-effect border border-white/20 rounded-3xl shadow-2xl p-8 w-full max-w-100 relative z-10">
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-gradient-to-r from-red-500 to-rose-400 rounded-full animate-glow">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mt-4 mb-2">Tableau de bord des médias</h1>
            <p class="text-red-100 opacity-80">Liste des médias et leur disponibilité - Cliquez sur les en-têtes pour trier</p>
        </div>

        <!-- Barre de recherche -->
        <form method="GET" class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input
                    type="text"
                    name="search"
                    id="searchInput"
                    class="block w-full pl-10 pr-20 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-red-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent backdrop-blur-sm"
                    placeholder="Rechercher par nom, auteur, type..."
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">

                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <!-- Conserver les paramètres de tri lors de la recherche -->
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sortColumn) ?>">
                    <input type="hidden" name="order" value="<?= htmlspecialchars($sortOrder) ?>">

                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-500/30 hover:bg-red-500/50 text-red-200 hover:text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <div class="flex justify-end mb-4 gap-4">
                <a href="../templates/book/add.php" class="text-white hover:text-red-200">Ajouter un livre</a>
                <a href="../templates/movie/add.php" class="text-white hover:text-red-200">Ajouter un film</a>
                <a href="../templates/album/add.php" class="text-white hover:text-red-200">Ajouter un album</a>
            </div>
            <table class="w-full text-white bg-white/10 rounded-xl overflow-hidden" id="mediaTable">
                <thead>
                    <tr class="bg-red-700/40">
                        <th class="py-3 px-4 text-left">
                            <a href="<?= getSortUrl('titre', $sortColumn, $sortOrder, $searchTerm) ?>"
                                class="sort-header flex items-center gap-2 p-2 -m-2 rounded">
                                <span>Nom du média</span>
                                <?php if ($sortColumn != 'titre'): ?>
                                    <svg class="w-4 h-4 text-red-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                <?php elseif ($sortOrder == 'asc'): ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th class="py-3 px-4 text-left">
                            <a href="<?= getSortUrl('auteur', $sortColumn, $sortOrder, $searchTerm) ?>"
                                class="sort-header flex items-center gap-2 p-2 -m-2 rounded">
                                <span>Auteur</span>
                                <?php if ($sortColumn != 'auteur'): ?>
                                    <svg class="w-4 h-4 text-red-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                <?php elseif ($sortOrder == 'asc'): ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th class="py-3 px-4 text-left">
                            <a href="<?= getSortUrl('type_media', $sortColumn, $sortOrder, $searchTerm) ?>"
                                class="sort-header flex items-center gap-2 p-2 -m-2 rounded">
                                <span>Type</span>
                                <?php if ($sortColumn != 'type_media'): ?>
                                    <svg class="w-4 h-4 text-red-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                <?php elseif ($sortOrder == 'asc'): ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th class="py-3 px-4 text-left">
                            <a href="<?= getSortUrl('disponible', $sortColumn, $sortOrder, $searchTerm) ?>"
                                class="sort-header flex items-center gap-2 p-2 -m-2 rounded">
                                <span>Disponibilité</span>
                                <?php if ($sortColumn != 'disponible'): ?>
                                    <svg class="w-4 h-4 text-red-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                <?php elseif ($sortOrder == 'asc'): ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </th>

                        <th class="py-3 px-4 text-left">
                            <div
                                class="sort-header flex items-center gap-2 p-2 -m-2 rounded">
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (count($medias) > 0): ?>
                        <?php foreach ($medias as $media): ?>
                            <tr class="border-b border-white/20 table-row hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4"><?= htmlspecialchars($media['titre']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($media['auteur']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($media['type_media']) ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($media['disponible']): ?>
                                        <span class="px-3 py-1 rounded-full bg-green-500/30 text-green-200 font-semibold">Disponible</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full bg-red-500/30 text-red-200 font-semibold">Indisponible</span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($media['type_media'] === 'book'): ?>
                                    <td class="py-3 px-4">
                                        <a href="../templates/book/edit.php?id=<?= $media['id'] ?>">Modifier</a>
                                        <a href="../templates/book/delete.php?id=<?= $media['id'] ?>">Supprimer</a>
                                        <?php if ($media['disponible']): ?>
                                            <a href="../templates/book/borrow.php?id=<?= $media['id'] ?>">Emprunter</a>
                                        <?php else: ?>
                                            <a href="../templates/book/return.php?id=<?= $media['id'] ?>">Rendre</a>
                                        <?php endif; ?>
                                    </td>
                                <?php elseif ($media['type_media'] === 'album'): ?>
                                    <td class="py-3 px-4">
                                        <a href="../templates/album/edit.php?id=<?= $media['id'] ?>">Modifier</a>
                                        <a href="../templates/album/delete.php?id=<?= $media['id'] ?>">Supprimer</a>
                                        <?php if ($media['disponible']): ?>
                                            <a href="../templates/album/borrow.php?id=<?= $media['id'] ?>">Emprunter</a>
                                        <?php else: ?>
                                            <a href="../templates/album/return.php?id=<?= $media['id'] ?>">Rendre</a>
                                        <?php endif; ?>
                                    </td>
                                <?php elseif ($media['type_media'] === 'movie'): ?>
                                    <td class="py-3 px-4">
                                        <a href="../templates/movie/edit.php?id=<?= $media['id'] ?>">Modifier</a>
                                        <a href="../templates/movie/delete.php?id=<?= $media['id'] ?>">Supprimer</a>
                                        <?php if ($media['disponible']): ?>
                                            <a href="../templates/movie/borrow.php?id=<?= $media['id'] ?>">Emprunter</a>
                                        <?php else: ?>
                                            <a href="../templates/movie/return.php?id=<?= $media['id'] ?>">Rendre</a>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-red-100 opacity-70">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">Aucun média trouvé</p>
                                <p class="text-sm">Essayez de modifier votre recherche</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>