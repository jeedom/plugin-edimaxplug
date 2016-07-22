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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../3rdparty/ediplug/Locator.php';
require_once dirname(__FILE__) . '/../../3rdparty/ediplug/EdiPlug.php';
require_once dirname(__FILE__) . '/../../3rdparty/ediplug/PlugInfo.php';

class edimaxplug extends eqLogic {

    /*     * ***********************Methode static*************************** */
	
	public static function searchPlug() {
		// Create the locator
		$locator = new Locator();
		// Spend 5 seconds looking for plugs
		$plugs = $locator->scan(5);
		foreach($plugs as $plug) {
		    $eqLogic = self::byLogicalId('plug' . $plug->mac, 'edimaxplug');
			if (!is_object($eqLogic)) {
				$eqLogic = new self();
				$eqLogic->setLogicalId('plug' . $plug->mac);
				$eqLogic->setName('plug-' . $plug->mac);
				$eqLogic->setEqType_name('edimaxplug');
				$eqLogic->setIsVisible(1);
				$eqLogic->setIsEnable(1);
			}
			$eqLogic->setConfiguration('mac', $plug->mac);
			$eqLogic->setConfiguration('manufacturer', $plug->manufacturer);
			$eqLogic->setConfiguration('model', $plug->model);
			$eqLogic->setConfiguration('version', $plug->version);
			$eqLogic->setConfiguration('ip', $plug->ip_address);
			$eqLogic->setConfiguration('login', 'admin');
			$eqLogic->setConfiguration('password', '1234');
			$eqLogic->save();
		}
	}
	
	public static function updateValues() {
		foreach ( eqLogic::byType('edimaxplug', true) as $edimaxplug) {
			// Create object with address, username and password.
			$plug = new EdiPlug($edimaxplug->getConfiguration('ip'), $edimaxplug->getConfiguration('login'), $edimaxplug->getConfiguration('password'));
			$value = $plug->power;
			$cmd = $edimaxplug->getCmd(null, 'state');
			$cmd->event($value);
		}
	}
	
	public function cron5(){
		edimaxplug::updateValues();
	}
	
	public function preSave() {
        if ($this->getConfiguration('ip') == '') {
        	throw new Exception(__('L\' adresse IP ne peut etre vide',__FILE__));
        }
    }
	
	public function postSave() {
        	
        $cmd = $this->getCmd(null, 'on');
		if (!is_object($cmd)) {
			$cmd = new edimaxplugCmd();
		}
		$cmd->setName(__('ON', __FILE__));
		$cmd->setConfiguration('type', 'power');
		$cmd->setConfiguration('value', true);
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setLogicalId('on');
		$cmd->setDisplay("generic_type","ENERGY_ON");
		$cmd->setIsVisible(1);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'off');
		if (!is_object($cmd)) {
			$cmd = new edimaxplugCmd();
		}
		$cmd->setName(__('OFF', __FILE__));
		$cmd->setConfiguration('type', 'power');
		$cmd->setConfiguration('value', false);
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setLogicalId('off');
		$cmd->setDisplay("generic_type", "ENERGY_OFF");
		$cmd->setIsVisible(1);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'state');
		if (!is_object($cmd)) {
			$cmd = new edimaxplugCmd();
		}
		$cmd->setName(__('ETAT', __FILE__));
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setLogicalId('state');
		$cmd->setDisplay("generic_type", "ENERGY_STATE");
		$cmd->setIsVisible(1);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		
		/*
		$cmd = $this->getCmd(null, 'power');
		if (!is_object($cmd)) {
			$cmd = new edimaxplugCmd();
		}
		$cmd->setName(__('CONSOMMATION', __FILE__));
		$cmd->setType('info');
		$cmd->setSubType('numeric');
		$cmd->setLogicalId('power');
		$cmd->setDisplay("generic_type", "POWER");
		$cmd->setIsVisible(0);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		 */
    }
	
}

class edimaxplugCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    
    public function execute($_options = null) {
    	log::add('edimaxplug', 'info', 'Debut de l action');	
		$edimaxplug=$this->getEqLogic();
		if ($this->type == 'action') {
			$plug = new EdiPlug($edimaxplug->getConfiguration('ip'), $edimaxplug->getConfiguration('login'), $edimaxplug->getConfiguration('password'));
			$plug->power = $this->getConfiguration('value');
			sleep(1);
			edimaxplug::updateValues();			
			return true;
		}else{
			return $this->getValue();
		}
	}
}
?>
