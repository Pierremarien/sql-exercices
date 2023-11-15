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

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['clientForm'])) {
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $birthDate = $_POST['birthDate'];
        $card = isset($_POST['card']) ? 1 : 0;
        $cardNumber = ($card == 1) ? $_POST['cardNumber'] : ($_POST['card'] == 0 ? null : null);
        $cardTypeId = isset($_POST['cardTypeId']) ? $_POST['cardTypeId'] : null;

        $insertCardQuery = "INSERT INTO cards (cardNumber, cardTypesId) 
        VALUES (:cardNumber, :cardTypeId)";
        $stmtCard = $bdd->prepare($insertCardQuery);
        $stmtCard->bindParam(':cardNumber', $cardNumber, PDO::PARAM_INT);
        $stmtCard->bindParam(':cardTypeId', $cardTypeId, PDO::PARAM_INT);
        $stmtCard->execute();

        $insertQuery = "INSERT INTO clients (lastName, firstName, birthDate, card, cardNumber) 
        VALUES (:lastName, :firstName, :birthDate, :card, :cardNumber)";
        $stmt = $bdd->prepare($insertQuery);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':birthDate', $birthDate);
        $stmt->bindParam(':card', $card);
        $stmt->bindParam(':cardNumber', $cardNumber, PDO::PARAM_INT);
        $stmt->execute();
        
        header("Location: index.php");
        exit();    
    } elseif (isset($_POST['showForm'])) {
        $title = $_POST['title'];
        $performer = $_POST['performer'];
        $date = $_POST['date'];
        $showTypesId = $_POST['showTypesId'];
        $firstGenresId = $_POST['firstGenresId'];
        $secondGenreId = $_POST['secondGenreId'];
        $duration = $_POST['duration'];
        $startTime = $_POST['startTime'];

        $insertShowQuery = "INSERT INTO shows (title, performer, date, showTypesId, firstGenresId, secondGenreId, duration, startTime) 
                            VALUES (:title, :performer, :date, :showTypesId, :firstGenresId, :secondGenreId, :duration, :startTime)";
        $stmtShow = $bdd->prepare($insertShowQuery);
        $stmtShow->bindParam(':title', $title);
        $stmtShow->bindParam(':performer', $performer);
        $stmtShow->bindParam(':date', $date);
        $stmtShow->bindParam(':showTypesId', $showTypesId, PDO::PARAM_INT);
        $stmtShow->bindParam(':firstGenresId', $firstGenresId, PDO::PARAM_INT);
        $stmtShow->bindParam(':secondGenreId', $secondGenreId, PDO::PARAM_INT);
        $stmtShow->bindParam(':duration', $duration);
        $stmtShow->bindParam(':startTime', $startTime);
        $stmtShow->execute();
        header("Location: index.php");
        exit();    
    } elseif (isset($_POST['selectClient']) && isset($_POST['clientId'])) {
        $selectedClientId = $_POST['clientId'];
        $selectClientDataQuery = "SELECT * FROM clients WHERE id = :clientId";
        $stmtClientData = $bdd->prepare($selectClientDataQuery);
        $stmtClientData->bindParam(':clientId', $selectedClientId, PDO::PARAM_INT);
        $stmtClientData->execute();
        $selectedClientData = $stmtClientData->fetch(PDO::FETCH_ASSOC);
    } elseif (isset($_POST['updateClient'])) {
        $clientId = $_POST['clientId'];
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $birthDate = $_POST['birthDate'];
        $card = isset($_POST['card']) ? 1 : 0;
        $cardNumber = ($card == 1) ? $_POST['cardNumber'] : null;

        $updateClientQuery = "UPDATE clients 
                              SET lastName = :lastName, firstName = :firstName, birthDate = :birthDate, 
                                  card = :card, cardNumber = :cardNumber
                              WHERE id = :clientId";
        $stmtUpdateClient = $bdd->prepare($updateClientQuery);
        $stmtUpdateClient->bindParam(':lastName', $lastName);
        $stmtUpdateClient->bindParam(':firstName', $firstName);
        $stmtUpdateClient->bindParam(':birthDate', $birthDate);
        $stmtUpdateClient->bindParam(':card', $card);
        $stmtUpdateClient->bindParam(':cardNumber', $cardNumber, PDO::PARAM_INT);
        $stmtUpdateClient->bindParam(':clientId', $clientId, PDO::PARAM_INT);
        $stmtUpdateClient->execute();

        header("Location: index.php");
        exit();    
    } elseif (isset($_POST['deleteClient'])) {
        $deleteClientId = $_POST['clientId'];
        $deleteClientQuery = "DELETE FROM clients WHERE id = :clientId";
        $stmtDeleteClient = $bdd->prepare($deleteClientQuery);
        $stmtDeleteClient->bindParam(':clientId', $deleteClientId, PDO::PARAM_INT);
        $stmtDeleteClient->execute();
        
        header("Location: index.php");
        exit();   
    } elseif (isset($_POST['selectShow']) && isset($_POST['showId'])) {
        $selectedShowId = $_POST['showId'];
        $selectShowDataQuery = "SELECT * FROM shows WHERE id = :showId";
        $stmtShowData = $bdd->prepare($selectShowDataQuery);
        $stmtShowData->bindParam(':showId', $selectedShowId, PDO::PARAM_INT);
        $stmtShowData->execute();
        $selectedShowData = $stmtShowData->fetch(PDO::FETCH_ASSOC);
    } elseif (isset($_POST['updateShow'])) {
        $showId = $_POST['showId'];
        $title = $_POST['title'];
        $performer = $_POST['performer'];
        $date = $_POST['date'];
        $showTypesId = $_POST['showTypesId'];
        $firstGenresId = $_POST['firstGenresId'];
        $secondGenreId = $_POST['secondGenreId'];
        $duration = $_POST['duration'];
        $startTime = $_POST['startTime'];

        $updateShowQuery = "UPDATE shows 
                            SET title = :title, performer = :performer, date = :date, 
                                showTypesId = :showTypesId, firstGenresId = :firstGenresId, 
                                secondGenreId = :secondGenreId, duration = :duration, 
                                startTime = :startTime
                            WHERE id = :showId";
        $stmtUpdateShow = $bdd->prepare($updateShowQuery);
        $stmtUpdateShow->bindParam(':title', $title);
        $stmtUpdateShow->bindParam(':performer', $performer);
        $stmtUpdateShow->bindParam(':date', $date);
        $stmtUpdateShow->bindParam(':showTypesId', $showTypesId, PDO::PARAM_INT);
        $stmtUpdateShow->bindParam(':firstGenresId', $firstGenresId, PDO::PARAM_INT);
        $stmtUpdateShow->bindParam(':secondGenreId', $secondGenreId, PDO::PARAM_INT);
        $stmtUpdateShow->bindParam(':duration', $duration);
        $stmtUpdateShow->bindParam(':startTime', $startTime);
        $stmtUpdateShow->bindParam(':showId', $showId, PDO::PARAM_INT);
        $stmtUpdateShow->execute();

        header("Location: index.php");
        exit();    
    } elseif (isset($_POST['deleteShow'])) {
        $deleteShowId = $_POST['showId'];
        $deleteShowQuery = "DELETE FROM shows WHERE id = :showId";
        $stmtDeleteShow = $bdd->prepare($deleteShowQuery);
        $stmtDeleteShow->bindParam(':showId', $deleteShowId, PDO::PARAM_INT);
        $stmtDeleteShow->execute();
        
        header("Location: index.php");
        exit();    
    }
}

} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
$selectClientsQuery = "SELECT id, lastName, firstName FROM clients";
$stmtClients = $bdd->query($selectClientsQuery);
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

