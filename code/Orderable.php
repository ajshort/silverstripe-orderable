<?php
/**
 * A simple extension to add a sort field to an object.
 *
 * @package silverstripe-orderable
 */
class Orderable extends DataObjectDecorator {

	public function extraStatics() {
		return array('db' => array('Sort' => 'Int'));
	}

	public function augmentSQL($query) {
		if ($query->orderby)
			return;
		
		// Only add the "orderby" clause if we can find the "Sort"
		// column for a table involved in this query.
		$db = DB::getConn();
		$fields = array();
		if ($query->from) foreach ($query->from as $table => $ignored) {
			$fields = array_merge($fields, $db->fieldList($table));
		}
		if (array_key_exists('Sort', $fields))
			$query->orderby('"Sort"');
	}

	public function onBeforeWrite() {
		if (!$this->owner->Sort) {
			$max = DB::query(sprintf(
				'SELECT MAX("Sort") + 1 FROM "%s"', $this->ownerBaseClass
			));
			$this->owner->Sort = $max->value();
		}
	}

	public function updateCMSFields($fields) {
		$fields->removeByName('Sort');
	}

	public function updateFrontEndFields($fields) {
		$fields->removeByName('Sort');
	}

}
