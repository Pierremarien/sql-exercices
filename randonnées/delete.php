<?php
/**** Supprimer une randonnée ****/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_ids']) && is_array($_POST['delete_ids'])) {
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=rando;charset=utf8', 'root', '');
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            foreach ($_POST['delete_ids'] as $delete_id) {
                $stmt = $bdd->prepare("DELETE FROM hiking WHERE id = :id");
                $stmt->bindParam(':id', $delete_id);
                $stmt->execute();
            }

            header('Location: read.php');
            exit();
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}

header('Location: read.php');
exit();
?>