<?php

class OrderableTest extends SapphireTest {
	
	public static $fixture_file = 'orderable/tests/OrderableTest.yml';
	
	// Make sure that Orderable works with Versioned DataObjects.
	public function testOrderableWithVersioned() {
		$sub = new OrderableTest_VersionedSub();
		$subID = $sub->write();
		$sub->publish('Stage', 'Live');
		$sub->deleteFromStage('Live');
		$sub->delete();
	}
	
}

class OrderableTest_VersionedBase extends DataObject {
	public static $db = array(
		'Field1' => 'Text'
	);
	public static $extensions = array(
		"Orderable",
		"Versioned('Stage', 'Live')"
	);
}

class OrderableTest_VersionedSub extends OrderableTest_VersionedBase {
	public static $db = array(
		'Field2' => 'Text'
	);
}

