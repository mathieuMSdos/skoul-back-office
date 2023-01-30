<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Teacher extends CoreModel
{
    /**
     * @var string
     */
    private $firstname;
    /**
     * @var string
     */
    private $lastname;
    /**
     * @var string
     */
    private $job;

    /**
     * Méthode permettant de récupérer un enregistrement de la table teacher en fonction d'un id donné
     *
     * @param int $teacherId ID du prof
     * @return Teacher
     */
    public static function find($teacherId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `teacher` WHERE `id` =' . $teacherId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        // self::class ça tue! ça fournit automatiquement le FQCN de la classe dans laquelle on utilise le mot-clé "self"
        $item = $pdoStatement->fetchObject(self::class);

        // retourner le résultat
        return $item;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table teacher
     *
     * @return Teacher[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `teacher`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

        return $results;
    }

    /**
     * Méthode permettant d'ajouter un enregistrement dans la table teacher
     *
     * @return bool
     */
    protected function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        $sql = '
            INSERT INTO `teacher` (firstname, lastname, job, status)
            VALUES (:firstname, :lastname, :job, :status)
        ';

        // Préparation de la requête d'insertion (pas exec, pas query)
        $pdoStatement = $pdo->prepare($sql);

        // On bind chaque jeton/token/placeholder
        $pdoStatement->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $pdoStatement->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $pdoStatement->bindValue(':job', $this->job, PDO::PARAM_STR);
        $pdoStatement->bindValue(':status', $this->status, PDO::PARAM_INT);

        // On exécute la requête préparée
        $ok = $pdoStatement->execute();

        // Si au moins une ligne ajoutée
        if ($pdoStatement->rowCount() > 0) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }

        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }

    /**
     * Méthode permettant de mettre à jour un enregistrement dans la table teacher
     *
     * @return bool
     */
    protected function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            UPDATE `teacher`
            SET
                firstname = :firstname,
                lastname = :lastname,
                job = :job,
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ";

        // Préparation de la requête de mise à jour (pas exec, pas query)
        $pdoStatement = $pdo->prepare($sql);

        // On bind chaque jeton/token/placeholder
        $pdoStatement->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $pdoStatement->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $pdoStatement->bindValue(':job', $this->job, PDO::PARAM_STR);
        $pdoStatement->bindValue(':status', $this->status, PDO::PARAM_INT);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

        // On exécute la requête préparée
        $ok = $pdoStatement->execute();

        // On retourne VRAI, si au moins une ligne modifiée
        return ($pdoStatement->rowCount() > 0);
    }

    /**
     * Méthode permettant de supprimer un enregistrement dans la table teacher
     *
     * @return bool
     */
    public function delete()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            DELETE FROM `teacher`
            WHERE id = :id
        ";

        // Préparation de la requête de mise à jour (pas exec, pas query)
        // Bon, ici, on aurait pu utiliser exec car l'id ne peut venir que de la base de données, il est donc "sécurisé"
        $pdoStatement = $pdo->prepare($sql);

        // On bind chaque jeton/token/placeholder
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

        // On exécute la requête préparée
        $ok = $pdoStatement->execute();

        // On retourne VRAI, si au moins une ligne supprimée
        return ($pdoStatement->rowCount() > 0);
    }

    /**
     * Get the value of firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param  string  $firstname
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get the value of lastname
     *
     * @return  string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param  string  $lastname
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the value of job
     *
     * @return  string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set the value of job
     *
     * @param  string  $job
     */
    public function setJob(string $job)
    {
        $this->job = $job;
    }
}
