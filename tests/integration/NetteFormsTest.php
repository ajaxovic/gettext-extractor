<?php

namespace Webwings\Gettext\Extractor\Filters;

use Tester\TestCase;

require_once ('./../../vendor/autoload.php');

/**
 * @author Ondřej Vodáček
 */
class NetteFormsTest extends TestCase {

	/** @var GettextExtractor_NetteExtractor */
	protected $object;

	protected function setUp() {
		$this->object = new GettextExtractor_NetteExtractor('/dev/null');
		$this->object->setMeta('POT-Creation-Date', '2013-02-11T14:18:02+01:00');
	}

	public function testForm() {
		$this->object->setupForms();
		$this->object->scan('tests/integration/data/form.php');
		$temp = tempnam(sys_get_temp_dir(), __CLASS__);
		$this->object->save($temp);
		$this->assertFileEquals(__DIR__.'/data/form.pot', $temp);
	}
}
