<?php
  $configs = include('./config.php');
  $questionsDb = new SQLite3($configs['questionsDbFile']);

  if( isset($_POST['rowid']) && !empty($_POST['rowid']) ) {

     $rowid = htmlentities($_POST['rowid']);
     $teamName = htmlentities($_POST['team_name']);
     $score = htmlentities($_POST['score']);

     $preparedStatement = $questionsDb->prepare('UPDATE scores SET team_name = :team_name, score = :score where rowid = :rowid');
     $preparedStatement->bindValue('rowid', $rowid);
     $preparedStatement->bindValue('team_name', $teamName);
     $preparedStatement->bindValue('score', $score);
     $result = $preparedStatement->execute();
     if(!$result) {
       echo "An error occurred!";
     }
  }
  else if( isset($_GET['rowid']) && !empty($_GET['rowid']) ) {
     $rowid = htmlentities($_GET['rowid']);
     $scoreChange = htmlentities($_GET['value']);

     $preparedStatement = $questionsDb->prepare('UPDATE scores SET score = score + :scoreChange where rowid = :rowid');
     $preparedStatement->bindValue('rowid', $rowid);
     $preparedStatement->bindValue('scoreChange', $scoreChange);
     $result = $preparedStatement->execute();
     if(!$result) {
        echo "An error occurred!";
     }
  }
?>

<html>
<body>

  <table border="true">
    <tr>
      <th>Team</th>
      <th>Score</th>
      <th></th>
    </tr>
<?php
  $selectScoresStmt = $questionsDb->prepare('SELECT rowid, team_name, score FROM scores');
  $scoresResult = $selectScoresStmt->execute();
  while($scoreRow = $scoresResult->fetchArray(SQLITE3_ASSOC)) {
    ?>
    <form action="./scorekeeper.php" method="post">
      <input name="rowid" type="hidden" value="<?php echo $scoreRow['rowid']; ?>" />
      <tr>
        <td>
            <input name="team_name" type="text" class="form-input" size=30 value="<?php echo $scoreRow['team_name']; ?>" />
        </td>
        <td>
            <table align="center" border="true" cellpadding="20"> 
                <tr>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=200">+200</a></td>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=400">+400</a></td>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=600">+600</a></td>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=800">+800</a></td>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=1000">+1000</a></td>
                </tr>
                <tr>
                    <td colspan=5>
                        <input name="score" type="text" class="form-input" value="<?php echo $scoreRow['score']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=-200">-200</a></td>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=-400">-400</a></td>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=-600">-600</a></td>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=-800">-800</a></td>
                    <td><a href="./scorekeeper.php?rowid=<?php echo $scoreRow['rowid']; ?>&value=-1000">-1000</a></td>
                </tr>
            </table>
        </td>
        <td><input type="submit" class="button form-submit" value="Save" /></td>
      </tr>
    </form>
    <?php
  }
?>
  </table>
</body>
</html>
