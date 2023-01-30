<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models
abstract class CoreModel
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var int
     */
    protected $status;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;

    /**
     * Méthode permettant de sauvegarder un Model (insert ou update)
     *
     * @return bool
     */
    public function save()
    {
        // si le Model existe déjà (id > 0)
        if ($this->id > 0) {
            // Alors on met à jour
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    // Pour que la méthode save() fonctionne bien, chaque Model doit avoir déclaré les méthodes insert et update en protected
    // Donc, puisque CoreModel est une classe abstraite, on peut forcer les classes "enfants" à déclarer (implémenter) ces méthodes
    abstract protected function insert();
    abstract protected function update();

    /**
     * Get the value of id
     *
     * @return  int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}
