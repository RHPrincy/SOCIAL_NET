<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = '';

// Inclut le fichier de configuration de la base de données
require_once '../config.php'; 

try {
    // Vérification si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Requête SQL pour insérer les données dans la table 'admin'
        $sql = "INSERT INTO compte (nom, prenom, email, password) VALUES (:nom, :prenom, :email, :password)";

        // Préparation de la requête SQL
        $stmt = $conn->prepare($sql);

        // Liaison des paramètres avec les valeurs des variables PHP
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        // Exécution de la requête SQL
        if ($stmt->execute()) {
            // Si l'insertion est réussie, message de confirmation
            $message = "Inscription r&eacute;ussie>";
            // Optionnel : Redirection vers une page de confirmation ou de connexion
            header("Location: index.php");
            exit();
        } else {
            // Si l'insertion échoue, message d'erreur
            $message = "Erreur lors de l'inscription";
        }
    }
} catch (PDOException $e) {
    // Capture et affichage des erreurs PDO
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
} catch (Exception $e) {
    // Capture et affichage des autres erreurs
    echo "Erreur : " . $e->getMessage();
} finally {
    // Fermeture de la connexion PDO
    $conn = null;
}

?>
