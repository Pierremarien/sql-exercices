<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=colyseum;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Afficher tous les clients.
    $query = "SELECT id, lastName, firstName, birthDate, cardNumber, card, IF(card = 1, 'V', 'X') FROM clients";
    $stmt = $bdd->query($query);
    //Afficher tous les types de spectacles possibles.
    $query2 = "SELECT * FROM showtypes";
    $stmt2 = $bdd->query($query2);

    //Afficher les 20 premiers clients
    $query3 = "SELECT id, lastName, firstName FROM clients LIMIT 20";
    $stmt3 = $bdd->query($query3);

    // N'afficher que les clients possédant une carte de fidélité.
    $query4 = "SELECT lastName, firstName, birthDate, card FROM clients WHERE card = 1";
    $stmt4 = $bdd->query($query4);

    // Afficher uniquement le nom et le prénom de tous les clients dont le nom commence par la lettre "M".
    $query5 = "SELECT id, lastName, firstName FROM clients WHERE lastName LIKE 'M%'";
    $stmt5 = $bdd->query($query5);

    // Afficher le titre de tous les spectacles ainsi que l'artiste, la date et l'heure. Trier les titres par ordre alphabétique. Afficher les résultat comme ceci : Spectacle par artiste, le date à heure.
    $query6 = "SELECT title, performer, date, startTime FROM shows";
    $stmt6 = $bdd->query($query6);

    // Afficher tous les clients comme ceci :
    $query7 = "SELECT lastName, firstName, birthDate, card, cardNumber FROM clients";
    $stmt7 = $bdd->query($query7);

} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    	
    <table>
        <tr>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Birth Date</th>
            <th>Fidelity card</th>
            <th>Card Number</th>
        </tr>
        
        <?php
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
            <tr>
                <!-- Afficher tous les clients. -->
                <td><?= $row['lastName'] ?></td>
                <td><?= $row['firstName'] ?></td>
                <td><?= $row['birthDate'] ?></td>
                <td><?= $row['card'] ?></td>
                
            </tr>
        <?php endwhile;?>
    </table>  
    <!-- Afficher tous les types de spectacles possibles. -->
    <h2>Show types</h2>
    <?php
     while ($type = $stmt2->fetch(PDO::FETCH_ASSOC)):
    ?>
        	<p><?= $type['type'] ?></p>
    <?php endwhile;?>
    <!-- Afficher les 20 premiers clients -->
    <h2>20 premiers clients</h2>
    <table>
    <?php
     while ($row2 = $stmt3->fetch(PDO::FETCH_ASSOC)):
    ?>
        <tr>
            <td><?= $row2['lastName'] ?></td>
            <td><?= $row2['firstName'] ?></td>
        </tr>
    <?php endwhile;?>
    </table>
    <!-- N'afficher que les clients possédant une carte de fidélité. -->
    <h2>carte de fidelite</h2>
    <table>
    <?php
     while ($row3 = $stmt4->fetch(PDO::FETCH_ASSOC)):
    ?>
        <tr>
            <td><?= $row3['lastName'] ?></td>
            <td><?= $row3['firstName'] ?></td>
            <td><?= $row3['card'] ?></td>
        </tr>
    <?php endwhile;?>
    </table>
    <h2>M</h2>
    <!-- Afficher uniquement le nom et le prénom de tous les clients dont le nom commence par la lettre "M". -->
    <?php
     while ($row4 = $stmt5->fetch(PDO::FETCH_ASSOC)):
    ?>
        <tr>
            <td>Nom :<?= $row4['lastName'] ?></td>
            <td>Prénom :<?= $row4['firstName'] ?></td>
        </tr>
    <?php endwhile;?>
    <!-- Afficher le titre de tous les spectacles ainsi que l'artiste, la date et l'heure. Trier les titres par ordre alphabétique. Afficher les résultat comme ceci : Spectacle par artiste, le date à heure. -->
    <h2>shows</h2>
    <?php
     while ($row5 = $stmt6->fetch(PDO::FETCH_ASSOC)):
    ?>
        <p><?= $row5['title'] ?> par <?= $row5['performer'] ?>, le <?= $row5['date'] ?> a <?= $row5['startTime'] ?></p>
    <?php endwhile;?>
    <!-- Afficher tous les clients comme ceci : -->
    <h2>clients</h2>
    <table>
    <?php
    while ($row6 = $stmt7->fetch(PDO::FETCH_ASSOC)):
    ?>
        <tr>
            <td>Nom : <?= $row6['lastName'] ?></td>
            <td>Prénom : <?= $row6['firstName'] ?></td>
            <td>Date de naissance: <?= $row6['birthDate'] ?></td>
            <td>Carte de fidélité : <?= ($row6['card'] == 1) ? 'Oui' : 'Non' ?></td>
            <td>Numéro de carte : <?= $row6['cardNumber'] ?></td>
        </tr>
    <?php endwhile; ?>

    </table>

</body>
</html>