<?php

namespace Webwings\Gettext\Extractor\Filters;

use Webwings\Gettext\Extractor\Extractor;

require_once dirname(__FILE__).'/FilterTest.php';

/**
 * Test class for PHPFilter.
 * Generated by PHPUnit on 2010-12-15 at 21:59:45.
 */
class PHPFilterTest extends FilterTest {

	protected function setUp() {
		error_reporting(-1);
		$this->object = new GettextExtractor_Filters_PHPFilter();
		$this->object->addFunction('addRule', 2);
		$this->file = dirname(__FILE__) . '/../../data/default.php';
	}

	public function testFunctionCallWithVariables() {
		$messages = $this->object->extract($this->file);

		$this->assertNotContains(array(
			Extractor::LINE => 7
		), $messages);

		$this->assertNotContains(array(
			Extractor::LINE => 8,
			Extractor::CONTEXT => 'context'
		), $messages);

		$this->assertNotContains(array(
			Extractor::LINE => 9,
			Extractor::SINGULAR => 'I see %d little indian!',
			Extractor::PLURAL => 'I see %d little indians!'
		), $messages);
	}

	public function testNestedFunctions() {
		$messages = $this->object->extract($this->file);

		$this->assertNotContains(array(
			Extractor::LINE => 11,
			Extractor::SINGULAR => 'Some string.'
		), $messages);

		$this->assertContains(array(
			Extractor::LINE => 12,
			Extractor::SINGULAR => 'Nested function.'
		), $messages);

		$this->assertContains(array(
			Extractor::LINE => 13,
			Extractor::SINGULAR => 'Nested function 2.',
			Extractor::CONTEXT => 'context'
		), $messages);
		$this->assertNotContains(array(
			Extractor::LINE => 13,
			Extractor::SINGULAR => 'context'
		), $messages);

		$this->assertContains(array(
			Extractor::LINE => 14,
			Extractor::SINGULAR => "%d meeting wasn't imported.",
			Extractor::PLURAL => "%d meetings weren't importeded."
		), $messages);
		$this->assertNotContains(array(
			Extractor::LINE => 14,
			Extractor::SINGULAR => "%d meeting wasn't imported."
		), $messages);

		$this->assertContains(array(
			Extractor::LINE => 17,
			Extractor::SINGULAR => "Please provide a text 2."
		), $messages);
		$this->assertContains(array(
			Extractor::LINE => 18,
			Extractor::SINGULAR => "Please provide a text 3."
		), $messages);
	}

	public function testConstantAsParameter() {
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Extractor::LINE => 16,
			Extractor::SINGULAR => "Please provide a text."
		), $messages);
	}

	public function testMessageWithNewlines() {
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Extractor::LINE => 22,
			Extractor::SINGULAR => "A\nmessage!"
		), $messages);
	}

	public function testArrayAsParameter() {
		$this->object->addFunction('addConfirmer', 3);
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Extractor::LINE => 25,
			Extractor::SINGULAR => "Really delete?"
		), $messages);
	}

	public function testSingularAndPluralMessageFromOneParameter() {
		$this->object->addFunction('plural', 1, 1);
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Extractor::LINE => 33,
			Extractor::SINGULAR => "%d weeks ago",
			Extractor::PLURAL => "%d weeks ago",
		), $messages);
	}

	/**
	 * @group bug5
	 */
	public function testArrayWithTranslationsAsParameter() {
		$this->object->addFunction('addSelect', 3);
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Extractor::LINE => 26,
			Extractor::SINGULAR => "item 1"
		), $messages);
		$this->assertContains(array(
			Extractor::LINE => 26,
			Extractor::SINGULAR => "item 2"
		), $messages);
	}

	/**
	 * @group bug3
	 */
	public function testMultipleMessagesFromSingleFunction() {
		$this->object->addFunction('bar', 1);
		$this->object->addFunction('bar', 2);
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Extractor::LINE => 30,
			Extractor::SINGULAR => "Value A"
		), $messages);
		$this->assertContains(array(
			Extractor::LINE => 30,
			Extractor::SINGULAR => "Value B"
		), $messages);
	}

	public function testCallable() {
		$messages = $this->object->extract(dirname(__FILE__) . '/../../data/callable.php');
		$this->assertEmpty($messages);
	}

	public function testStaticFunctions() {
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Extractor::LINE => 31,
			Extractor::SINGULAR => "Static function"
		), $messages);
	}

	/**
	 * @group bug11
	 */
	public function testNoMessagesInArray() {
		$this->object->addFunction('translateArray');
		$messages = $this->object->extract(dirname(__FILE__) . '/../../data/bug11.php');
		$this->assertEmpty($messages);
	}
}
