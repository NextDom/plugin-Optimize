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
require_once('DataStorage.class.php');

class OptimizeSystem extends BaseOptimize
{
    /**
     * @var array Tableau des différents logs système
     */
    private $systemLogs = array(
        'scenario' => 'Scenario',
        'plugin' => 'Plugin',
        'market' => 'Market',
        'api' => 'Api',
        'connection' => 'Connection',
        'interact' => 'Interact',
        'tts' => 'TTS',
        'report' => 'Report',
        'event' => 'Event'
    );

    private $dataStorage;

    public function __construct()
    {
        $this->dataStorage = new DataStorage('optimize');
    }

    /**
     * Evalue les informations d'un log système.
     *
     * @param array $informations Informations à évaluer
     *
     * @return array Rapport sur les informations évaluées
     */
    private function rateSystemLogInformations($informations)
    {
        $rating = array();

        // Valeurs par défaut
        $rating['log'] = 'ok';
        self::$bestScore++;

        // Les logs doivent être désactivés
        if ($informations['log'] === true) {
            self::$badPoints++;
            $rating['log'] = 'warn';
        }

        return $rating;
    }

    /**
     * Obtenir les informations et une évaluation de l'ensemble des logs système.
     *
     * @return array Informations sur l'ensemble des scénarios
     */
    public function getLogInformations()
    {
        $informations = array();

        foreach ($this->systemLogs as $systemLogId => $systemLogName) {
            $systemLogInformations = array();
            $systemLogInformations['id'] = $systemLogId;
            $systemLogInformations['name'] = $systemLogName;
            $systemLogConfig = config::byKey('log::level::' . $systemLogId);
            $systemLogInformations['log'] = false;
            // Chaque type de log est stocké dans un tableau et identifié par un nombre sauf "default"
            // 1000 représente "Aucun"
            foreach ($systemLogConfig as $logType => $value) {
                if ($value == "1" && $logType != 1000 || ($logType == 1000 && $value != "1")) {
                    $systemLogInformations['log'] = true;
                }
            }
            $rating = $this->rateSystemLogInformations($systemLogInformations);
            $systemLogInformations['rating'] = $rating;
            \array_push($informations, $systemLogInformations);
        }
        return $informations;
    }

    /**
     * Test si la commande pip est installée sur le système
     *
     * @return true si pip a été trouvé
     */
    public function isPipInstalled()
    {
        $result = false;
        // La commande pip --version met trop de temps à se lancer et ralentie le
        // chargement de la page
        $cmdReturn = \exec('whereis pip');
        // Test si il y a bien un chemin, en cas d'erreur, la commande renvoie pip:
        if (\strlen($cmdReturn) > 5) {
            $result = true;
        }
        return $result;
    }

    /**
     * Test si le module de csscompressor est installé
     *
     * @return bool true si csscompressor est installé
     */
    public function isCssCompressorInstalled()
    {
        return $this->testPipPackage('csscompressor');
    }

    /**
     * Test si le module de jsmin est installé
     *
     * @return bool true si jsmin est installé
     */
    public function isJsMinInstalled()
    {
        return $this->testPipPackage('jsmin');
    }

    /**
     * Test si un module python est installé
     *
     * @param string $name Nom du module
     *
     * @return bool true si le module est installé
     */
    public function testPipPackage($name)
    {
        $result = false;
        // Test du lancement du module
        // La commande pip list est trop longue à s'initialiser
        // python -m pose problème avec jsmin qui plante sans argument
        \exec('python -c "import ' . $name . '"', $output, $returnCode);
        if ($returnCode == 0) {
            $result = true;
        }
        return $result;
    }

    /**
     * Installe des éléments nécessaires au fonctionnement du plugin
     *
     * @param string $item Elément à installer
     *
     * @return bool true si l'installation a réussie
     */
    public function install($item)
    {
        $result = false;
        switch ($item) {
            case 'csscompressor':
                $result = $this->pipInstall('csscompressor');
                break;
            case 'jsmin':
                $result = $this->pipInstall('jsmin');
                break;
        }
        return $result;
    }

    /**
     * Minifie un type de documents
     *
     * @param string $item Type de minification
     *
     * @return bool true si la minification a été reconnue et effectuée
     */
    public function minify($item)
    {
        $result = true;
        switch ($item) {
            case 'csscompressor':
                $fileList = $this->findFilesRecursively($this->getJeedomRootDirectory(), 'css');
                $this->minifyCss($fileList);
                break;
            case 'jsmin':
                $fileList = $this->findFilesRecursively($this->getJeedomRootDirectory(), 'js');
                $this->minifyJavascript($fileList);
                break;
        }
        return $result;
    }

