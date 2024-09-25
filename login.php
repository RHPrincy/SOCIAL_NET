<?php
    // Start the session
    session_start();        

    // messages d'erreur 
    $error = '';
    include('config.php');  // Include the database connection

    // Retrieve email and password from the POST request
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM compte WHERE email = :email AND password = :password");
    $stmt->execute(['email' => $email, 'password' => $password]);

    // Fetch the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // If user exists, set the session ID
        $_SESSION['id'] = $row['id'];
        // Redirect to home.php
        header("Location: home.php");
        exit();
    } 
    else {
    // If login fails, display an alert
    $error = "Erreur d'authentification";
    }
?>
