<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Form\Field;

use FOF30\Form\FieldInterface;
use FOF30\Form\Form;
use FOF30\Model\DataModel;

defined('_JEXEC') or die;

\JFormHelper::loadFieldClass('timezone');

/**
 * Form Field class for FOF
 * Supports a generic list of options.
 */
class Timezone extends \JFormFieldTimezone implements FieldInterface
{
	/**
	 * @var  string  Static field output
	 */
	protected $static;

	/**
	 * @var  string  Repeatable field output
	 */
	protected $repeatable;

	/**
	 * The Form object of the form attached to the form field.
	 *
	 * @var    Form
	 */
	protected $form;

	/**
	 * A monotonically increasing number, denoting the row number in a repeatable view
	 *
	 * @var  int
	 */
	public $rowid;

	/**
	 * The item being rendered in a repeatable form field
	 *
	 * @var  DataModel
	 */
	public $item;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   2.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'static':
				if (empty($this->static))
				{
					$this->static = $this->getStatic();
				}

				return $this->static;
				break;

			case 'repeatable':
				if (empty($this->repeatable))
				{
					$this->repeatable = $this->getRepeatable();
				}

				return $this->repeatable;
				break;

			default:
				return parent::__get($name);
		}
	}

	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getStatic()
	{
		if (isset($this->element['legacy']))
		{
			return $this->getInput();
		}

		$options = array(
			'id' => $this->id
		);

		return $this->getFieldContents($options);
	}

	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getRepeatable()
	{
		if (isset($this->element['legacy']))
		{
			return $this->getInput();
		}

		$options = array(
			'class' => $this->id
		);

		return $this->getFieldContents($options);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @param   array   $fieldOptions  Options to be passed into the field
	 *
	 * @return  string  The field HTML
	 */
	public function getFieldContents(array $fieldOptions = array())
	{
		$id    = isset($fieldOptions['id']) ? $fieldOptions['id'] : null;
		$class = $this->class . (isset($fieldOptions['class']) ? ' ' . $fieldOptions['class'] : '');

		$selected = GroupedList::getOptionName($this->getGroups(), $this->value);

		if (is_null($selected))
		{
			$selected = array(
				'group'	 => '',
				'item'	 => ''
			);
		}

		return '<span ' . ($id ? 'id="' . $id . '-group" ' : '') . 'class="fof-groupedlist-group ' . $class . '>' .
			htmlspecialchars($selected['group'], ENT_COMPAT, 'UTF-8') .
			'</span>' .
			'<span ' . ($id ? 'id="' . $id . '-item" ' : '') . 'class="fof-groupedlist-item ' . $class . '>' .
			htmlspecialchars($selected['item'], ENT_COMPAT, 'UTF-8') .
			'</span>';
	}
}
