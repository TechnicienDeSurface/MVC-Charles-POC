<?php
namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;

class BookController
{
    private BookRepository $bookRepository;
    
    public function __construct()
    {
        $this->bookRepository = new BookRepository();
    }
    
    public function add()
    {
        session_start();
        $error = null;
        
        // Traitement du formulaire POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['bookTitle'], $_POST['bookAuthor'], $_POST['nbPages'])) {
                $title = trim($_POST['bookTitle']);
                $author = trim($_POST['bookAuthor']);
                $nbPages = (int) $_POST['nbPages'];
                
                if (!empty($title) && !empty($author) && $nbPages > 0) {
                    try {
                        // Création du livre
                        $book = new Book();
                        $book->setTitre($title);
                        $book->setAuteur($author);
                        $book->setNbPages($nbPages);
                        $book->setDisponible(true); // Nouveau livre disponible par défaut
                        
                        // Sauvegarde via le repository
                        if ($this->bookRepository->add($book)) {
                            $_SESSION['success'] = "Livre ajouté avec succès!";
                            header('Location: /books');
                            exit();
                        } else {
                            $error = "Erreur lors de l'ajout du livre.";
                        }
                    } catch (\Exception $e) {
                        $error = $e->getMessage();
                    }
                } else {
                    $error = "Tous les champs doivent être remplis correctement.";
                }
            } else {
                $error = "Tous les champs sont requis.";
            }
        }
        
        // Affichage du formulaire
        $this->render('book/add', [
            'title' => 'Ajouter un livre',
            'error' => $error
        ]);
    }

    public function delete(int $id)
    {
        session_start();
        
        try {
            if ($id <= 0) {
                throw new \InvalidArgumentException("ID invalide.");
            }
            
            // Suppression via le repository
            if ($this->bookRepository->delete($id)) {
                $_SESSION['success'] = "Livre supprimé avec succès!";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression du livre.";
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /books');
        exit();
    }
    
    public function index()
    {
        session_start();
        
        try {
            // Récupération de tous les livres via le repository
            $books = $this->bookRepository->findAll();
            
            $this->render('book/index', [
                'title' => 'Liste des livres',
                'books' => $books
            ]);
        } catch (\Exception $e) {
            $this->render('book/index', [
                'title' => 'Liste des livres',
                'books' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function show(int $id)
    {
        session_start();
        
        try {
            if ($id <= 0) {
                throw new \InvalidArgumentException("ID invalide.");
            }
            
            // Récupération du livre via le repository
            $currentBook = $this->bookRepository->find($id);
            
            if (!$currentBook) {
                $_SESSION['error'] = "Livre non trouvé.";
                header('Location: /books');
                exit();
            }
            
            $this->render('book/show', [
                'title' => 'Détails du livre',
                'book' => $currentBook
            ]);
            
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /books');
            exit();
        }
    }
    
    public function edit(int $id)
    {
        session_start();
        $error = null;
        
        try {
            if ($id <= 0) {
                throw new \InvalidArgumentException("ID invalide.");
            }
            
            // Récupération du livre à modifier
            $book = $this->bookRepository->find($id);
            
            if (!$book) {
                $_SESSION['error'] = "Livre non trouvé.";
                header('Location: /books');
                exit();
            }
            
            // Traitement du formulaire POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['bookTitle'], $_POST['bookAuthor'], $_POST['nbPages'])) {
                    $title = trim($_POST['bookTitle']);
                    $author = trim($_POST['bookAuthor']);
                    $nbPages = (int) $_POST['nbPages'];
                    $disponible = isset($_POST['disponible']) ? (bool) $_POST['disponible'] : false;
                    
                    if (!empty($title) && !empty($author) && $nbPages > 0) {
                        try {
                            // Mise à jour des propriétés
                            $book->setTitre($title);
                            $book->setAuteur($author);
                            $book->setNbPages($nbPages);
                            $book->setDisponible($disponible);
                            
                            // Sauvegarde via le repository
                            if ($this->bookRepository->update($book)) {
                                $_SESSION['success'] = "Livre modifié avec succès!";
                                header('Location: /books');
                                exit();
                            } else {
                                $error = "Erreur lors de la modification du livre.";
                            }
                        } catch (\Exception $e) {
                            $error = $e->getMessage();
                        }
                    } else {
                        $error = "Tous les champs doivent être remplis correctement.";
                    }
                }
            }
            
            $this->render('book/edit', [
                'title' => 'Modifier le livre',
                'book' => $book,
                'error' => $error
            ]);
            
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /books');
            exit();
        }
    }
    
    public function borrow(int $id)
    {
        session_start();
        
        try {
            if ($id <= 0) {
                throw new \InvalidArgumentException("ID invalide.");
            }
            
            $book = $this->bookRepository->find($id);
            
            if (!$book) {
                $_SESSION['error'] = "Livre non trouvé.";
            } else {
                // Utilisation de la méthode emprunter() de l'entité
                if ($book->emprunter()) {
                    // Sauvegarde de l'état modifié
                    if ($this->bookRepository->update($book)) {
                        $_SESSION['success'] = "Livre emprunté avec succès!";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'emprunt.";
                    }
                }
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /books');
        exit();
    }
    
    public function return(int $id)
    {
        session_start();
        
        try {
            if ($id <= 0) {
                throw new \InvalidArgumentException("ID invalide.");
            }
            
            $book = $this->bookRepository->find($id);
            
            if (!$book) {
                $_SESSION['error'] = "Livre non trouvé.";
            } else {
                // Utilisation de la méthode rendre() de l'entité
                $book->rendre();
                
                // Sauvegarde de l'état modifié
                if ($this->bookRepository->update($book)) {
                    $_SESSION['success'] = "Livre rendu avec succès!";
                } else {
                    $_SESSION['error'] = "Erreur lors du retour.";
                }
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /books');
        exit();
    }
    
    /**
     * Méthode pour rendre une vue
     */
    private function render(string $view, array $data = [])
    {
        // Extraction des variables pour la vue
        extract($data);
        
        // Démarrage de la capture de sortie
        ob_start();
        
        // Inclusion de la vue
        $viewFile = __DIR__ . '/../../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new \Exception("Vue non trouvée : $view");
        }
        
        // Récupération du contenu
        $content = ob_get_clean();
        
        // Inclusion du layout principal (optionnel)
        if (file_exists(__DIR__ . '/../../views/layouts/app.php')) {
            include __DIR__ . '/../../views/layouts/app.php';
        } else {
            echo $content;
        }
    }
}