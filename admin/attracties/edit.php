<?php
session_start();
require_once '../backend/config.php';
if(!isset($_SESSION['user_id']))
{
    $msg = "Je moet eerst inloggen!";
    header("Location: $base_url/admin/login.php?msg=$msg");
    exit;
}
?>

<!doctype html>
<html lang="nl">

<head>
    <title>Attractiepagina / Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;600;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/main.css">
    <link rel="icon" href="<?php echo $base_url; ?>/favicon.ico" type="image/x-icon" />
</head>

<body>

    <?php require_once '../../header.php'; ?>
    <div class="container">

        <h2>Attractie aanpassen</h2>

        <?php 
        $id = $_GET['id'];
        require_once '../backend/conn.php';
        $query = "SELECT * FROM rides WHERE id = :id";
        $statement = $conn->prepare($query);
        $statement->execute([":id" => $id]);
        $ride = $statement->fetch(PDO::FETCH_ASSOC);
        ?>

        <form action="../backend/ridesController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="old_img" value="<?php echo $ride['img_file']; ?>">

            <div class="form-group">
                <label for="title">Titel:</label>
                <input type="text" name="title" id="title" class="form-input" value="<?php echo $ride['title']; ?>">
            </div>
            <div class="form-group">
                <label for="themeland">Themagebied:</label>
                <select name="themeland" id="themeland" class="form-input">
                    <option value=""> - kies een optie - </option>
                    <option value="familyland" <?php if($ride['themeland'] == 'familyland') echo 'selected'; ?>>Familyland</option>
                    <option value="waterland" <?php if($ride['themeland'] == 'waterland') echo 'selected'; ?>>Waterland</option>
                    <option value="adventureland" <?php if($ride['themeland'] == 'adventureland') echo 'selected'; ?>>Adventureland</option>
                </select>
            </div>
            <div class="form-group">
                <label for="min_length">Min Lengte in cm:</label>
                <input type="number" name="min_length" id="min_length" value="<?php echo $ride['min_length']; ?>" class="form-input">
            </div>
            <div class="form-group">
                <label for="description">Beschijving:</label>
                <textarea name="description" id="description" cols="30" rows="10"><?php echo $ride['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="img_file">Afbeelding:</label>
                <img src="<?php echo $base_url . "/img/attracties/" . $ride['img_file']; ?>" alt="attractiefoto" style="max-width: 120px;">
                <input type="file" name="img_file" id="img_file" class="form-input">
            </div>
            <div class="form-group">
                <label for="fast_pass">FAST PASS:</label>
                <input type="checkbox" name="fast_pass" id="fast_pass" <?php if($ride['fast_pass']) echo 'checked'; ?>>
                <label for="fast_pass">Voor deze attractie is een FAST PASS nodig.</label>
            </div>

            <input type="submit" value="Attracties aanpassen">
        </form>
        <hr>
        <form action="../backend/ridesController.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" value="Verwijderen">
        </form>

    </div>

</body>

</html>
