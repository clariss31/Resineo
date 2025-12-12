<?php

/**
 * Classe abstraite qui représente un manager. Elle récupère automatiquement le gestionnaire de base de données. 
 */
abstract class AbstractEntityManager
{

    /**
     * Instance de la classe DBManager pour interagir avec la base de données.
     * @var DBManager
     */
    protected $db;

    /**
     * Constructeur de la classe.
     * Il récupère automatiquement l'instance de DBManager. 
     */
    public function __construct()
    {
        $this->db = DBManager::getInstance();
    }
}
