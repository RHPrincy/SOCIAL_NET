<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="./output.css" rel="stylesheet">
    <?php include('config.php'); // Include database connection ?>
    <?php include('session.php'); // Include session handling ?>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto px-4 py-6">
        <div class="text-center mb-4 p-5 bg-gray-500">
            <p class="text-lg text-white">
                WELCOME! <?php echo htmlspecialchars($utilisateur); ?>
                <a href="logout.php" class="text-blue-500 hover:underline px-6"><button class="bg-blue-500 text-white py-1 px-3 rounded">Log Out</button></a>
            </p>
        </div>
        
        <form method="post" class="m-4 text-center">
            <textarea name="post_content" rows="4" placeholder="Publication" required class="w-full p-2 border border-gray-300 rounded-md"></textarea>
            <button name="post" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded">Partager la publication</button>
        </form>

        <?php
            // Vérifie si le formulaire de publication a été soumis
            if (isset($_POST['post'])) {
                // Récupère le contenu de la publication
                $post_content = $_POST['post_content'];

                // Prépare une requête SQL pour insérer une nouvelle publication dans la base de données
                $stmt = $conn->prepare("INSERT INTO publication (contenu, date, id_compte) VALUES (:contenu, NOW(), :id_compte)");
                
                // Exécute la requête en liant les valeurs de contenu et d'identifiant de compte
                $stmt->execute(['contenu' => $post_content, 'id_compte' => $user_id]);
                
                // Redirige l'utilisateur vers la page d'accueil après l'insertion
                header('Location: home.php');

                exit(); // Stoppe l'exécution du script après la redirection
            }

            // Prépare une requête SQL pour récupérer toutes les publications avec des informations sur l'utilisateur
            $stmt = $conn->prepare(" SELECT publication.id AS post_id, publication.contenu, publication.date, compte.nom, compte.prenom, UNIX_TIMESTAMP() - publication.date AS TimeSpent FROM publication LEFT JOIN compte ON compte.id = publication.id_compte ORDER BY publication.date DESC");
            $stmt->execute(); // Exécute la requête
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les publications sous forme de tableau associatif

            // Vérifie s'il y a des publications à afficher
            if ($posts) {
                // Parcourt chaque publication
                foreach ($posts as $post_row) {

                    // Échappe les noms pour éviter les problèmes XSS et concatène le nom et le prénom
                    $posted_by = htmlspecialchars($post_row['nom'] . " " . $post_row['prenom']);
        
                    // Affiche la publication dans un conteneur stylisé
                    echo "<div class='bg-white shadow-md rounded-lg p-10 mb-4 mx-auto max-w-md'>";
                        echo "<div class='flex flex-col flex-start'>";
                            // Affiche le nom de l'auteur et la date de la publication
                            echo "<p><b>{$posted_by}</b></p>"; 
                            echo "<p class='text-sm text-gray-400'>{$post_row['date']}</p>";

                            // Affiche le contenu de la publication
                            echo "<div class='bg-blue-500 px-2 text-sm text-white py-20'>";
                                echo "<p class='text-center'>{$post_row['contenu']}</p>";
                            echo "</div>";
                        echo "</div>";

                        // Initialise un tableau pour stocker les comptes de réactions
                        $reaction_counts = [];

                        // Prépare une requête SQL pour récupérer les comptes de réactions par type
                        $reaction_stmt = $conn->prepare("SELECT type, COUNT(*) AS count FROM reaction_publication WHERE id_publication = :post_id GROUP BY type");

                        // Lie l'ID de la publication
                        $reaction_stmt->bindParam(':post_id', $post_row['post_id']);

                        $reaction_stmt->execute(); // Exécute la requête

                        // Parcourt les résultats pour le tableau des comptes de réactions
                        while ($row = $reaction_stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Stocke le compte pour chaque type de réaction
                            $reaction_counts[$row['type']] = $row['count']; 
                        }

                        // Affiche un résumé des réactions avec des icônes
                        echo "<div class='flex justify-center space-x-6 mt-3'>";
                            foreach (['like', 'love', 'haha', 'angry', 'wow', 'sad'] as $reaction) {
                                // Vérifie si des réactions de ce type existent, sinon définit le compte à 0
                                $count = isset($reaction_counts[$reaction]) ? $reaction_counts[$reaction] : 0;
                                // Affiche l'icône de la réaction et son compte
                                echo "<span class='flex items-center'><i class='fas fa-" . ($reaction == 'like' ? 'thumbs-up' : ($reaction == 'love' ? 'heart' : ($reaction == 'haha' ? 'laugh' : ($reaction == 'angry' ? 'angry' : ($reaction == 'wow' ? 'surprise' : 'sad-tear'))))) . " px-2 text-blue-500'></i> {$count}</span>";
                            }
                        echo "</div>";

                        // Formulaire de réaction pour la publication
                        echo "<div class='mt-2 text-center'>";
                            echo "<button onclick='showReactionForm({$post_row['post_id']}, \"publication\")' class='bg-blue-500 px-2 rounded text-white'>Réagir</button>";
                            // Masque le formulaire par défaut
                            echo "<div id='reaction_form_publication_{$post_row['post_id']}' class='mt-2 hidden'>";
                                echo "<form method='post' action='reaction.php' class='flex justify-center items-center'>";
                                    // ID de la publication
                                    echo "<input type='hidden' name='post_id' value='{$post_row['post_id']}'>"; 
                                    echo "<select name='reaction_type' class='border border-gray-300 rounded-md p-1' required>";
                                        echo "<option value=''>Choisir une réaction</option>";
                                        // Remplit le menu déroulant avec les types de réactions
                                        foreach (['like', 'love', 'haha', 'angry', 'wow', 'sad'] as $reaction) {
                                            // ucfirst() en sert à convertir la première lettre d'une chaîne de caractères en majuscule, tout en laissant les autres lettres inchangée
                                            echo "<option value='{$reaction}'>" . ucfirst($reaction) . "</option>";
                                        }
                                    echo "</select>";
                                    echo "<button type='submit' class='ml-2 bg-blue-500 text-white py-1 px-3 rounded'>Réagir</button>";
                                echo "</form>";
                            echo "</div>";
                        echo "</div>";

                        // Formulaire pour commenter la publication
                        echo "<form method='post' action='comment.php' class='mt-4 text-center'>";
                            // ID de la publication
                            echo "<input type='hidden' name='post_id' value='{$post_row['post_id']}'>"; 
                            echo "<input type=\"text\" name='content' required placeholder='Commenter' class='w-full p-2 border border-gray-300 rounded-md'>"; // Champ pour le commentaire
                            // Bouton pour soumettre le commentaire
                            echo "<button type='submit' name='comment' class='mt-2 bg-blue-500 text-white py-1 px-3 rounded'>Commenter</button>";
                        echo "</form>";

                        // Prépare une requête SQL pour récupérer les commentaires de la publication
                        $stmt = $conn->prepare("SELECT comments.id_commentaire, comments.contenu, comments.date, compte.nom , compte.prenom, UNIX_TIMESTAMP() - comments.date AS TimeSpent FROM comments LEFT JOIN compte ON compte.id = comments.id_compte LEFT JOIN publication ON publication.id = comments.id_publication WHERE id_publication = {$post_row['post_id']} ORDER BY comments.date DESC");
                        $stmt->execute();
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                        // Vérifie s'il y a des commentaires à afficher
                        if ($comments) {
                            echo "<div class='mt-4 border-t pt-4'>";
                                echo "<h4 class='font-semibold text-center'>Commentaires:</h4>";
                                // Parcourt chaque commentaire
                                foreach ($comments as $comment_row) {
                                echo "<div class='border border-gray-300 p-4'>";
                                echo "<div class='flex flex-col flex-start'>";
                                    // L'utilisateur qui a commenté
                                    $commented_by = htmlspecialchars($comment_row['nom'] . " " . $comment_row['prenom']);
                                    // Affiche le commentaire avec l'auteur et la date
                                    echo "<p class='text-md'><b>{$commented_by} </b></p>";
                                    echo "<p class='text-sm text-gray-400'>{$comment_row['date']}</p>";
                                    // Affiche le contenu du commentaire
                                    echo "<div class='text-blue-500 p-2'>";
                                        echo "<p><b>{$comment_row['contenu']}</b></p>";
                                    echo "</div>";
                                echo "</div>";
                                    
                                
                                    // Compte des réactions pour le commentaire
                                    $comment_reaction_counts = [];
                                    // Prépare une requête SQL pour récupérer les comptes de réactions par type pour le commentaire
                                    $comment_reaction_stmt = $conn->prepare("SELECT type, COUNT(*) AS count FROM reaction_commentaire WHERE id_commentaire = :comment_id GROUP BY type");
                                    $comment_reaction_stmt->bindParam(':comment_id', $comment_row['id_commentaire']); // Lie l'ID du commentaire
                                    $comment_reaction_stmt->execute(); // Exécute la requête
                    
                                    // Parcourt les résultats pour peupler le tableau des comptes de réactions pour le commentaire
                                    while ($row = $comment_reaction_stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $comment_reaction_counts[$row['type']] = $row['count']; // Stocke le compte pour chaque type de réaction
                                    }
                    
                                    // Affiche un résumé des réactions pour le commentaire avec des icônes
                                    echo "<div class='flex justify-center space-x-6 mt-3'>";
                                        foreach (['like', 'love', 'haha', 'angry', 'wow', 'sad'] as $reaction) {
                                            $count = isset($comment_reaction_counts[$reaction]) ? $comment_reaction_counts[$reaction] : 0; // Compte des réactions ou 0
                                            // Affiche l'icône de la réaction et son compte
                                            echo "<span class='flex items-center'><i class='fas fa-" . ($reaction == 'like' ? 'thumbs-up' : ($reaction == 'love' ? 'heart' : ($reaction == 'haha' ? 'laugh' : ($reaction == 'angry' ? 'angry' : ($reaction == 'wow' ? 'surprise' : 'sad-tear'))))) . " px-2 text-blue-500'></i> {$count}</span>";
                                        }
                                    echo "</div>";
                                    
                        
                                    // Formulaire de réaction pour le commentaire
                                    echo "<div class='mt-2 text-center'>";
                                        echo "<button onclick='showReactionForm({$comment_row['id_commentaire']}, \"commentaire\")' class='bg-blue-500 px-2 rounded text-white'>Réagir</button>";
                                        
                                        echo "<div id='reaction_form_commentaire_{$comment_row['id_commentaire']}' class='mt-2 hidden'>";
                                            echo "<form method='post' action='reaction.php' class='flex justify-center items-center'>";
                                                echo "<input type='hidden' name='comment_id' value='{$comment_row['id_commentaire']}'>";
                                                echo "<select name='reaction_type' class='border border-gray-300 rounded-md p-1' required>";
                                                    echo "<option value=''>Choisir une réaction</option>";
                                                    // Remplit le menu déroulant avec les types de réactions
                                                    foreach (['like', 'love', 'haha', 'angry', 'wow', 'sad'] as $reaction) {
                                                        echo "<option value='{$reaction}'>" . ucfirst($reaction) . "</option>";
                                                    }
                                                echo "</select>";
                                                // Bouton pour soumettre le formulaire de réaction pour le commentaire
                                                echo "<button type='submit' class='ml-2 bg-blue-500 text-white py-1 px-3 rounded'>Réagir</button>";
                                            echo "</form>";
                                        echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            echo "</div>";
                        }

                    echo "</div>"; // Ferme le conteneur de la publication
                }
            } 
            else {
                // Si aucune publication n'est trouvée, affiche un message approprié
                echo "<p class='text-center'>Aucune publication à afficher.</p>";
            }
        ?>

    </div>

</body>
<script>

    // Fonction pour afficher ou masquer le formulaire de réaction
    function showReactionForm(id, type) {
        // Détermine l'ID du formulaire en fonction du type (publication ou commentaire)
        var formId = type === "publication" ? 'reaction_form_publication_' + id : 'reaction_form_commentaire_' + id;

        // Récupère l'élément du formulaire en utilisant son ID
        var form = document.getElementById(formId);

        // Alterne la classe 'hidden' sur le formulaire pour l'afficher ou le masquer
        form.classList.toggle('hidden');
    }
</script>


</html>
