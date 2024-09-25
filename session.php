<?php
    // Start the session
    session_start();
    if (!isset($_SESSION['id'])) { // Check if user is logged in
        header('location:index.php'); // Redirect to index if not
        exit();
}

$user_id = $_SESSION['id']; // Get user ID from session

// Prepare and execute SQL statement to fetch user details
$stmt = $conn->prepare("SELECT * FROM compte WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$member_row = $stmt->fetch(PDO::FETCH_ASSOC);

// Full name of the user
$utilisateur = htmlspecialchars($member_row['nom'] . " " . $member_row['prenom']); 
?>
