<?php
session_start();
require_once 'admin/backend/config.php';

$themeland = null;
$minlength = null;
$fastpass = "";

if(isset($_GET['themeland'])){
    $themeland = $_GET['themeland'];
}
if(isset($_GET['minlength'])){
    $minlength = $_GET['minlength'];
    if($minlength < 0){
        $minlength = 0;
    }
    if($minlength > 300){
        $minlength = 300;
    }
}
if(isset($_GET['fastpass'])){
    if($_GET['fastpass'] == "0"){
        $fastpass = 0;
    }else if($_GET['fastpass'] == "1"){
        $fastpass = 1;
    }
}

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
            <form action="./" method="get">
                <div class="attractieSort">
                    <label for="themeland">Themeland:</label>
                    <select name="themeland" id="themeland">
                        <option value="" <?php echo $themeland==null ? "selected" : "" ?>> - Sorteer op themaland - </option>
                        <option value="familyland" <?php echo $themeland=="familyland" ? "selected" : "" ?>>Familyland</option>
                        <option value="waterland" <?php echo $themeland=="waterland" ? "selected" : "" ?>>Waterland</option>
                        <option value="adventureland" <?php echo $themeland=="adventureland" ? "selected" : "" ?>>Adventureland</option>
                    </select>
                </div>
                <div class="attractieSort">
                    <label for="minlength">Minimale Lengte:</label>
                    <input type="number" name="minlength" id="minlength" value="<?php echo $minlength ?>" placeholder="Sorteer op minimale lengte">
                </div>
                <div class="attractieSort">
                    <label for="fastpass">Fastpass:</label>
                    <select name="fastpass" id="fastpass">
                        <option value="" <?php echo $fastpass=="" ? "selected" : "" ?>> - Sorteer op fastpass - </option>
                        <option value="1" <?php echo $fastpass===1 ? "selected" : "" ?>>Ja</option>
                        <option value="0" <?php echo $fastpass===0 ? "selected" : "" ?>>Nee</option>
                    </select>
                </div>
                <div class="attractieSort">
                    <button type="submit">Filter toepassen</button>
                </div>
            </form>
        </aside>
        <main>
            <?php
                require_once 'admin/backend/conn.php';
                
                if(!empty($themeland) && ($fastpass === 0 || $fastpass === 1)){
                    $query = "SELECT * FROM rides WHERE `themeland`=:themeland And `fast_pass`=:fast_pass;";
                    $statement = $conn->prepare($query);
                    $statement->execute([
                        "themeland" => $themeland,
                        "fast_pass" => $fastpass
                    ]);
                }else if(!empty($themeland) && !empty($minlength)){
                    $query = "SELECT * FROM rides WHERE `themeland`=:themeland And `min_length`>=:min_length;";
                    $statement = $conn->prepare($query);
                    $statement->execute([
                        "themeland" => $themeland,
                        "min_length" => $minlength
                    ]);
                }else if(!empty($minlength) && ($fastpass === 0 || $fastpass === 1)){
                    $query = "SELECT * FROM rides WHERE `min_length`>=:min_length And `fast_pass`=:fast_pass;";
                    $statement = $conn->prepare($query);
                    $statement->execute([
                        "min_length" => $minlength,
                        "fast_pass" => $fastpass
                    ]);
                }else if(!empty($themeland) && !empty($minlength) && ($fastpass === 0 || $fastpass === 1)){
                    $query = "SELECT * FROM rides WHERE `themeland`=:themeland And `min_length`>=:min_length And `fast_pass`=:fast_pass;";
                    $statement = $conn->prepare($query);
                    $statement->execute([
                        "themeland" => $themeland,
                        "min_length" => $minlength,
                        "fast_pass" => $fastpass
                    ]);
                }else if(!empty($themeland)){
                    $query = "SELECT * FROM rides WHERE `themeland`=:themeland;";
                    $statement = $conn->prepare($query);
                    $statement->execute([
                        "themeland" => $themeland
                    ]);
                }else if(!empty($minlength)){
                    $query = "SELECT * FROM rides WHERE `min_length`>=:min_length;";
                    $statement = $conn->prepare($query);
                    $statement->execute([
                        "min_length" => $minlength
                    ]);
                }else if($fastpass == 0 || $fastpass == 1){
                    $query = "SELECT * FROM rides WHERE `fast_pass`=:fast_pass;";
                    $statement = $conn->prepare($query);
                    $statement->execute([
                        "fast_pass" => $fastpass
                    ]);
                }else{
                    $query = "SELECT * FROM rides";
                    $statement = $conn->prepare($query);
                    $statement->execute();
                }
                
                $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="atracties">
                <?php foreach($rides as $ride): ?>
                    <div class="atractie <?php if($ride['fast_pass'] == "1")echo "wide"; ?>">
                        <img class="mainimg" src="./img/attracties/<?php echo $ride['img_file']; ?>" alt="Atractie foto">
                        <div class="container">
                            <div class="left <?php if($ride['fast_pass'] == "0")echo "fullwidth"; ?>">
                                <p class="theme"><?php echo ucfirst($ride['themeland']); ?></p>
                                <h2 class="title"><?php echo $ride['title']; ?></h2>
                                <p class="desc"><?php echo $ride['description']; ?></p>
                                <p class="minheight"><?php if($ride['min_length'] != null){echo "<strong>".ucfirst($ride['min_length']."cm</strong> minimale lengte");}else{echo "<strong>Geen</strong> minimale lengte";} ?></p>
                            </div>
                            <?php if($ride['fast_pass'] == "1"): ?>
                            <div class="right">
                                <p class="fastpass-desc">Deze atractie is alleen te bezoeken met een fastpass.</p>
                                <div>
                                    <p>Boek nu en sla de wachtrij over.</p>
                                    <button><img src="./img/Ticket.png" alt="TicketIcon"> FAST PASS</button>
                                </div>
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
