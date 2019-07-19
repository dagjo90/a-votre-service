<?php

class User extends Model {

  public function createUser($pseudo, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($this -> connectDB) {
      try {
        $sql = 'INSERT INTO users
                (`pseudo`, `email`, `password`, `isadmin`)
                VALUES (?,?,?,?)';
        $pdoStmnt = $this-> connectDB -> prepare( $sql);
        $pdoStmnt -> execute ( [
          $pseudo,
          $email,
          $hash,
          0,
        ]);
        return true;
      } catch (\PDOException $e) {
        return $e -> getMessage();
      }
    } else {
      return false;
    }
  }

  public function getUser ($id) {
      if ( $this -> connectDB ) {
          try {
              $sql = "SELECT * FROM users where id = $id";
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

  public function getAllUsers() {
    if ( $this -> connectDB ) {
        try {
            $sql = "SELECT * FROM users";
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

  function loginUser($psw, $email){

      if(!empty($psw) AND !empty($email)){
      $req= $this->connectDB->prepare("SELECT id, password FROM users WHERE email = ?");
      $req->execute(array($email));
      $resultat= $req->fetch();

      if(!$resultat)
      {
          echo 'Mauvais identifiant ou mot de passe !';
      }
      else {
          if(password_verify( $psw, $resultat['password'])){
          session_start();
              $_SESSION['id'] = $resultat['id'];
              $_SESSION['email'] = $email;

          }
          else {
              echo 'Mauvais identifiant ou mot de passe ';
          }
      }
      }

  }

  public function updateUser($id, $pseudo, $email, $password) {

    $hash = password_hash($password, PASSWORD_DEFAULT);

      if ( $this -> connectDB ) {
          try {
              $sql = 'UPDATE users
                      SET pseudo = ?, email = ?, password = ?
                      WHERE id = ?';
              $pdoStmnt = $this -> connectDB -> prepare( $sql );
              $pdoStmnt -> execute( [
                $pseudo,
                $email,
                $hash,
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



  public function deleteUser ($id) {

    if ( $this -> connectDB ) {
        try {
            $sql = "DELETE FROM users WHERE id = $id";
            $pdoStmnt = $this -> connectDB -> prepare( $sql );
            $pdoStmnt -> execute();

        } catch ( \PDOException $e ) {
            return $e -> getMessage();

    } } else {
        return false;
    }

  }

}
?>
