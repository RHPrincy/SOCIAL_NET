<?php
    include('login.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NET</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <h2 class="text-center text-4xl mt-10">CONNEXION</h2>
    <div class="flex justify-center items-center px-4 py-6 mt-8">
        <form action="" method="post" class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="mt-4">
                <label for="email" class="block text-blue-400">Email :</label>
                <input id="email" name="email" placeholder="Entrer votre email" type="text" class="w-full mt-2 px-4 py-2 border border-gray-600 rounded-md bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div class="mt-4">
                <label for="password" class="block text-blue-400">Mot de passe :</label>
                <input id="password" name="password" placeholder="Entrer votre mot de passe" type="password" class="w-full mt-2 px-4 py-2 border border-gray-600 rounded-md bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div class="mt-6 flex justify-center">
                <input name="submit" type="submit" value="Se connecter" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            </div>

            <div class="mt-4 flex justify-end text-red-400">
                <a href="#" class="text-xs hover:underline">Mot de passe oubli&eacute;?</a>
            </div>

            <hr class="my-4 border-t border-gray-600">

            <div class="mt-4 flex justify-between">
                <span class="text-sm">Pas de compte?</span>
                <a href="./inscription/index.php" class="text-sm text-blue-400 hover:underline">S'inscrire</a>
            </div>
            <div class="text-center m-2">
                <!-- Affiche les messages d'erreur s'il y en a -->
                <span class="text-red-500 text-sm"><?php echo $error; ?></span> 
            </div>
        </form>
    </div>
</body>

</html>
