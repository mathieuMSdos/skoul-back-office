<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class AppUser extends CoreModel
{

    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $role;

    /**
     * Méthode permettant de récupérer un enregistrement de la table app_user en fonction d'un id donné
     *
     * @param string $email adresse email
     * @return AppUser
     */
    public static function findByEmail(string $email)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `app_user` WHERE `email` = :email';

        // exécuter notre requête
        $pdoStatement = $pdo->prepare($sql);

        // On bind chaque jeton/token/placeholder
        $pdoStatement->bindValue(':email', $email, PDO::PARAM_STR);

        // On exécute la requête préparée
        $pdoStatement->execute();

        // un seul résultat => fetchObject
        // self::class ça tue! ça fournit automatiquement le FQCN de la classe dans laquelle on utilise le mot-clé "self"
        $item = $pdoStatement->fetchObject(self::class);

        // retourner le résultat
        return $item;
    }

    /**
     * Méthode permettant de récupérer un enregistrement de la table app_user en fonction d'un id donné
     *
     * @param int $appUserId ID du user
     * @return AppUser
     */
    public static function find($appUserId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `app_user` WHERE `id` =' . $appUserId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        // self::class ça tue! ça fournit automatiquement le FQCN de la classe dans laquelle on utilise le mot-clé "self"
        $item = $pdoStatement->fetchObject(self::class);

        // retourner le résultat
        return $item;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table app_user
     *
     * @return AppUser[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `app_user`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

        return $results;
    }

    /**
     * Méthode permettant d'ajouter un enregistrement dans la table app_user
     *
     * @return bool
     */
    protected function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        $sql = '
            INSERT INTO `app_user` (email, name, password, role, status)
            VALUES (:email, :name, :password, :role, :status)
        ';

        // Préparation de la requête d'insertion (pas exec, pas query)
        $pdoStatement = $pdo->prepare($sql);

        // On bind chaque jeton/token/placeholder
        $pdoStatement->bindValue(':email', $this->email, PDO::PARAM_STR);
        $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindValue(':password', $this->password, PDO::PARAM_STR);
        $pdoStatement->bindValue(':role', $this->role, PDO::PARAM_STR);
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
     * Méthode permettant de mettre à jour un enregistrement dans la table app_user
     *
     * @return bool
     */
    protected function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            UPDATE `app_user`
            SET
                email = :email,
                name = :name,
                password = :password,
                role = :role,
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ";

        // Préparation de la requête de mise à jour (pas exec, pas query)
        $pdoStatement = $pdo->prepare($sql);

        // On bind chaque jeton/token/placeholder
        $pdoStatement->bindValue(':email', $this->email, PDO::PARAM_STR);
        $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindValue(':password', $this->password, PDO::PARAM_STR);
        $pdoStatement->bindValue(':role', $this->role, PDO::PARAM_STR);
        $pdoStatement->bindValue(':status', $this->status, PDO::PARAM_INT);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

        // On exécute la requête préparée
        $ok = $pdoStatement->execute();

        // On retourne VRAI, si au moins une ligne modifiée
        return ($pdoStatement->rowCount() > 0);
    }

    /**
     * Méthode permettant de supprimer un enregistrement dans la table app_user
     *
     * @return bool
     */
    public function delete()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            DELETE FROM `app_user`
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
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of password
     *
     * @return  string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     */
    public function setNewPassword(string $clearPassword)
    {
        // On hash le mot de passe désiré avant sauvegarde en DB
        $this->password = password_hash($clearPassword, PASSWORD_DEFAULT);
    }

    /**
     * Get the value of role
     *
     * @return  string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param  string  $role
     */
    public function setRole(string $role)
    {
        $this->role = $role;
    }
}
