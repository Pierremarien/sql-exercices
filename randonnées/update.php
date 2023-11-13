<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=rando;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $difficulty = $_POST['difficulty'];
            $distance = $_POST['distance'];
            $duration = $_POST['duration'];
            $height_difference = $_POST['height_difference'];

            $stmt = $bdd->prepare("UPDATE hiking SET name = :name, difficulty = :difficulty, distance = :distance, duration = :duration, height_difference = :height_difference WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':difficulty', $difficulty);
            $stmt->bindParam(':distance', $distance);
            $stmt->bindParam(':duration', $duration);
            $stmt->bindParam(':height_difference', $height_difference);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo "<div class='succes'>La randonnée a été ajoutée avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de l'ajout de la randonnée.</div>";
            }
        }
        }
        $query = "SELECT * FROM hiking WHERE id = :id";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $hike = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Invalid request. Please provide a valid hike ID.";
    }
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajouter une randonnée</title>
	<link rel="stylesheet" href="css/basics.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<a href="/sql-exercices/randonnées/read.php">Liste des données</a>
	<h1>Ajouter</h1>
	<form action="" method="post">
		<div>
			<label for="name">Name</label>
			<input type="text" name="name" value="">
		</div>

		<div>
			<label for="difficulty">Difficulté</label>
			<select name="difficulty">
				<option value="très facile">Très facile</option>
				<option value="facile">Facile</option>
				<option value="moyen">Moyen</option>
				<option value="difficile">Difficile</option>
				<option value="très difficile">Très difficile</option>
			</select>
		</div>
		
		<div>
			<label for="distance">Distance</label>
			<input type="text" name="distance" value="">
		</div>
		<div>
			<label for="duration">Durée</label>
			<input type="duration" name="duration" value="">
		</div>
		<div>
			<label for="height_difference">Dénivelé</label>
			<input type="text" name="height_difference" value="">
		</div>
		<button type="submit" name="button">Envoyer</button>
	</form>
</body>
</html>