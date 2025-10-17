<?php
namespace Model;
use SQLite3;

// require la classe Eleve (même namespace)
require_once __DIR__ . '/Eleve.php';

class BDD
{
    private static $cheminDeLaBDD = '/home/lburban/Documents/ap_nodlml/NoodleML/Data/nodlml.db';
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

    static public function ajouter(string $email, ?string $nom = null, ?string $prenom = null, ?int $classe_id = null, string $role = 'eleve')
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
        $reini->bindValue(1, $email, SQLITE3_INTEGER);
        $res = $reini->execute();
        if ($reini) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo 'erreur lors de l\'ajout de l\'utilisateur. ' . $error;
            return -1;
        }
    }

    static public function ajouterClasse(?string $nom = null, string $prof, string $etablissement_num, ?int $chap_dispo = null)
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

    static public function ajouterEtablissement(?string $nom = null)
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

    static public function getEleve($email)
    {
        $eleves = [];
        $db = new SQLite3(BDD::$cheminDeLaBDD);
        $stmt = $db->prepare("SELECT eleve.email as email, classe.nom as classe_nom, etablissement.num as etab_num
                                FROM utilisateur as eleve 
                                INNER JOIN classe 
                                    ON eleve.classe_id = classe.id 
                                INNER JOIN etablissement
                                    ON classe.etablissement_num = etablissement.num
                                INNER JOIN utilisateur as prof
                                    ON classe.prof = prof.email
                                WHERE prof.email = :email");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $etab_num = $row['etab_num'];
                $classe_nom = $row['classe_nom'];
                $eleve = new Eleve($row['email']);
                if (!isset($eleves[$etab_num])) {
                    $eleves[$etab_num] = [];
                }
                if (!isset($eleves[$etab_num][$classe_nom])) {
                    $eleves[$etab_num][$classe_nom] = [];
                }
                $eleves[$etab_num][$classe_nom][] = $eleve;
            }
        }
        return $eleves;
    }

    static public function getProf($email)
    {
        $profs = [];
        $db = new SQLite3(BDD::$cheminDeLaBDD);
        $stmt = $db->prepare("SELECT prof.email
                                        from utilisateur as prof
                                        where role = 'prof'");
        $result = $stmt->execute();
        $result = $result->fetchArray(SQLITE3_ASSOC);
        if ($result) {
            $profs[] = $result['email'];
        }
        return $profs;
    }

    static public function getChap_dispo($id)
    {
        $db = new SQLite3(BDD::$cheminDeLaBDD);
        $stmt = $db->prepare("SELECT chap_dispo from classe where id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $result = $result->fetchArray(SQLITE3_ASSOC);
        if ($result) {
            return $result['chap_dispo'];
        } else {
            return null;
        }
    }

    static public function setChapDispo(int $id, ?int $chap_dispo)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $upd = $bdd->prepare('UPDATE classe SET chap_dispo = :chap_dispo WHERE id = :id');
        $upd->bindValue(':chap_dispo', $chap_dispo, SQLITE3_INTEGER);
        $upd->bindValue(':id', $id, SQLITE3_INTEGER);
        $res = $upd->execute();
        return $res ? 1 : -1;
    }

        // Bloque un chapitre (ajoute le numéro dans chap_locked JSON)
    static public function bloquerChapitre(int $classe_id, int $chap_num)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $stmt = $bdd->prepare('SELECT chap_locked FROM classe WHERE id = :id');
        $stmt->bindValue(':id', $classe_id, SQLITE3_INTEGER);
        $r = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        $locked = json_decode($r['chap_locked'] ?? '[]', true);
        if (!in_array($chap_num, $locked, true)) {
            $locked[] = $chap_num;
            $upd = $bdd->prepare('UPDATE classe SET chap_locked = :locked WHERE id = :id');
            $upd->bindValue(':locked', json_encode(array_values($locked)), SQLITE3_TEXT);
            $upd->bindValue(':id', $classe_id, SQLITE3_INTEGER);
            $res = $upd->execute();
            return $res ? 1 : -1;
        }
        return 1;
    }

    // Débloque un chapitre (retire le numéro de chap_locked)
    static public function debloquerChapitre(int $classe_id, int $chap_num)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $stmt = $bdd->prepare('SELECT chap_locked FROM classe WHERE id = :id');
        $stmt->bindValue(':id', $classe_id, SQLITE3_INTEGER);
        $r = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        $locked = json_decode($r['chap_locked'] ?? '[]', true);
        $locked = array_values(array_filter($locked, function($c) use ($chap_num) { return $c !== $chap_num; }));
        $upd = $bdd->prepare('UPDATE classe SET chap_locked = :locked WHERE id = :id');
        $upd->bindValue(':locked', json_encode($locked), SQLITE3_TEXT);
        $upd->bindValue(':id', $classe_id, SQLITE3_INTEGER);
        $res = $upd->execute();
        return $res ? 1 : -1;
    }

    // Vérifie si un chapitre est disponible pour une classe
    static public function isChapDisponible(int $classe_id, int $chap_num) : bool
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $stmt = $bdd->prepare('SELECT chap_dispo, chap_locked FROM classe WHERE id = :id');
        $stmt->bindValue(':id', $classe_id, SQLITE3_INTEGER);
        $r = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if (!$r) return false;

        $chap_dispo = $r['chap_dispo'] !== null ? intval($r['chap_dispo']) : null;
        $locked = json_decode($r['chap_locked'] ?? '[]', true);

        // si chapitre explicitement bloqué
        if (in_array($chap_num, $locked, true)) return false;

        // si chap_dispo défini et chap_num > chap_dispo => bloqué
        if ($chap_dispo !== null && $chap_num > $chap_dispo) return false;

        return true;
    }
}