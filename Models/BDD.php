<?php
namespace NoodleML\Models;
use Exception;
use SQLite3;

class BDD
{
    private $db;

    public function __construct() {
        $this->db = new SQLite3('../Data/nodlml.db');
        if (!$this->db) {
            echo $this->db->lastErrorMsg();
        }
    }

    public function login($email, $mdp)
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateur WHERE email = :email AND mot_de_passe = :mdp');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':mdp', $mdp, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result)
        {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $user = $result->fetchArray(SQLITE3_ASSOC);
        return $user;
    }

    public function addEleve(string $email, string $password, ?string $nom = null, ?string $prenom = null, ?int $classe_id = null, string $role = 'eleve')
    {
        $stmt = $this->db->prepare('INSERT INTO utilisateur (email, mot_de_passe, nom, prenom, classe_id, role) values (:email, :mdp, :nom, :prenom, :classe_id, :role)');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':mdp', $password, SQLITE3_TEXT);
        $stmt->bindValue(":nom", $nom, SQLITE3_TEXT);
        $stmt->bindValue(":prenom", $prenom, SQLITE3_TEXT);
        $stmt->bindValue(":classe_id", $classe_id, SQLITE3_INTEGER);
        $stmt->bindValue(":role", $role, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result)
        {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }

    public function supprimerEleve($email)
    {
        $stmt = $this->db->prepare('DELETE FROM utilisateur WHERE email = :email');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(":email", $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result)
        {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }

    public function reinitialisationMdp($email)
    {
        $stmt = $this->db->prepare('UPDATE utilisateur SET mot_de_passe = "reseted" WHERE email = :email');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result)
        {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }

    public function getClassesNomByID($id)
    {
        $stmt = $this->db->prepare('SELECT nom FROM classe WHERE id = :id');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if (!$result)
        {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $nom = $result->fetchArray(SQLITE3_ASSOC);
        return $nom;
    }

    public function ajouterClasse(string $nom, string $prof, int $etablissement_num, int $chap_dispo=0)
    {
        $stmt = $this->db->prepare("INSERT INTO classe (nom, prof, etablissement_num, chap_dispo) VALUES (:nom, :prof, :etablissement_num, 0)");
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':nom', $nom, SQLITE3_TEXT);
        $stmt->bindValue(':prof', $prof, SQLITE3_TEXT);
        $stmt->bindValue(':etablissement_num', $etablissement_num, SQLITE3_INTEGER);
        $stmt->bindValue(':chap_dispo', $chap_dispo, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result) {
            return true;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function supprimerClasse($id)
    {
        $stmt = $this->db->prepare('DELETE FROM classe WHERE id = :id');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result)
        {
            return true;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function getEtablissementsNomById($id)
    {
        $stmt = $this->db->prepare('SELECT nom FROM etablissement WHERE id = :id');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result)
        {
            $nom = $result->fetchArray(SQLITE3_ASSOC);
            return $nom;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function ajouterEtablissement(string $nom)
    {
        $stmt = $this->db->prepare('INSERT INTO etablissement (nom) VALUES (:nom)');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':nom', $nom, SQLITE3_TEXT);
        $result = $stmt->execute();
        if ($result)
        {
            return true;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function supprimerEtablissement($id)
    {
        $stmt = $this->db->prepare('DELETE FROM etablissement WHERE id = :id');
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result)
        {
            return true;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function getEleveByClasse($classe_id)
    {
        $eleves = [];
        $stmt = $this->db->prepare("SELECT eleve.email as email, classe.id as classe_id, classe.nom as classe_nom, etab.nom as etablissement_nom
        FROM utilisateur AS eleve
        INNER JOIN classe ON eleve.classe_id = classe.id
        INNER JOIN etablissement AS etab ON classe.etablissement_num = etab.id
        WHERE eleve.classe_id = :classe_id");
        if (!$stmt)
        {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':classe_id', $classe_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $eleve = array(
                    'email' => $row['email'],
                    'classe_nom' => $row['classe_nom'],
                    'etab_nom' => $row['etablissement_nom']
                );
                $eleves[] = $eleve;
            }
            return $eleves;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function getProfs()
    {
        $profs = [];
        $stmt = $this->db->prepare("SELECT prof.email from utilisateur as prof where role = 'professeur'");
        if (!$stmt)
        {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $result = $stmt->execute();
        if ($result)
        {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $prof = array(
                    'email' => $row['email']
                );
                $profs[] = $prof;
            }
            return $profs;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
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