<?php
    include('ajout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NET</title>
    <link href="./../output.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <h2 class="text-center text-4xl mt-10">INSCRIPTION</h2>
    <div class="flex justify-center items-center px-4 py-6 mt-8">
        <form action="" method="post" class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="mt-4">
                <label for="nom" class="block text-blue-400">Nom :</label>
                <input class="w-full mt-2 px-4 py-2 border border-gray-600 rounded-md bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="nom" placeholder="Entrer votre nom" type="text" required><br><br>

                <label for="prenom" class="block text-blue-400">Prénoms :</label>
                <input class="w-full mt-2 px-4 py-2 border border-gray-600 rounded-md bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="prenom" placeholder="Entrer votre prénoms" type="text" required><br><br>

                <label for="email" class="block text-blue-400">Email :</label>
                <input class="w-full mt-2 px-4 py-2 border border-gray-600 rounded-md bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="email" placeholder="Entrer votre email" type="email" required><br><br>

                <label for="password" class="block text-blue-400">Mot de passe :</label>
                <input class="w-full mt-2 px-4 py-2 border border-gray-600 rounded-md bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="password" placeholder="Entrer votre mot de passe" type="password" required><br><br>
            </div>  
            <div class="mt-6 flex justify-center">
                <input name="submit" type="submit" value="S'inscrire" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">  
            </div>
        </form>
        <div class="text-center text-sm text-red-500">
        <!-- Affiche le message-->
        <?php echo $message; ?> 
    </div>
    </div>
    
    <div class="mt-4 text-center">Déjà un compte ? <a href="../index.php" class="text-blue-400 hover:underline">Se connecter</a></div>
    

</body>
</html>
