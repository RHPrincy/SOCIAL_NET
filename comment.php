<?php
// Include database connection
include("config.php"); 
// Include session handling
include("session.php"); 

if (isset($_POST['comment'])) {
    $comment = $_POST['content'];
    // Get the post ID
    $post_id = $_POST['post_id']; 

    // Prepare and execute SQL to insert comment
    $stmt = $conn->prepare("INSERT INTO comments (contenu, id_compte, id_publication, date) VALUES (:contenu, :id_compte, :id_publication, NOW())");
    $stmt->execute(['contenu' => $comment, 'id_compte' => $user_id, 'id_publication' => $post_id]);
    
    // Redirect to home page after commenting
    header('Location: home.php');
    exit();
}
?>
