<?php

/**
 * Classe inscrivant l'historique des actions effectuées lors des tests
 */
class MockedActions
{
    /**
     * @var array Liste des actions
     */
    public static $actionsList = array();

    /**
     * @param array $toAdd Ajoute une action à la liste
     */
    public static function add($toAdd)
    {
        array_push(self::$actionsList, $toAdd);
    }

    /**
     * Obtenir la liste des actions
     *
     * @return array Liste des actions
     */
    public static function get()
    {
        return self::$actionsList;
    }

    /**
     * Effacer la liste des actions
     */
    public static function clear()
    {
        self::$actionsList = array();
    }
}

/**
 * Classe définissant certaines variables pour orienter le comportement de Jeedom
 */
class JeedomVars
{
    /**
     * @var bool Valeur renvoyée par la fonction isConnect()
     */
    public static $jeedomIsConnected = true;

    /**
     * @var array Tableau des réponses de la fonction init()
     */
    public static $initAnswers = array();
}

/**
 * Mock de la fonction d'inclusion d'un fichier
 *
 * @param $folder Répertoire du fichier
 * @param $name Nom du fichier
 * @param $type Type de fichier
 * @param null $plugin Plugin si ce n'est pas un fichier du core
 */
function include_file($folder, $name, $type, $plugin = null)
{
    MockedActions::add(array('action' => 'include_file', 'folder' => $folder, 'name' => $name, 'type' => $type, 'plugin' => $plugin));
}

/**
 * Mock de la fonction de test de connection de l'utilisateur
 * Renvoie la valeur stockée dans JeedomVars::$jeedomIsConnected
 *
 * @param null $user Utilisateur connecté (facultatif)
 *
 * @return bool Valeur de JeedomVars::$jeedomIsConnected
 */
function isConnect($user = null)
{
    return JeedomVars::$jeedomIsConnected;
}

/**
 * Mock de la fonction d'initialisation d'une valeur
 * Renvoie la valeur correspondant à la clé du tableau JeedomVars::$initAnswers
 *
 * @param $key Clé du tableau
 *
 * @return mixed Valeur de la clé du tableau JeedomVars::$initAnswers
 */
function init($key)
{
    return JeedomVars::$initAnswers[$key];
}

/**
 * Mock de la fonction de traduction
 * Renvoie le message en paramètre
 *
 * @param $msg Chaine à traduire
 * @return mixed Chaine passée en paramètre
 */
function __($msg)
{
    return $msg;
}

/**
 * Mock de la fonction d'affichage d'une exception
 * Ne fait rien
 *
 * @param $exceptionMsg Message à afficher
 */
function displayExeption($exceptionMsg)
{
    displayException($exceptionMsg);
}

/**
 * Mock de la fonction d'affichage d'une exception
 * Ne fait rien
 *
 * @param $exceptionMsg Message à afficher
 */
function displayException($exceptionMsg)
{

}
