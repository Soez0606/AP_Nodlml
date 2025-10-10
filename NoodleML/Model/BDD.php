<?php
namespace Model;
use SQLite3;

class BDD
{
    private static $cheminDeLaBDD = '../Data/nodlml.db';
    static public function login($email, $mot_de_passe)
    {

        $db = new SQLite3(BDD::$cheminDeLaBDD);
        $stmt = $db->prepare("SELECT * FROM utilisateur WHERE email = :email AND mot_de_passe = :mot_de_passe");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':mot_de_passe', $mot_de_passe, SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);
        return $user;

    }

    static public function ajouter(string $email,?string $nom=null,?string $prenom=null,?int $classe_id=null,string $role='eleve')
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $insert = $bdd->prepare("insert into utilisateur (email, nom, prenom, classe_id, role) values (:email, :nom, :prenom, :classe_id, :role)");
        $insert->bindValue(":email", $email, SQLITE3_INTEGER);
        $insert->bindValue(":nom", $nom, SQLITE3_TEXT);
        $insert->bindValue(":prenom", $prenom, SQLITE3_TEXT);
        $insert->bindValue(":classe_id", $classe_id, SQLITE3_TEXT);
        $insert->bindValue(":role", $role, SQLITE3_TEXT);
        $result = $insert->execute();
        if ($result) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo 'erreur lors de l\'ajout de l\'utilisateur. ' . $error;
            return -1;
        }
    }

    static public function supprimer($id)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $sup = $bdd->prepare('DELETE FROM utilisateur WHERE id = ?');
        $sup->bindValue(1, $id, SQLITE3_INTEGER);
        $res = $sup->execute();
        if ($res) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo $error;
            return -1;
        }
    }

    static public function reinitialisationMotDePasse($id)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $reini = $bdd->prepare('UPDATE utilisateur SET mot_de_passe = :mot_de_passe WHERE id = :id');
        $reini->bindValue(1,$id,SQLITE3_INTEGER);
        $res = $reini->execute();
        if ($reini)
        {
            return 1;
        }
        else {
            $error = $bdd->lastErrorMsg();
            echo 'erreur lors de l\'ajout de l\'utilisateur. ' . $error;
            return -1;
        }
    }

    /*static public function ajouterClasse(string $email,?string $nom=null,?string $prenom=null,?int $classe_id=null,string $role='eleve')
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $insert = $bdd->prepare("insert into utilisateur (email, nom, prenom, classe_id, role) values (:email, :nom, :prenom, :classe_id, :role)");
        $insert->bindValue(":email", $email, SQLITE3_INTEGER);
        $insert->bindValue(":nom", $nom, SQLITE3_TEXT);
        $insert->bindValue(":prenom", $prenom, SQLITE3_TEXT);
        $insert->bindValue(":classe_id", $classe_id, SQLITE3_TEXT);
        $insert->bindValue(":role", $role, SQLITE3_TEXT);
        $result = $insert->execute();
        if ($result) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo 'erreur lors de l\'ajout de l\'utilisateur. ' . $error;
            return -1;
        }
    }*/
    
}