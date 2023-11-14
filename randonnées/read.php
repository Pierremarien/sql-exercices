<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Randonnées</title>
    <link rel="stylesheet" href="css/basics.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <h1>Liste des randonnées</h1>
    <form method="post" action="delete.php">
        <table>
        <!-- Afficher la liste des randonnées -->
        <?php
        try {
        $bdd = new PDO('mysql:host=localhost;dbname=rando;charset=utf8', 'root', '');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM hiking";
        $result = $bdd->query($query);
        
        
        echo '<tr><th>Name</th><th>Difficulty</th><th>Distance</th><th>Duration</th><th>Height Difference</th><th>Delete</th></tr>';
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td><a href="/sql-exercices/randonnées/update.php?id=' . $row['id'] . '">' . $row['name'] . '</a></td>';
            echo '<td>' . $row['difficulty'] . '</td>';
            echo '<td>' . $row['distance'] . '</td>';
            echo '<td>' . $row['duration'] . '</td>';
            echo '<td>' . $row['height_difference'] . '</td>';
            echo '<td><input type="checkbox" name="delete_ids[]" value="' . $row['id'] . '"></td>';
            echo '</tr>';
        }
        echo '</form>';

    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
    ?>
        </table>
        <input type="submit" value="Delete Selected">
    </form>
  </body>
</html>
