<?php

include 'php_common/cleanURL.php';

class cleanURLTest extends PHPUnit_Framework_TestCase {

	public function testNoHttp() {
		$userdata = "www.example.com";
		$expected = "http://www.example.com";
		$this->assertEquals(cleanURL($userdata), $expected);
	}

	public function testHttpAlreadyHere() {
		$userdata = "http://www.example.com";
		$expected = "http://www.example.com";
		$this->assertEquals(cleanURL($userdata), $expected);
	}

	public function testHttpsAlreadyHere() {
		$userdata = "https://www.example.com";
		$expected = "https://www.example.com";
		$this->assertEquals(cleanURL($userdata), $expected);
	}

	public function testHttpInsideUrl() {
		$userdata = "www.example.com/http://garbage";
		$expected = "http://www.example.com/http://garbage";
		$this->assertEquals(cleanURL($userdata), $expected);
	}

	public function testCrossProtocolAttack() {
		$userdata = "mailto:user@example.com?subject=http://";
		$expected = "http://mailto:user@example.com?subject=http://";
		$this->assertEquals(cleanURL($userdata), $expected);
	}
}

