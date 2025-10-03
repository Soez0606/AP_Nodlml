<?php
namespace Model;
use SQLite3;

class BDD
{
    private static $cheminDeLaBDD = __DIR__ . '../Data/nodlml.sql';
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
}