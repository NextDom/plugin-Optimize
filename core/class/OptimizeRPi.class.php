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

require_once('BaseOptimize.class.php');

class OptimizeRPi extends BaseOptimize
{
    /**
     * Chaîne de caractère se trouvant dans l'intitulé du hardware dans le cas d'un Raspberry Pi
     */
    const RASPBERRY_STRING_TEST_1 = 'rpi';
    /**
     * Chaîne de caractère pouvant se trouver dans l'intitulé du hardware dans le cas d'un Raspberry Pi
     */
    const RASPBERRY_STRING_TEST_2 = 'raspberry';
    /**
     * Chemin vers le répertoire config.txt
     */
    const SYSTEM_CONFIG_FILE_PATH = '/boot/config.txt';
    /**
     * Code du paramètre de la mémoire GPU
     */
    const GPU_MEM_NAME = 'gpu_mem';
    /**
     * Valeur idéale du paramètre de la mémoire GPU
     */
    const GPU_MEM_BEST_VALUE = 16;
    /**
     * Code du paramètre de la mémoire cache
     */
    const L2_CACHE_NAME = 'disable_l2cache';
    /**
     * Valeur idéale du paramètre de la mémoire cache
     */
    const L2_CACHE_BEST_VALUE = 0;

    /**
     * Données du fichier de configurations système
     */
    private $systemConfig;

    /**
     * Test si Jeedom est installé sur un Raspberry Pi
     *
     * @return bool true si Jeedom a détecté un Raspbbery Pi
     */
    public function isRaspberryPi()
    {
        $result = false;

        $hardwareName = jeedom::getHardwareName();
        // En minuscule au cas où Jeedom changerait l'intitulé
        $hardwareName = \strtolower($hardwareName);

        // raspberry est testé dans le cas d'un changement de l'intitulé
        if (\strstr($hardwareName, self::RASPBERRY_STRING_TEST_1) !== false ||
            \strstr($hardwareName, self::RASPBERRY_STRING_TEST_2) !== false) {
            $result = true;
        }
        return $result;
    }

    /**
     * Test si Jeedom peut exécuter des actions demandant des privilèges.
     *
     * @return bool true si c'est possible
     */
    public function canSudo()
    {
        return jeedom::isCapable('sudo');
    }

    /**
     * Test si le fichier de configuration système est lisible et lit son contenu
     *
     * @return bool true si la lecture a réussi
     */
    public function canParseSystemConfigFile()
    {
        $result = false;
        if ($this->isSystemConfigFileReadable()) {
            return $this->parseSystemConfigFile();
        }
        return $result;
    }

    /**
     * Note l'optimisation de la réduction de la mémoire GPU est appliquée
     *
     * @return bool true si l'optimisation est déjà appliquée.
     */
    public function getGpuMemOptimizationInformation()
    {
        return $this->getOptimizationInformation(self::GPU_MEM_NAME, self::GPU_MEM_BEST_VALUE);
    }

    /**
     * Note l'optimisation de la mémoire cache est appliquée
     *
     * @return bool true si l'optimisation est déjà appliquée.
     */
    public function getL2CacheOptimizationInformation()
    {
        return $this->getOptimizationInformation(self::L2_CACHE_NAME, self::L2_CACHE_BEST_VALUE);
    }

    /**
     * Note une optimisation
     *
     * @param string $name Nom du paramètre
     * @param string $bestValue Valeur idéale
     *
     * @return string Note de l'optimisation
     */
    private function getOptimizationInformation($name, $bestValue)
    {
        $result = 'warn';
        self::$bestScore++;
        if (\array_key_exists($name, $this->systemConfig)) {
            if ($this->systemConfig[$name] == $bestValue) {
                $result = 'ok';
            }
        }
        if ($result == 'warn') {
            self::$badPoints++;
        }
        return $result;
    }

