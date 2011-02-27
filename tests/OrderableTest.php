<?php
/**
 * @package    silverstripe-orderable
 * @subpackage tests
 */
class OrderableTest extends SapphireTest {

	/**
	 * Tests that versioned delete operations work with the orderable decorator.
	 */
	public function testVersionedOrderableDelete() {
		$sub = new OrderableTest_VersionedSub();
		$sub->write();
		$sub->publish('Stage', 'Live');

		$sub->deleteFromStage('Live');
		$sub->delete();
	}

}

/**
 * @ignore
 */
class OrderableTest_VersionedBase extends DataObject {
	public static $db = array(
		'Field1' => 'Text'
	);
	public static $extensions = array(
		"Orderable",
		"Versioned('Stage', 'Live')"
	);
}

/**
 * @ignore
 */
class OrderableTest_VersionedSub extends OrderableTest_VersionedBase {
	public static $db = array(
		'Field2' => 'Text'
	);
}