<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
    $configs = include('./config.php');
    $questionsDb = new SQLite3($configs['questionsDbFile']);

    $selectGamesStatement = $questionsDb->prepare('SELECT distinct(round_id) as round_id from games');
    $gamesResult = $selectGamesStatement->execute();
    while($gameRow = $gamesResult->fetchArray(SQLITE3_ASSOC)) {
        $roundId = $gameRow['round_id'];
        echo '<a href="./game_board.php?round=' . $roundId . '">Game ' . $roundId . '</a> <br />';
    }
?>
</body>
</html>
