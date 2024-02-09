<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "work";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Handle item deletion
if (isset($_GET["action"]) && $_GET["action"] === "supprimer" && isset($_GET["id"])) {
    $itemId = $_GET["id"];
    $deleteSql = "DELETE FROM itulisateur WHERE id = $itemId";

    if ($conn->query($deleteSql) === TRUE) {
        echo "Item supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// Handle item modification - you need to implement the modification logic here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data
    $titre = $_POST["titre"];
    $descriptionn = $_POST["descriptionn"];

    // Create the "uploads" directory if it doesn't exist
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Process the uploaded image
    $imageName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $imageName;
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);

    // Insert data into the database
    $sql = "INSERT INTO itulisateur (titre, descriptionn, imagee) VALUES ('$titre', '$descriptionn', '$targetFilePath')";

    if ($conn->query($sql) === TRUE) {
        echo "Connexion réussie";
    } else {
        echo "Erreur : " . $conn->error;
    }
}

// Fetch items from the database
$selectSql = "SELECT * FROM itulisateur";
$result = $conn->query($selectSql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des éléments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

       
        table {
            width: 80%;
            
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .thumbnail-image {
            max-width: 50px;
            height: auto;
        }

        .action-links a {
            text-decoration: none;
            margin-right: 5px;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            color: #fff;
        }

        .action-links a.Supprimer {
            background-color: #dc3545;
        }

        .action-links a.Modifier {
            background-color: #007bff;
        }
    </style>
</head>

    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["titre"] . "</td>";
            echo "<td>" . $row["descriptionn"] . "</td>";
            echo "<td><img src='" . $row["imagee"] . "' alt='Image'></td>";
            echo "<td>";
            echo "<a href='?action=supprimer&id=" . $row["id"] . "'>Supprimer</a>";
           
            // You can add a link for modifying an item, for example:
             echo " <a href='modify.php?id=" . $row["id"] . "'>Modifier</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>

</html>

<?php
$conn->close();
?>
