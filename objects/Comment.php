<?php
class Comment extends Model {

  public function createComment($article_id, $user_id, $contenu) {
    if ($this -> connectDB) {
      try {
        $sql = 'INSERT INTO comments
                (`article_id`, `user_id`, `content`)
                VALUES (?,?,?)';
        $pdoStmnt = $this-> connectDB -> prepare( $sql);
        $pdoStmnt -> execute ( [
          $article_id,
          $user_id,
          $contenu,
        ]);
        return true;
      } catch (\PDOException $e) {
        return $e -> getMessage();
      }
    } else {
      return false;
    }
  }

  public function getAllCommentsFromArticle($id) {
      if ( $this -> connectDB ) {
          try {
              $sql = "SELECT * FROM comments where article_id = $id";
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

  public function getAllCommentsFromUser($id) {
    if ( $this -> connectDB ) {
        try {
            $sql = "SELECT * FROM comments where user_id = $id";
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

  public function getComment ($id) {
      if ( $this -> connectDB ) {
          try {
              $sql = "SELECT * FROM comments where id = $id";
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

  public function deleteComment ($id, $user_id) {

    if ( $this -> connectDB ) {
        try {
            $sql = "DELETE FROM comments WHERE id = $id";
            $pdoStmnt = $this -> connectDB -> prepare( $sql );
            $pdoStmnt -> execute();

        } catch ( \PDOException $e ) {
            return $e -> getMessage();

    } } else {
        return false;
    }

  }

  public function deleteAllCommentsFromArticle ($id) {

    if ( $this -> connectDB ) {
        try {
            $sql = "DELETE FROM comments WHERE article_id = $id";
            $pdoStmnt = $this -> connectDB -> prepare( $sql );
            $pdoStmnt -> execute();

        } catch ( \PDOException $e ) {
            return $e -> getMessage();

    } } else {
        return false;
    }

  }

  public function deleteAllCommentsFromUser ($id) {

    if ( $this -> connectDB ) {
        try {
            $sql = "DELETE FROM comments WHERE user_id = $id";
            $pdoStmnt = $this -> connectDB -> prepare( $sql );
            $pdoStmnt -> execute();

        } catch ( \PDOException $e ) {
            return $e -> getMessage();

    } } else {
        return false;
    }

  }

  public function modifyComment ($id, $message) {


      if ( $this -> connectDB ) {
          try {
              $sql = 'UPDATE comments
                      SET content = ?
                      WHERE id = ?';
              $pdoStmnt = $this -> connectDB -> prepare( $sql );
              $pdoStmnt -> execute( [
                $message,
                $id
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
