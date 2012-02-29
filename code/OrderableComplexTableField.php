<?php
/**
 * An extension to {@link ComplexTableField} to support drag and drop ordering.
 *
 * @package silverstripe-orderable
 */
class OrderableComplexTableField extends ComplexTableField {

	protected $showPagination = false;
	protected $orderField = 'Sort';
	protected $template = 'OrderableComplexTableField';

	/**
	 * Sets the order field, which is by default "Sort".
	 *
	 * @param string $field
	 */
	public function setOrderField($field) {
		$this->orderField = $field;
	}

	/**
	 * @return string
	 */
	public function getOrderField() {
		return $this->orderField;
	}

	/**
	 * Handles updating the order of objects.
	 */
	public function order($request) {
		$token = $this->getForm()->getSecurityToken();
		if(!$token->checkRequest($request)) return $this->httpError(400);

		$items = $this->sourceItems();
		$sort  = $this->orderField;
		$order = $request->postVar('ids');

		// Get the table the sort field exists on.
		$classes = ClassInfo::ancestry($this->sourceClass());
		foreach (array_reverse($classes) as $table) {
			if (singleton($table)->hasOwnTableDatabaseField($sort)) {
				break;
			}
		}

		// Populate each object with a sort value.
		foreach ($items as $item) if (!$item->$sort) {
			$query = DB::query("SELECT MAX(\"$sort\") + 1 FROM \"$table\"");
			$item->$sort = $query->value();
			$item->write();
		}

		// Re-order the fields, but only use existing sort values to prevent
		// conflicts with items not in this CTF.
		$values = array_values($items->map('ID', $sort));
		sort($values);

		foreach ($order as $key => $id) {
			$item = $items->find('ID', $id);
			$item->$sort = $values[$key];
			$item->write();
		}

		$items->sort($sort);
		return $this->FieldHolder();
	}

	public function FieldHolder() {
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript(SAPPHIRE_DIR . '/javascript/jquery_improvements.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-ui/jquery-ui.custom.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-ui/jquery-ui-1.8rc3.custom.js');
		Requirements::javascript('orderable/javascript/OrderableComplexTableField.js');

		return parent::FieldHolder();
	}

}