<?php
require 'config.php';
session_start();
if (!isset($_SESSION['email'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
    header("Location: login.php");
    exit;
}

echo "Bienvenue, " . htmlspecialchars($_SESSION['email']) . " !";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>

	<style type="text/css">
	
	#text{

		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 100%;
	}

	#button{

		padding: 10px;
		width: 100px;
		color: white;
		background-color: lightblue;
		border: none;
	}

	#box{

		background-color: grey;
		margin: auto;
		width: 300px;
		padding: 20px;
	}

	</style>

	<div id="box">
		
		<form method="post">
			<a href="liste_entreprises.php">voir la liste des entreprise</a><br><br>
			<a href="liste_offres_stage.php">voir la liste des stages</a><br><br>
			<a href="wish_list.php">voir la lists des wish-list</a><br><br>
			<a href="liste_etudiants.php">voir la liste des etudiants</a><br><br>
			<a href="liste_pilotes.php">voir la liste des pilotes</a><br><br>
			<a href="signout.php">se deconnecter</a><br><br>
		</form>
	</div>
</body>
</html>
