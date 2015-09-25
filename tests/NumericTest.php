<?php
/**
 *  Copyright 2015 net2grid B.V.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */


namespace Net2Grid\Conversion;


use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

class NumericTest extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function dashesAreStrippedFromHexString() {

		$hexStringWithDashDelimiters = "41-e8-4a";
		$convertedFromStringWithDashes = Numeric::decimalFromHex($hexStringWithDashDelimiters);

		$expected = "4319306";
		$this->assertEquals($expected, $convertedFromStringWithDashes);
	}

	/**
	 * @test
	 */
	public function dotsAreStrippedFromHexString() {

		$hexStringWithDotDelimiters = "41.e8.4a";
		$convertedFromStringWithDots = Numeric::decimalFromHex($hexStringWithDotDelimiters);

		$expected = "4319306";
		$this->assertEquals($expected, $convertedFromStringWithDots);
	}

	/**
	 * @test
	 */
	public function largeHexStringIsConvertedToDecimalString() {
		$largeHexString = "41e84a4ac022fb773d4ab14bc4c121ea";

		$converted = Numeric::decimalFromHex($largeHexString);

		$expected = "87605939417857720904870321048504050154";
		$this->assertEquals($expected, $converted);
	}

	/**
	 * @test
	 */
	public function colonsAreStrippedFromHexString() {

		$hexStringWithColonDelimiters = "41:e8:4a";
		$convertedFromStringWithColons = Numeric::decimalFromHex($hexStringWithColonDelimiters);

		$expected = "4319306";
		$this->assertEquals($expected, $convertedFromStringWithColons);
	}

	/**
	 * @test
	 */
	public function whitespaceIsStrippedFromHexString() {
		$hexStringWithWhiteSpace = "aa16 3eff fffb 0a30";
		$convertedFromStringWithWhiteSpace = Numeric::decimalFromHex($hexStringWithWhiteSpace);
		$expected = "12256052705167608368";
		$this->assertEquals($expected, $convertedFromStringWithWhiteSpace);
	}

	/**
	 * @test
	 */
	public function leading0xIsStrippedFromHexString() {

		$hexStringWithLeading0x = "0x0016 3eff fffb 0a30";
		$convertedFromStringWithDots = Numeric::decimalFromHex($hexStringWithLeading0x);

		$expected = "6261718719859248";
		$this->assertEquals($expected, $convertedFromStringWithDots);
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function nonHexCharsAreRejected() {
		$hexStringWithNonHexCharacter = "41e84a4ac022fb773d4ab14bc4c121ea1e:g:84a4:h:ac022";

		Numeric::decimalFromHex($hexStringWithNonHexCharacter);

	}

	/**
	 * @test
	 */
	public function largeDecimalStringIsConvertedToHex() {
		$largeDecimalString = "87605939417857720904870321048504050154";

		$converted = Numeric::hexFromDecimal($largeDecimalString);

		$expected = "41e84a4ac022fb773d4ab14bc4c121ea";
		$this->assertEquals($expected, $converted);
	}

}