<?php
$questionsDb = new SQLite3('../db/mff_2016_questions.db');

if( isset($_GET['id']) && !empty($_GET['id']) ) {

  $categoryId = htmlentities($_GET['id']);

  $categoryTitleSelectStatment = $questionsDb->prepare('SELECT title from categories where rowid = (:rowid)');
  $categoryTitleSelectStatment->bindValue('rowid', $categoryId);
  $categoryTitleResult = $categoryTitleSelectStatment->execute();
  if(!$categoryTitleResult) {
    echo "An error occurred!";
    return 0;
  }

  $categoryTitle = $categoryTitleResult->fetchArray(SQLITE3_ASSOC)['title'];

  if( isset($_POST['question-text']) && !empty($_POST['question-text'])
    && isset($_POST['answer']) && isset($_POST['value']) ) {
    if( isset($_POST['rowid']) && !empty($_POST['rowid']) ) {
      $questionUpdateStatement = $questionsDb->prepare('UPDATE questions SET question_text = (:question_text), answer = (:answer), value = (:value) WHERE rowid = (:rowid)');
      $questionUpdateStatement->bindValue('question_text', $_POST['question-text']);
      $questionUpdateStatement->bindValue('answer', $_POST['answer']);
      $questionUpdateStatement->bindValue('value', $_POST['value']);
      $questionUpdateStatement->bindValue('rowid', $_POST['rowid']);
      $questionUpdateResult = $questionUpdateStatement->execute();
      if(!$questionUpdateResult) {
        echo "An error occurred!";
        return 0;
      }
    }
    else {
      $questionInsertStatement = $questionsDb->prepare('INSERT INTO questions (question_text, answer, value, category_id) VALUES ((:question_text), (:answer), (:value), (:categoryId))');
      $questionInsertStatement->bindValue('question_text', $_POST['question-text']);
      $questionInsertStatement->bindValue('answer', $_POST['answer']);
      $questionInsertStatement->bindValue('value', $_POST['value']);
      $questionInsertStatement->bindValue('categoryId', $categoryId);
      $questionInsertResult = $questionInsertStatement->execute();
      if(!$questionInsertResult) {
        echo "An error occurred!";
        return 0;
      }
    }
  }
}
else {
  return 0;
}

?>
<html>
<head>
  <title>Editing "<?php echo $categoryTitle ?>"</title>
</head>
<body>

  <h1><?php echo $categoryTitle ?></h1>

  <a href="./">Categories Home</a>

  <table border="true">
    <tr>
      <th>Clue</th>
      <th>Answer</th>
      <th>Value</th>
      <th>Used</th>
      <th></th>
    </tr>
  <?php
    $selectQuestionsStmt = $questionsDb->prepare('SELECT rowid, question_text, answer, value, used  FROM questions WHERE category_id = (:id)');
    $selectQuestionsStmt->bindValue('id', $categoryId);
    $questionsResult = $selectQuestionsStmt->execute();
    while($questionRow = $questionsResult->fetchArray(SQLITE3_ASSOC)) {
      ?>
      <form action="./category.php?id=<?php echo $categoryId ?>" method="post">
        <input name="rowid" type="hidden" value="<?php echo $questionRow['rowid']; ?>" />
        <tr>
          <td><input name="question-text" type="text" class="form-input" size=100 value="<?php echo $questionRow['question_text']; ?>" /></td>
          <td><input name="answer" type="text" class="form-input" value="<?php echo $questionRow['answer']; ?>" /></td>
          <td><input name="value" type="text" class="form-input" size=5 value="<?php echo $questionRow['value']; ?>" /></td>
          <td><?php if($questionRow['used']) echo "X"; ?></td>
          <td><input type="submit" class="button form-submit" value="Save" /></td>
        </tr>
      </form>
      <?php
    }
  ?>

    <form id="new-question-form" action="./category.php?id=<?php echo $categoryId ?>" method="post">
      <tr>
        <td><input name="question-text" type="text" class="form-input" size=100 placeholder="New Clue" /></td>
        <td><input name="answer" type="text" class="form-input" /></td>
        <td><input name="value" type="text" class="form-input" size=5 /></td>
        <td></td>
        <td><input type="submit" class="button form-submit" value="Add" /></td>
      </tr>
    </form>

  </table>

</body>
</html>
