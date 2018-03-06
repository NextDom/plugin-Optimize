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

class OptimizeSystem extends BaseOptimize
{
    /**
     * @var array Tableau des différents logs système
     */
    private $systemLogs = array(
        'scenario'   => 'Scenario',
        'plugin'     => 'Plugin',
        'market'     => 'Market',
        'api'        => 'Api',
        'connection' => 'Connection',
        'interact'   => 'Interact',
        'tts'        => 'TTS',
        'report'     => 'Report'
    );

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
                if ($value == 1 && $logType != 1000) {
                    $systemLogInformations['log'] = true;
                }
            }
            $rating = $this->rateSystemLogInformations($systemLogInformations);
            $systemLogInformations['rating'] = $rating;
            array_push($informations, $systemLogInformations);
        }
        return $informations;
    }

    /**
     * Test si la commande pip est installée sur le système
     */
    public function canPip() {
        $return = false;
        // La commande pip --version met trop de temps à se lancer et ralentie le
        // chargement de la page
        $cmdReturn = exec('whereis pip');
        // Test si il y a bien un chemin, en cas d'erreur, la commande renvoie pip:
        if (\strlen($cmdReturn) > 5) {
            $return = true;
        }
        return $return;
    }
    
    /**
     * Désactiver les logs d'un service.
     *
     * @param integer $systemLogId Identifiant du scénario
     */
    public function disableLogs($systemLogId)
    {
        $systemLogConfig = config::byKey('log::level::' . $systemLogId);
        foreach ($systemLogConfig as $key => $value) {
            if ($value != 0) {
                $systemLogConfig[$key] = 0;
            }
        }
        $systemLogConfig[1000] = 1;
        config::save('log::level::' . $systemLogId, $systemLogConfig);
    }
}
