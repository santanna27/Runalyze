<?php

namespace Runalyze\Model\Activity;

/**
 * Generated by hand
 */
class ObjectTest extends \PHPUnit_Framework_TestCase {

	public function testEmptyObject() {
		$A = new Object();

		$this->assertTrue($A->weather()->isEmpty());
		$this->assertTrue($A->weather()->temperature()->isUnknown());
		$this->assertTrue($A->weather()->condition()->isUnknown());

		$this->assertTrue($A->splits()->isEmpty());
		$this->assertTrue($A->partner()->isEmpty());
	}

	public function testEmptyTemperature() {
		$A = new Object(array(
			Object::TEMPERATURE => null
		));
		$B = new Object(array(
			Object::TEMPERATURE => ''
		));

		$this->assertTrue($A->weather()->temperature()->isUnknown());
		$this->assertTrue($B->weather()->temperature()->isUnknown());
	}

}