    /**
     * Installe un module python avec pip
     *
     * @param string $packageName Nom du module
     *
     * @return bool true si l'installation a réussie
     */
    private function pipInstall($packageName)
    {
        $result = false;
        \exec(system::getCmdSudo() . ' pip install ' . $packageName, $output, $resultCode);
        if ($resultCode == 0) {
            $result = true;
        }
        return $result;
    }

    /**
     * Minifie une liste de fichiers CSS
     *
     * @param array $fileList Liste des fichiers
     *
     * @return int Nombre de fichiers minifiés
     */
    private function minifyCss($fileList)
    {
        $mininfiedFiles = 0;
        if ($this->isCssCompressorInstalled()) {
            foreach ($fileList as $file) {
                $fileHash = $this->getHashFile($file);
                if ($this->isFileNotBeMinify($file, $fileHash)) {
                    \exec('python -m csscompressor ' . $file . ' -o ' . $file);
                    $this->storeFileHash($file);
                    ++$mininfiedFiles;
                }
            }
        }
        return $mininfiedFiles;
    }

    /**
     * Minifie une liste de fichiers Javascript
     *
     * @param array $fileList Liste de fichiers
     *
     * @return int Nombre de fichiers minifiés
     */
    private function minifyJavascript($fileList)
    {
        $mininfiedFiles = 0;
        if ($this->isJsMinInstalled()) {
            if (is_writable('/tmp/optimize_tmp.js')) {
                foreach ($fileList as $file) {
                    if (!strstr($file, 'node_modules')) {
                        $fileHash = $this->getHashFile($file);
                        if (is_writable($file)) {
                            if ($this->isFileNotBeMinify($file, $fileHash)) {
                                \exec('python -m jsmin ' . $file . ' > /tmp/optimize_tmp.js');
                                \exec('cp /tmp/optimize_tmp.js ' . $file);
                                $this->storeFileHash($file);
                                ++$mininfiedFiles;
                            }
                        }
                    }
                }
            }
            if (file_exists('/tmp/optimize_tmp.js')) {
                unlink('/tmp/optimize_tmp.js');
            }
        }
        return $mininfiedFiles;
    }

    /**
     * Vérifie si le fichier n'a pas déjà été minifié
     *
     * @param string $filePath Chemin du fichier
     * @param string $fileHash Hash actuel
     *
     * @return bool True si le fichier n'a pas été minifié.
     */
    protected function isFileNotBeMinify($filePath, $fileHash)
    {
        $result = false;
        $dbValue = $this->dataStorage->getRawData($filePath);
        if ($dbValue !== $fileHash) {
            $result = true;
        }
        return $result;
    }

    /**
     * Stocke le hash d'un fichier
     *
     * @param string $filePath Chemin du fichier
     */
    protected function storeFileHash($filePath)
    {
        $fileHash = $this->getHashFile($filePath);
        $this->dataStorage->storeRawData($filePath, $fileHash);
    }

    /**
     * Obtenir le hash d'un fichier
     *
     * @param string $filePath Chemin du fichier
     * @return string   Hash du fichier
     */
    protected function getHashFile($filePath)
    {
        return md5_file($filePath);
    }

    /**
     * Recherche des fichiers dans un répertoire recursivement en fonction de son extension
     *
     * @param string $path Répertoire racine
     * @param string $extension Extension
     *
     * @return array Liste des fichiers
     */
    protected function findFilesRecursively($path, $extension)
    {
        $files = array();
        if (!file_exists($path . '/.optimize-ignore')) {
            $itemDirectoryIterator = new \RecursiveDirectoryIterator($path);
            foreach ($itemDirectoryIterator as $file) {
                $filename = $file->getFilename();
                if ($filename != '.' && $filename != '..') {
                    if ($file->isDir()) {
                        $files = \array_merge($files, $this->findFilesRecursively($file->getPathName(), $extension));
                    }
                    if (\pathinfo($filename, PATHINFO_EXTENSION) == $extension) {
                        \array_push($files, $file->getPathName());
                    }
                }
            }
        }
        return $files;
    }

    public function disableLogs($systemLogId)
    {
        if ($systemLogId == 'optimize-all') {
            foreach ($this->systemLogs as $id => $name) {
                $this->disableSystemLog($id);
            }
        } else {
            $this->disableSystemLog($systemLogId);
        }
    }

    /**
     * Désactiver les logs d'un service.
     *
     * @param integer $systemLogId Identifiant du scénario
     */
    public function disableSystemLog($systemLogId)
    {
        $systemLogConfig = config::byKey('log::level::' . $systemLogId);
        foreach ($systemLogConfig as $key => $value) {
            if ($value != 0) {
                $systemLogConfig[$key] = "0";
            }
        }
        $systemLogConfig[1000] = "1";
        config::save('log::level::' . $systemLogId, $systemLogConfig);
    }
}