$selectShowsQuery = "SELECT id, title FROM shows";
$stmtShows = $bdd->query($selectShowsQuery);
$shows = $stmtShows->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Add Client</h2>

<form method="POST" action="">
    <label for="lastName">Nom :</label>
    <input type="text" name="lastName" required><br>

    <label for="firstName">Prenom :</label>
    <input type="text" name="firstName" required><br>

    <label for="birthDate">Date de naissance:</label>
    <input type="date" name="birthDate" required><br>

    <label for="card">Carte de fidelite:</label>
    <input type="checkbox" name="card" id="cardCheckbox" value="1"><br>

    <label for="cardNumber">Numero de carte:</label>
    <input type="text" name="cardNumber" id="cardNumberInput" <?php echo isset($_POST['card']) && $_POST['card'] == '1' ? '' : 'readonly'; ?>><br>

    <label for="cardTypeId">Type de Carte:</label>
    <input type="number" name="cardTypeId" id="cardTypeIdInput" <?php echo isset($_POST['card']) && $_POST['card'] == '1' ? '' : 'readonly'; ?>><br>

    <script>
        document.getElementById('cardCheckbox').addEventListener('change', function() {
            document.getElementById('cardNumberInput').readOnly = !this.checked;
            document.getElementById('cardTypeIdInput').readOnly = !this.checked;
        
        });
    </script>

    <input type="hidden" name="clientForm" value="1">

    <input type="submit" value="Add Client">
</form>

