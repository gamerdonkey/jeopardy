<?php
$configs = include('../config.php');
$questionsDb = new SQLite3($configs['questionsDbFile']);

if( isset($_POST['category-title']) && !empty($_POST['category-title']) ) {

    $title = htmlentities($_POST['category-title']);
    $adult = isset($_POST['category-adult']);

   $preparedStatement = $questionsDb->prepare('INSERT INTO categories (title, adult) VALUES (:title, :adult)');
   $preparedStatement->bindValue('title', $title);
   $preparedStatement->bindValue('adult', $adult);
   $result = $preparedStatement->execute();
   if(!$result) {
        echo "An error occurred!";
   }
}

?>
<html>
<head>
  <title>Leopardy! Catergories</title>
</head>
<body>

  <h1>Leopardy! Categories</h1>

  <table border="true">
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>AD</th>
      <th>Unused Questions</th>
    </tr>
  <?php
    $selectCategoriesStmt = $questionsDb->prepare('SELECT rowid, title, adult FROM categories');
    $categoriesResult = $selectCategoriesStmt->execute();
    while($categoryRow = $categoriesResult->fetchArray(SQLITE3_ASSOC)) {
      $unusedQuestionCountStatement = $questionsDb->prepare('SELECT count(*) as unused_question_count from questions where category_id = (:category_id) AND (used != 1 OR used is null)');
      $unusedQuestionCountStatement->bindValue('category_id', $categoryRow['rowid']);
      $unusedQuestionCountResult = $unusedQuestionCountStatement->execute();

      $unusedQuestionCount = $unusedQuestionCountResult->fetchArray(SQLITE3_ASSOC)['unused_question_count'];
      ?>
      <tr>
        <td><?php echo $categoryRow['rowid']; ?></td>
        <td><a href="./category.php?id=<?php echo $categoryRow['rowid']; ?>"><?php echo $categoryRow['title']; ?></a></td>
        <td><?php if($categoryRow['adult']) echo "X"; ?></td>
        <td><?php echo $unusedQuestionCount; ?></td>
      </tr>
      <?php
    }
  ?>
    <tr>
      <form id="new-category-form" action="./index.php" method="post">
      <td></td>
      <td><input required name="category-title" type="text" class="form-input" size=60 placeholder="New Category" /></td>
      <td><input name="category-adult" type="checkbox" /></td>
      <td><input type="submit" class="button form-submit" value="Add" /></td>
    </form>
    </tr>
  </table>

</body>
</html>
