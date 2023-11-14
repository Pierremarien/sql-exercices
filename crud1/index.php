<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=colyseum;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $query = "SELECT id, lastName, firstName, birthDate, card, IF(card = 1, 'V', 'X') AS member FROM clients";
    $stmt = $bdd->query($query);

   
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
            <th>Member</th>
        </tr>
        <?php
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
            <tr>
                <td><?= $row['lastName'] ?></td>
                <td><?= $row['firstName'] ?></td>
                <td><?= $row['birthDate'] ?></td>
                <td><?= $row['member'] ?></td>
            </tr>
        <?php endwhile;?>
    </table>    
</body>
</html>