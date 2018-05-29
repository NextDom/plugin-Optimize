<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once(__DIR__ . '/../../../../core/config/common.config.php');
require_once(__DIR__ . '/../../../../core/class/DB.class.php');

class DataStorage
{
    private $dataTableName;

    /**
     * Constructeur.
     * Initialise le nom de la table des données
     *
     * @param string $dataTableName Nom de la table des données.
     */
    public function __construct($dataTableName)
    {
        $this->dataTableName = 'data_' . $dataTableName;
    }

    /**
     * Test si une table existe dans la base de données
     *
     * @return bool True si la table exists
     */
    public function isDataTableExists()
    {
        $returnValue = false;
        $statement = DB::getConnection()->prepare("SHOW TABLES LIKE ?");
        $statement->execute(array($this->dataTableName));
        $dbResult = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (count($dbResult) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    /**
     * Créer la table des données
     */
    public function createDataTable()
    {
        if (!$this->isDataTableExists()) {
            $statement = DB::getConnection()
                ->prepare("CREATE TABLE `" . $this->dataTableName . "` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, `code` VARCHAR(256) NOT NULL, `data` TEXT NULL)");
            $statement->execute();
        }
    }

    /**
     * Supprimer la table des données
     */
    public function dropDataTable()
    {
        DB::getConnection()->prepare("DROP TABLE IF EXISTS `" . $this->dataTableName . "`")->execute();
    }

    /**
     * Supprime une donnée de la base de données
     *
     * @param string $code Code de la donnée
     */
    public function deleteData($code)
    {
        $statement = DB::getConnection()
            ->prepare("DELETE FROM `" . $this->dataTableName . "` WHERE `code` = ?");
        $statement->execute(array($code));
    }

    /**
     * Obtenir une données stockée brute
     *
     * @param string $code Codes des données
     *
     * @return mixed Données correspondant au code.
     */
    public function getRawData($code)
    {
        $returnValue = null;
        $statement = DB::getConnection()->prepare("SELECT `data` FROM `" . $this->dataTableName . "` WHERE `code` = ?");
        $statement->execute(array($code));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            $returnValue = $result[0]['data'];
        }
        return $returnValue;
    }

    /**
     * Test si une donnée existe
     *
     * @param string $code Code de la donnée
     *
     * @return bool True si la données existe
     */
    public function isDataExists($code)
    {
        $result = false;
        if ($this->getRawData($code) !== null) {
            $result = true;
        }
        return $result;
    }

    /**
     * Ajoute des données brutes
     *
     * @param string $code Codes des données
     * @param string $data Données brutes
     */
    public function addRawData($code, $data)
    {
        $statement = DB::getConnection()
            ->prepare("INSERT INTO `" . $this->dataTableName . "` (`code`, `data`) VALUES (?, ?)");
        $statement->execute(array($code, $data));
    }

    /**
     * Met à jour une donnée brutes stockées
     *
     * @param string $code Codes des données
     * @param mixed $data Données brutes
     */
    public function updateRawData($code, $data)
    {
        $statement = DB::getConnection()
            ->prepare("UPDATE `" . $this->dataTableName . "` SET `data` = ? WHERE `code` = ?");
        $statement->execute(array($data, $code));

    }

    /**
     * Stocke des données brutes.
     * Les données sont mises à jour si elles avaient été stockées précédemment.
     *
     * @param string $code Code des données.
     * @param mixed $data Données brutes
     */
    public function storeRawData($code, $data)
    {
        if ($this->isDataExists($code)) {
            $this->updateRawData($code, $data);
        } else {
            $this->addRawData($code, $data);
        }
    }

    /**
     * Stocke des données au format JSON.
     *
     * @param string $code Code des données
     * @param array $jsonData Données au format JSON
     */
    public function storeJsonData($code, $jsonData)
    {
        $this->storeRawData($code, json_encode($jsonData));
    }

    /**
     * Obtenir des données JSON
     *
     * @param string $code Code des données
     *
     * @return array Tableau de données.
     */
    public function getJsonData($code)
    {
        return json_decode($this->getRawData($code), true);
    }
}
