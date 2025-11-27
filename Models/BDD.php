<?php
namespace NoodleML\Models;

use Exception;
use SQLite3;

class BDD
{
    private $db;

    public function __construct() {
        $this->db = new SQLite3(__DIR__ . '/../Data/nodlml.db');
        if (!$this->db) {
            throw new Exception('Impossible d\'ouvrir la base de données : ' . $this->db->lastErrorMsg());
        }

        // S'assurer que la colonne chap_locked existe pour la gestion des chapitres bloqués
        $this->ensureChapLockedColumnExists();
    }

    /**
     * Vérifie si la colonne chap_locked existe dans la table classe, sinon l'ajoute.
     */
    private function ensureChapLockedColumnExists()
    {
        $res = $this->db->query("PRAGMA table_info(classe)");
        $exists = false;
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            if (isset($row['name']) && $row['name'] === 'chap_locked') {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            // Ajout de la colonne chap_locked de type TEXT (stockera un JSON array)
            $this->db->exec("ALTER TABLE classe ADD COLUMN chap_locked TEXT DEFAULT '[]'");
        }
    }

    public function login($email, $mdp)
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateur WHERE email = :email');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $user = $result->fetchArray(SQLITE3_ASSOC);
        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }

    public function addEleve(string $email, string $password, ?string $nom = null, ?string $prenom = null, ?int $classe_id = null, string $role = 'eleve')
    {
        $stmt = $this->db->prepare('INSERT INTO utilisateur (email, mot_de_passe, nom, prenom, classe_id, role) VALUES (:email, :mdp, :nom, :prenom, :classe_id, :role)');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        // stocke le mot de passe hashé
        $stmt->bindValue(':mdp', password_hash($password, PASSWORD_BCRYPT), SQLITE3_TEXT);
        $stmt->bindValue(':nom', $nom, SQLITE3_TEXT);
        $stmt->bindValue(':prenom', $prenom, SQLITE3_TEXT);
        if ($classe_id === null) {
            $stmt->bindValue(':classe_id', null, SQLITE3_NULL);
        } else {
            $stmt->bindValue(':classe_id', $classe_id, SQLITE3_INTEGER);
        }
        $stmt->bindValue(':role', $role, SQLITE3_TEXT);

        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }

    public function supprimerEleve($email)
    {
        $stmt = $this->db->prepare('DELETE FROM utilisateur WHERE email = :email');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }

    public function reinitialisationMdp($email)
    {
        // Remplace le mot de passe par 'reseted' (idéalement à remplacer par un token)
        $stmt = $this->db->prepare('UPDATE utilisateur SET mot_de_passe = :mdp WHERE email = :email');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        // ici on utilise un hash minimal pour garder la même forme (mais 'reseted' en clair n'est pas sécurisé)
        $stmt->bindValue(':mdp', password_hash('reseted', PASSWORD_BCRYPT), SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }

    public function getClassesNomByEtablissement($etablissement_num)
    {
        $stmt = $this->db->prepare('SELECT nom FROM classe WHERE etablissement_num = :etablissement');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':etablissement', $etablissement_num, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $noms = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $noms[] = $row['nom'];
        }
        return $noms;
    }

    public function getClassesByEtablissement($id)
    {
        $stmt = $this->db->prepare('SELECT id, nom FROM classe WHERE etablissement_num = :id');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $classes = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $classes[] = $row;
        }
        return $classes;
    }

    public function ajouterClasse(string $nom, string $prof, int $etablissement_num, int $chap_dispo = 0)
    {
        $stmt = $this->db->prepare('INSERT INTO classe (nom, prof, etablissement_num, chap_dispo) VALUES (:nom, :prof, :etablissement_num, :chap_dispo)');
        if (!$stmt) {
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
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result) {
            return true;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function getEtablissementsNomByProf($userEmail)
    {
        // On récupère les établissements (num, nom) liés aux classes du prof (par email)
        $stmt = $this->db->prepare('
            SELECT e.num AS id, e.nom 
            FROM etablissement AS e
            INNER JOIN classe AS c ON c.etablissement_num = e.num
            WHERE c.prof = :prof
            GROUP BY e.num, e.nom
            ORDER BY e.nom
        ');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':prof', $userEmail, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $etabs = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $etabs[] = [
                'id' => $row['id'],
                'nom' => $row['nom']
            ];
        }
        return $etabs;
    }

    public function ajouterEtablissement(string $nom)
    {
        $stmt = $this->db->prepare('INSERT INTO etablissement (nom) VALUES (:nom)');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':nom', $nom, SQLITE3_TEXT);
        $result = $stmt->execute();
        if ($result) {
            return true;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function supprimerEtablissement($id)
    {
        $stmt = $this->db->prepare('DELETE FROM etablissement WHERE num = :id');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result) {
            return true;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function getEleveByClasse($classe_id)
    {
        $eleves = [];
        $stmt = $this->db->prepare("
            SELECT eleve.email as email, eleve.nom as nom, eleve.prenom as prenom,
                   classe.id as classe_id, classe.nom as classe_nom, etab.nom as etablissement_nom
            FROM utilisateur AS eleve
            INNER JOIN classe ON eleve.classe_id = classe.id
            INNER JOIN etablissement AS etab ON classe.etablissement_num = etab.num
            WHERE eleve.classe_id = :classe_id
        ");
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':classe_id', $classe_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $eleve = [
                    'email' => $row['email'],
                    'nom' => $row['nom'],
                    'prenom' => $row['prenom'],
                    'classe_nom' => $row['classe_nom'],
                    'etab_nom' => $row['etablissement_nom'],
                    'classe_id' => $row['classe_id']
                ];
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
        $stmt = $this->db->prepare("SELECT email FROM utilisateur WHERE role = 'professeur'");
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $result = $stmt->execute();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $profs[] = ['email' => $row['email']];
            }
            return $profs;
        } else {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
    }

    public function getChapDispo($id)
    {
        $stmt = $this->db->prepare("SELECT chap_dispo FROM classe WHERE id = :id");
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row ? $row['chap_dispo'] : null;
    }

    public function setChapDispo($id, $chap_dispo)
    {
        $stmt = $this->db->prepare('UPDATE classe SET chap_dispo = :chap_dispo WHERE id = :id');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':chap_dispo', $chap_dispo, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }

    public function bloquerChapitre($classe_id, $chap_num)
    {
        $stmt = $this->db->prepare('SELECT chap_locked FROM classe WHERE id = :id');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $classe_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $locked = json_decode($row['chap_locked'] ?? '[]', true);
        if (!is_array($locked)) $locked = [];

        if (!in_array($chap_num, $locked, true)) {
            $locked[] = $chap_num;
            $upd_stmt = $this->db->prepare('UPDATE classe SET chap_locked = :locked WHERE id = :id');
            if (!$upd_stmt) {
                throw new Exception('Failed to prepare update statement : ' . $this->db->lastErrorMsg());
            }
            $upd_stmt->bindValue(':locked', json_encode(array_values($locked)), SQLITE3_TEXT);
            $upd_stmt->bindValue(':id', $classe_id, SQLITE3_INTEGER);
            $upd_result = $upd_stmt->execute();
            if (!$upd_result) {
                throw new Exception('Failed to execute update query : ' . $this->db->lastErrorMsg());
            }
            return true;
        }
        return true;
    }

    public function debloquerChapitre($classe_id, $chap_num)
    {
        $stmt = $this->db->prepare('SELECT chap_locked FROM classe WHERE id = :id');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $classe_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $locked = json_decode($row['chap_locked'] ?? '[]', true);
        if (!is_array($locked)) $locked = [];

        $locked = array_values(array_filter($locked, function($c) use ($chap_num) {
            return $c !== $chap_num;
        }));

        $upd_stmt = $this->db->prepare('UPDATE classe SET chap_locked = :locked WHERE id = :id');
        if (!$upd_stmt) {
            throw new Exception('Failed to prepare update statement : ' . $this->db->lastErrorMsg());
        }
        $upd_stmt->bindValue(':locked', json_encode($locked), SQLITE3_TEXT);
        $upd_stmt->bindValue(':id', $classe_id, SQLITE3_INTEGER);
        $upd_result = $upd_stmt->execute();
        if (!$upd_result) {
            throw new Exception('Failed to execute update query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }

    public function isChapDispo($classe_id, $chap_num)
    {
        $stmt = $this->db->prepare('SELECT chap_dispo, chap_locked FROM classe WHERE id = :id');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':id', $classe_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if (!$row) return false;

        $chap_dispo = $row['chap_dispo'] !== null ? intval($row['chap_dispo']) : null;
        $locked = json_decode($row['chap_locked'] ?? '[]', true);
        if (!is_array($locked)) $locked = [];

        // si chapitre explicitement bloqué
        if (in_array($chap_num, $locked, true)) return false;

        // si chap_dispo défini et chap_num > chap_dispo => bloqué
        if ($chap_dispo !== null && $chap_num > $chap_dispo) return false;

        return true;
    }

    public function register($email, $mdp)
    {
        $stmt = $this->db->prepare('UPDATE utilisateur SET mot_de_passe = :mdp WHERE email = :email');
        if (!$stmt) {
            throw new Exception('Failed to prepare statement : ' . $this->db->lastErrorMsg());
        }
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':mdp', password_hash($mdp, PASSWORD_BCRYPT), SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception('Failed to execute query : ' . $this->db->lastErrorMsg());
        }
        return true;
    }
}