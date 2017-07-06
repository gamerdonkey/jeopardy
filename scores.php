<?php
  $configs = include('./config.php');
  $questionsDb = new SQLite3($configs['questionsDbFile']);

  $selectScoresStmt = $questionsDb->prepare('SELECT team_name, score FROM scores');
  $scoresResult = $selectScoresStmt->execute();
  while($scoreRow = $scoresResult->fetchArray(SQLITE3_ASSOC)) {
        echo $scoreRow['team_name'];
        echo ': <span class="score">$';
        echo $scoreRow['score'];
        echo '</span> ';
  }
?>
