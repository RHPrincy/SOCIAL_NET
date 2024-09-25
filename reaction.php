<?php
// Include database connection
include("config.php"); 
// Include session handling
include("session.php");

try {
    // Vérifiez si une réaction a été soumise
    if (isset($_POST['reaction_type'])) {
        $reaction_type = $_POST['reaction_type']; // Récupère le type de réaction soumis

        // Préparez les variables pour l'insertion
        $stmt = null; // Initialise la variable de requête à null

        if (isset($_POST['post_id'])) {
            // Réaction sur une publication
            $post_id = $_POST['post_id']; // Récupère l'ID de la publication
            // Assurez-vous que l'entrée est unique en insérant ou en mettant à jour
            $stmt = $conn->prepare("INSERT INTO reaction_publication (id_publication, id_compte, type) VALUES (:post_id, :user_id, :reaction_type) ON DUPLICATE KEY UPDATE type = :reaction_type");
            $stmt->bindParam(':post_id', $post_id); // Lier l'ID de la publication
        } 
        elseif (isset($_POST['comment_id'])) {
            // Réaction sur un commentaire
            $comment_id = $_POST['comment_id']; // Récupère l'ID du commentaire
            // Assurez-vous que l'entrée est unique en insérant ou en mettant à jour
            $stmt = $conn->prepare("INSERT INTO reaction_commentaire (id_commentaire, id_compte, type) VALUES (:comment_id, :user_id, :reaction_type) ON DUPLICATE KEY UPDATE type = :reaction_type");
            $stmt->bindParam(':comment_id', $comment_id); // Lier l'ID du commentaire
        }

        // Liez les paramètres
        if ($stmt) {
            $stmt->bindParam(':user_id', $user_id); // Lier l'ID de l'utilisateur
            $stmt->bindParam(':reaction_type', $reaction_type); // Lier le type de réaction
            $stmt->execute(); // Exécute la requête

            // Redirection ou traitement après l'insertion
            header("Location: home.php"); // Redirige vers la page d'accueil
            exit(); // Terminer le script après la redirection
        } 
        else {
            throw new Exception("Aucune réaction valide fournie."); // Lance une exception si aucune entrée valide
        }
    } 
    else {
        throw new Exception("Aucune réaction soumise."); // Lance une exception si aucune réaction n'est soumise
    }
} 
catch (Exception $e) {
    echo "Erreur: " . $e->getMessage(); // Affichez l'erreur en cas d'exception
}
