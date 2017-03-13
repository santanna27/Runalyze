<?php

namespace Runalyze\Calculation;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2014-10-25 at 19:44:41.
 */
class MonotonyTest extends \PHPUnit_Framework_TestCase {

	public function testEmptyArray() {
		$Monotony = new Monotony(array());
		$Monotony->calculate();

		$this->assertEquals( 0, $Monotony->value() );
	}

	/**
	 * @expectedException \RuntimeException
	 */
	public function testRuntimeException() {
		$Monotony = new Monotony(array(100));
		$Monotony->value();
	}

	public function testSingleValue() {
		$Monotony = new Monotony(array(1, 1, 1, 1, 1, 1, 1));
		$Monotony->calculate();

		$this->assertEquals( Monotony::MAX, $Monotony->value() );
	}

	public function testSimpleExample() {
		$Monotony = new Monotony(array(0, 20, 10, 10, 10, 10, 10));
		$Monotony->calculate();

		$this->assertEquals( 10/sqrt(200/7), $Monotony->value() );
		$this->assertEquals( 7*10*10/sqrt(200/7), $Monotony->trainingStrain() );
	}

	public function testAnotherSimpleExample() {
		$Monotony = new Monotony(array(5, 15, 20, 20, 20, 25, 35));
		$Monotony->calculate();

		$this->assertEquals( 20/8.45, $Monotony->value(), '', 0.01 );
		$this->assertEquals( 7*20*20/8.45, $Monotony->trainingStrain(), '', 0.1 );
	}

	public function testEmptyDays() {
		$WithZeroes = new Monotony(array(0, 0, 0, 10, 20, 30, 40));
		$WithZeroes->calculate();

		$WithoutZeroes = new Monotony(array(10, 20, 30, 40));
		$WithoutZeroes->calculate();

		$this->assertEquals( $WithZeroes->value(), $WithoutZeroes->value() );
		$this->assertEquals( $WithZeroes->trainingStrain(), $WithoutZeroes->trainingStrain() );
	}

}