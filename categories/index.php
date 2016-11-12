<?php
$questionsDb = new SQLite3('../db/mff_2016_questions.db');

if( isset($_POST['category-title']) && !empty($_POST['category-title']) ) {

   $title = htmlentities($_POST['category-title']);

   $preparedStatement = $questionsDb->prepare('INSERT INTO categories (title) VALUES (:title)');
   $preparedStatement->bindValue('title', $title);
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
      <th>Round</th>
    </tr>
  <?php
    $selectCategoriesStmt = $questionsDb->prepare('SELECT rowid, title, round FROM categories');
    $categoriesResult = $selectCategoriesStmt->execute();
    while($categoryRow = $categoriesResult->fetchArray(SQLITE3_ASSOC)) {
      ?>
      <tr>
        <td><?php echo $categoryRow['rowid']; ?></td>
        <td><a href="./category.php?id=<?php echo $categoryRow['rowid']; ?>"><?php echo $categoryRow['title']; ?></a></td>
        <td><?php echo $categoryRow['round']; ?></td>
      </tr>
      <?php
    }
  ?>
    <tr>
      <form id="new-category-form" action="./questions.php" method="post">
      <td></td>
      <td><input required name="category-title" type="text" class="form-input" size=60 placeholder="New Category" /></td>
      <td><input type="submit" class="button form-submit" value="Add" /></td>
    </form>
    </tr>
  </table>

</body>
</html>