    /**
     * Test si le fichier de configuration système est lisible
     *
     * @return bool true si le fichier peut être lu
     */
    private function isSystemConfigFileReadable()
    {
        $result = false;
        if (\file_exists(self::SYSTEM_CONFIG_FILE_PATH)) {
            if (\is_readable(self::SYSTEM_CONFIG_FILE_PATH)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Analyse le contenu du fichier de configuration système.
     */
    private function parseSystemConfigFile()
    {
        $result = false;
        $this->systemConfig = array();

        $fileHandle = \fopen(self::SYSTEM_CONFIG_FILE_PATH, "r");
        if ($fileHandle !== false) {
            while (!\feof($fileHandle)) {
                $line = \fgets($fileHandle);
                // Suppression des espaces inutiles au début de la chaine (et à la fin)
                $line = \trim($line);
                if ($this->lineContaintsInformation($line)) {
                    $configInformation = $this->readSystemConfigLineInformation($line);
                    if ($configInformation !== false) {
                        $this->systemConfig[$configInformation[0]] = $configInformation[1];
                    }
                }
            }
            \fclose($fileHandle);
            $result = true;
        }
        return $result;
    }

    /**
     * Test si une ligne contient des informations
     *
     * @param string $line Ligne à tester
     *
     * @return bool true si la ligne contient des données
     */
    private function lineContaintsInformation($line)
    {
        $result = false;
        // Test d'une ligne vide
        if (\strlen($line) > 0) {
            // Test d'une ligne de commentaire
            if ($line[0] != '#') {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Lit les information d'une ligne.
     * Le résultat est un tableau avec deux éléments. La clé puis la valeur.
     *
     * @param string $line Ligne contenant des informations
     *
     * @return array|bool Informations lues ou false
     */
    private function readSystemConfigLineInformation($line)
    {
        $result = false;
        if (\strpos($line, '=') !== 0) {
            $result = \explode('=', $line);
        }
        return $result;
    }

    /**
     * Optimise l'option gpu_mem
     *
     * @return bool true si l'écriture réussit
     */
    public function optimizeGpuMem()
    {
        return $this->optimizeItem(self::GPU_MEM_NAME, self::GPU_MEM_BEST_VALUE);
    }

    /**
     * Optimise l'option l2_cache
     *
     * @return bool true si l'écriture réussit
     */
    public function optimizeL2Cache()
    {
        return $this->optimizeItem(self::L2_CACHE_NAME, self::L2_CACHE_BEST_VALUE);
    }

    /**
     * Optimise une option
     *
     * @param string $name Nom du paramètre
     * @param string $bestValue Valeur idéale
     *
     * @return bool true si l'écriture réussit
     */
    private function optimizeItem($name, $bestValue)
    {
        $result = false;
        if ($this->createBackupSystemConfigFile() === true) {
            if ($this->parseSystemConfigFile() === true) {
                if (\array_key_exists($name, $this->systemConfig)) {
                    // On commente le paramètre
                    $this->commentParameter($name);
                }
                $this->addParameter($name, $bestValue);
                $result = true;
            }
            return $result;
        }
        return $result;
    }

    /**
     * Créer un fichier backup du fichier de configuration système
     *
     * @return bool true si la sauvegarde a réussi
     */
    private function createBackupSystemConfigFile()
    {
        $result = false;
        if ($this->canSudo()) {
            \exec(system::getCmdSudo() . ' cp ' . self::SYSTEM_CONFIG_FILE_PATH . ' ' . self::SYSTEM_CONFIG_FILE_PATH . '.bak');
            $result = file_exists(self::SYSTEM_CONFIG_FILE_PATH . '.bak');
        }
        return $result;
    }

    /**
     * Commente un paramètre du fichier de configuration système
     *
     * @param string $name Nom du paramètre
     */
    private function commentParameter($name)
    {
        \exec(system::getCmdSudo() . ' sed -i\'\' \'s/^' . $name . '/#' . $name . '/\' ' . self::SYSTEM_CONFIG_FILE_PATH);
    }

    /**
     * Ajoute un paramètre au fichier de configuration système.
     *
     * @param string $name Nom du paramètre
     * @param string $value Valeur du paramètre
     */
    private function addParameter($name, $value)
    {
        \exec(system::getCmdSudo() . ' sh -c "echo \'' . $name . '=' . $value . '\' >> ' . self::SYSTEM_CONFIG_FILE_PATH . '"');
    }
}
