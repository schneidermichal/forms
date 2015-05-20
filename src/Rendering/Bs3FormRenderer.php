<?php

/**
 * This file is part of the Nextras community extensions of Nette Framework
 *
 * @license    New BSD License
 * @link       https://github.com/nextras/forms
 * @author     Jan Skrasek
 */

namespace Nextras\Forms\Rendering;

use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Forms\Controls;
use Nette\Forms\Form;
use Nette;
use Nette\Utils\Html;


/**
 * FormRenderer for Bootstrap 3 framework.
 * @author   Jan Skrasek
 * @author   David Grudl
 */
class Bs3FormRenderer extends DefaultFormRenderer
{
	/** @var Controls\Button */
	public $primaryButton = NULL;

	/** @var bool */
	private $controlsInit = FALSE;
	
	/** @var bool */
	private $displayLabels = TRUE;
	
	/** @var int */
        private $labelSize = 3;
        
        /** @var string */
        private $controlContainer = 'div class=col-sm-%d';
        
        /** @var string */
        private $labelContainer = 'div class="col-sm-%d control-label"';

	public function __construct()
	{
		$this->setUpContainers();
	}
	
	
	private function setUpContainers()
	{
		$this->wrappers['controls']['container'] = NULL;
		$this->wrappers['pair']['container'] = 'div class=form-group';
		$this->wrappers['pair']['.error'] = 'has-error';
		$this->wrappers['control']['container'] = sprintf($this->controlContainer, 12 - $this->labelSize);
		$this->wrappers['label']['container'] = sprintf($this->labelContainer, $this->labelSize);
		$this->wrappers['control']['description'] = 'span class=help-block';
		$this->wrappers['control']['errorcontainer'] = 'span class=help-block';
	}
	
	
	public function displayLabels($displayLabels)
	{
		$this->displayLabels = $displayLabels;
	}
	
	
	public function setLabelSize($labelSize)
	{
		$this->labelSize = $labelSize;
		$this->setUpContainers();
	}


	public function renderBegin()
	{
		$this->controlsInit();
		return parent::renderBegin();
	}


	public function renderEnd()
	{
		$this->controlsInit();
		return parent::renderEnd();
	}


	public function renderBody()
	{
		$this->controlsInit();
		return parent::renderBody();
	}


	public function renderControls($parent)
	{
		$this->controlsInit();
		return parent::renderControls($parent);
	}


	public function renderPair(Nette\Forms\IControl $control)
	{
		$this->controlsInit();
		return parent::renderPair($control);
	}


	public function renderPairMulti(array $controls)
	{
		$this->controlsInit();
		return parent::renderPairMulti($controls);
	}


	public function renderLabel(Nette\Forms\IControl $control)
	{
		if ($this->displayLabels) {
			$this->controlsInit();
			return parent::renderLabel($control);
		} else {
			return Html::el();
		}
	}


	public function renderControl(Nette\Forms\IControl $control)
	{
		$this->controlsInit();
		return parent::renderControl($control);
	}


	private function controlsInit()
	{
		if ($this->controlsInit) {
			return;
		}

		$this->controlsInit = TRUE;
		$this->form->getElementPrototype()->addClass('form-horizontal');
		foreach ($this->form->getControls() as $control) {
			if ($control instanceof Controls\Button) {
				$markAsPrimary = $control === $this->primaryButton || (!isset($this->primary) && empty($usedPrimary) && $control->parent instanceof Form);
				if ($markAsPrimary) {
					$class = 'btn btn-primary';
					$usedPrimary = TRUE;
				} else {
					$class = 'btn btn-default';
				}
				$control->getControlPrototype()->addClass($class);

			} elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
				$control->getControlPrototype()->addClass('form-control');

			} elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
				if ($control->getSeparatorPrototype()->getName() !== NULL) {
					$control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
				} else {
					$control->getItemLabelPrototype()->addClass($control->getControlPrototype()->type . '-inline');
				}
			}
		}
	}

}
