<?php namespace App\Contracts;

/**
 * Interface Person
 * @package App\Services\Contracts
 */
interface HeightAndWeightMeasurable
{

	/**
	 * @return int
	 */
	public function getHeightInFeet();

	/**
	 * @return int
	 */
	public function getHeightInInches();

	/**
	 * @return int
	 */
	public function getWeightInLbs();
}
