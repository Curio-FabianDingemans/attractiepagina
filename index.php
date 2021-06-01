<?php
session_start();
require_once 'admin/backend/config.php';
?>

<!doctype html>
<html lang="nl">

<head>
    <title>Attractiepagina</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;600;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/main.css">
    <link rel="icon" href="<?php echo $base_url; ?>/favicon.ico" type="image/x-icon" />
</head>

<body>

    <?php require_once 'header.php'; ?>
    <div class="container content">
        <aside>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia modi dolore magnam! Iste libero voluptatum autem, sapiente ullam earum nostrum sed magnam vel laboriosam quibusdam, officia, esse vitae dignissimos nulla?
        </aside>
        <main>
            <?php
                require_once 'admin/backend/conn.php';
                $query = "SELECT * FROM rides";
                $statement = $conn->prepare($query);
                $statement->execute();
                $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="atracties">
                <?php foreach($rides as $ride): ?>
                    <div class="atractie <?php if($ride['fast_pass'] == "1")echo "wide"; ?>">
                        <img src="./img/attracties/<?php echo $ride['img_file']; ?>" alt="Atractie foto">
                        <div class="container">
                            <div class="left">
                                <p class="theme"><?php echo ucfirst($ride['themeland']); ?> <?php if($ride['min_length'] != null){echo "- Lengte: " . ucfirst($ride['min_length']."m");} ?></p>
                                <h2 class="title"><?php echo $ride['title']; ?></h2>
                                <p class="desc"><?php echo $ride['description']; ?></p>
                            </div>
                            <?php if($ride['fast_pass'] == "1"): ?>
                            <div class="right">
                                <button>Fast Pass</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

</body>

</html>
