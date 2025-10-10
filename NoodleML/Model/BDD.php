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

    static public function supprimer($email)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $sup = $bdd->prepare('DELETE FROM utilisateur WHERE email = ?');
        $sup->bindValue(1, $email, SQLITE3_INTEGER);
        $res = $sup->execute();
        if ($res) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo $error;
            return -1;
        }
    }

    static public function reinitialisationMotDePasse($email)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $reini = $bdd->prepare('UPDATE utilisateur SET mot_de_passe = null WHERE email = :email');
        $reini->bindValue(1,$email,SQLITE3_INTEGER);
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

    static public function ajouterClasse(?string $nom=null,string $prof,string $etablissement_num,?int $chap_dispo=null)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $insert = $bdd->prepare("insert into classe (nom, prof, etablissement_num, chap_dispo) values (:nom, :prof, :etablissement_num, :chap_dispo)");
        $insert->bindValue(":nom", $nom, SQLITE3_INTEGER);
        $insert->bindValue(":prof", $prof, SQLITE3_TEXT);
        $insert->bindValue(":etablissement_num", $etablissement_num, SQLITE3_TEXT);
        $insert->bindValue(":chap_dispo", $chap_dispo, SQLITE3_TEXT);
        $result = $insert->execute();
        if ($result) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo 'erreur lors de l\'ajout de la classe. ' . $error;
            return -1;
        }
    }
    
    static public function supprimerClasse($id)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $sup = $bdd->prepare('DELETE FROM classe WHERE id = ?');
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

    static public function ajouterEtablissement(?string $nom=null)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $insert = $bdd->prepare("insert into etablissement (nom) values (:nom)");
        $insert->bindValue(":nom", $nom, SQLITE3_INTEGER);
        $result = $insert->execute();
        if ($result) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo 'erreur lors de l\'ajout de l\'etablissement. ' . $error;
            return -1;
        }
    }
    
    static public function supprimerEtablissement($num)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $sup = $bdd->prepare('DELETE FROM etablissement WHERE num = ?');
        $sup->bindValue(1, $num, SQLITE3_INTEGER);
        $res = $sup->execute();
        if ($res) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo $error;
            return -1;
        }
    }

    static public function getEleve ($email)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        
    }
}