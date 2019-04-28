<?php

class Article extends Model {

  public function createArticle($photo1, $titre, $tags, $accroche, $texte1, $photo2, $photo3, $texte2) {
    if ($this -> connectDB) {
      try {
        $sql = 'INSERT INTO articles
                (`photo1`, `titre`, `date`, `tags`, `accroche`, `texte1`, `photo2`, `photo3`, `texte2`, `signature`)
                VALUES (?,?,?,?,?,?,?,?,?,?)';
        $pdoStmnt = $this-> connectDB -> prepare( $sql);
        $pdoStmnt -> execute ( [
          $photo1,
          $titre,
          date("Y-m-d"),
          $tags,
          $accroche,
          $texte1,
          $photo2,
          $photo3,
          $texte2,
          "Ann-so.",
        ]);
        return true;
      } catch (\PDOException $e) {
        return $e -> getMessage();
      }
    } else {
      return false;
    }
  }


  public function getAllArticles () {
      if ( $this -> connectDB ) {
          try {
              $sql = 'SELECT * FROM articles';
              $pdoStmnt = $this -> connectDB -> prepare( $sql );
              $pdoStmnt -> execute();
              return $pdoStmnt -> fetchAll();
              $result = $pdoStmnt->fetchAll();
              print_r($result);
          } catch ( \PDOException $e ) {
              return $e -> getMessage();

      } } else {
          return false;
      }
  }

  public function getArticle ($id) {
      if ( $this -> connectDB ) {
          try {
              $sql = "SELECT * FROM articles where id = $id";
              $pdoStmnt = $this -> connectDB -> prepare( $sql );
              $pdoStmnt -> execute();
              return $pdoStmnt -> fetch();
              $result = $pdoStmnt->fetch();
              print_r($result);
          } catch ( \PDOException $e ) {
              return $e -> getMessage();

      } } else {
          return false;
      }
  }

  public function deleteArticle ($id) {
    if ( $this -> connectDB ) {
        try {
            $sql = "DELETE FROM articles WHERE id = $id";
            $pdoStmnt = $this -> connectDB -> prepare( $sql );
            $pdoStmnt -> execute();

        } catch ( \PDOException $e ) {
            return $e -> getMessage();

    } } else {
        return false;
    }

  }




  public function updateArticle ($id, $photo1, $titre, $tags, $accroche, $texte1, $photo2, $photo3, $texte2) {


      if ( $this -> connectDB ) {
          try {
              $sql = 'UPDATE articles
                      SET photo1 = ?, titre = ?, date = ?, tags = ?, accroche = ?,  texte1 = ?,  photo2 = ?,  photo3 = ?,  texte2 = ?
                      WHERE id = ?';
              $pdoStmnt = $this -> connectDB -> prepare( $sql );
              $pdoStmnt -> execute( [
                $photo1,
                $titre,
                date("Y-m-d"),
                $tags,
                $accroche,
                $texte1,
                $photo2,
                $photo3,
                $texte2,
                $id,
               ] );
              return true;
          } catch ( \PDOException $e ) {
              return $e -> getMessage();
          }
      } else {
          return false;
      }
  }



}


?>