<h2>Update Client</h2>

    <form method="POST" action="">
        <label for="clientId">Choose a client:</label>
        <select name="clientId">
            <?php foreach ($clients as $client): ?>
                <option value="<?php echo $client['id']; ?>"><?php echo $client['lastName'] . ', ' . $client['firstName']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="selectClient" value="Select Client">
    </form>

    <?php if (isset($selectedClientData)): ?>
        <form method="POST" action="">
            <input type="hidden" name="updateClient" value="1">
            <input type="hidden" name="clientId" value="<?php echo $selectedClientData['id']; ?>">

            <label for="lastName">Nom :</label>
            <input type="text" name="lastName" value="<?php echo $selectedClientData['lastName']; ?>" required><br>

            <label for="firstName">Prenom :</label>
            <input type="text" name="firstName" value="<?php echo $selectedClientData['firstName']; ?>" required><br>

            <label for="birthDate">Date de naissance:</label>
            <input type="date" name="birthDate" value="<?php echo $selectedClientData['birthDate']; ?>" required><br>

            <label for="card">Carte de fidelite:</label>
            <input type="checkbox" name="card" id="cardCheckbox" value="1" <?php echo $selectedClientData['card'] == 1 ? 'checked' : ''; ?>><br>

            <label for="cardNumber">Numero de carte:</label>
            <input type="text" name="cardNumber" id="cardNumberInput" value="<?php echo $selectedClientData['cardNumber']; ?>" <?php echo $selectedClientData['card'] == 1 ? '' : 'readonly'; ?>><br>

            <script>
                document.getElementById('cardCheckbox').addEventListener('change', function() {
                    document.getElementById('cardNumberInput').readOnly = !this.checked;
                });
            </script>

            <input type="submit" value="Update Client">
        </form>

        <form method="POST" action="">
            <input type="hidden" name="deleteClient" value="1">
            <input type="hidden" name="clientId" value="<?php echo $selectedClientData['id']; ?>">
            <input type="submit" value="Delete Client" onclick="return confirm('Are you sure you want to delete this client?')">
        </form>
    <?php endif; ?>

<h2>Add Show</h2>

    <form method="POST" action="">
        <label for="title">Title:</label>
        <input type="text" name="title" required><br>

        <label for="performer">Performer:</label>
        <input type="text" name="performer" required><br>

        <label for="date">Date:</label>
        <input type="date" name="date" required><br>

        <label for="showTypesId">Show Type ID:</label>
        <input type="number" name="showTypesId" required><br>

        <label for="firstGenresId">First Genre ID:</label>
        <input type="number" name="firstGenresId" required><br>

        <label for="secondGenreId">Second Genre ID:</label>
        <input type="number" name="secondGenreId" required><br>

        <label for="duration">Duration:</label>
        <input type="time" name="duration" required><br>

        <label for="startTime">Start Time:</label>
        <input type="time" name="startTime" required><br>

        <input type="hidden" name="showForm" value="1">
        <input type="submit" value="Add Show">
    </form>

    <h2>Update Show</h2>

<form method="POST" action="">
    <label for="showId">Choose a show:</label>
    <select name="showId">
        <?php foreach ($shows as $show): ?>
            <option value="<?php echo $show['id']; ?>"><?php echo $show['title']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="selectShow" value="Select Show">
</form>

<?php if (isset($selectedShowData)): ?>
    <form method="POST" action="">
        <input type="hidden" name="updateShow" value="1">
        <input type="hidden" name="showId" value="<?php echo $selectedShowData['id']; ?>">

        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo $selectedShowData['title']; ?>" required><br>

        <label for="performer">Performer:</label>
        <input type="text" name="performer" value="<?php echo $selectedShowData['performer']; ?>" required><br>

        <label for="date">Date:</label>
        <input type="date" name="date" value="<?php echo $selectedShowData['date']; ?>" required><br>

        <label for="showTypesId">Show Type:</label>
        <input type="number" name="showTypesId" value="<?php echo $selectedShowData['showTypesId']; ?>" required><br>

        <label for="firstGenresId">First Genre:</label>
        <input type="number" name="firstGenresId" value="<?php echo $selectedShowData['firstGenresId']; ?>" required><br>

        <label for="secondGenreId">Second Genre:</label>
        <input type="number" name="secondGenreId" value="<?php echo $selectedShowData['secondGenreId']; ?>" required><br>

        <label for="duration">Duration:</label>
        <input type="time" name="duration" value="<?php echo $selectedShowData['duration']; ?>" required><br>

        <label for="startTime">Start Time:</label>
        <input type="time" name="startTime" value="<?php echo $selectedShowData['startTime']; ?>" required><br>

        <input type="submit" value="Update Show">
        </form>
        
        <form method="POST" action="">
            <input type="hidden" name="deleteShow" value="1">
            <input type="hidden" name="showId" value="<?php echo $selectedShowData['id']; ?>">
            <input type="submit" value="Delete Show" onclick="return confirm('Are you sure you want to delete this show?')">
        </form>
    <?php endif; ?>

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