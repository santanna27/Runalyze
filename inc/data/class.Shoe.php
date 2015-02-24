<?php
/**
 * This file contains class::Shoe
 * @package Runalyze\DataObjects\Shoe
 */

use Runalyze\Activity\Distance;
use Runalyze\Activity\Duration;

/**
 * Data object for a shoe
 * @author Hannes Christiansen
 * @package Runalyze\DataObjects\Shoe
 */
class Shoe extends DataObject {
	/**
	 * Init DatabaseScheme 
	 */
	protected function initDatabaseScheme() {
		$this->DatabaseScheme = DatabaseSchemePool::get('training/schemes/scheme.Shoe.php');
	}

	/**
	 * Tasks to perform after insert
	 */
	protected function tasksAfterInsert() {
		// TODO: 'update' cache
		ShoeFactory::reInitAllShoes();
	}

	/**
	 * Tasks to perform after update
	 */
	protected function tasksAfterUpdate() {
		// TODO: 'update' cache
		ShoeFactory::reInitAllShoes();
	}

	/**
	* Get name
	* @return string
	*/
	public function getName() {
		return $this->get('name');
	}

	/**
	 * Get search link
	 * @return string
	 */
	public function getSearchLink() {
		if (Request::isOnSharedPage()) {
			return $this->getName();
		}

		return ShoeFactory::getSearchLink($this->id());
	}
	
	/**
	* Get since
	* @return string
	*/
	public function getSince() {
		return $this->get('since');
	}

	/**
	* Get time
	* @return int
	*/
	public function getTime() {
		return $this->get('time');
	}

	/**
	 * Get string for time
	 * @return string
	 */
	public function getTimeString() {
		return Duration::format($this->getTime());
	}

	/**
	* Get km
	* @return float
	*/
	public function getKm() {
		return $this->getKmInDatabase() + $this->getAdditionalKm();
	}

	/**
	 * Get string for km
	 * @return string
	 */
	public function getKmString() {
		return Distance::format($this->getKm());
	}

	/**
	 * Get string for weight
	 * @return string
	 */
	public function getWeightString() {
		if ($this->getWeight() > 0) {
			return $this->getWeight().FormularUnit::$G;
		}

		return '';
	}

	/**
	* Get km from trainings in database
	* @return float
	*/
	public function getKmInDatabase() {
		return $this->get('km');
	}

	/**
	* Get additional km
	* @return float
	*/
	public function getAdditionalKm() {
		return $this->get('additionalKm');
	}

	/**
	* Is this shoe in use?
	* @return boolean
	*/
	public function isInUse() {
		return $this->get('inuse') == 1;
	}
        
	/**
	* Get weight
	* @return float
	*/
	public function getWeight() {
		return $this->get('weight');
	}

}