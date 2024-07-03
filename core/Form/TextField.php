<?php
namespace Fagathe\Framework\Form;

use Fagathe\Framework\Form\AbstractField;
use Fagathe\Framework\Form\Label;

final class TextField extends AbstractField
{

    private const CUSTOM_FIELD_ATTRIBUTES = ['name', 'onclick', 'list', 'placeholder', 'choices', 'onkeyup', 'oninput', 'minlength', 'maxlengeth', 'size'];

    /**
     * @return string
     */
    public function getHTMLValue(): string
    {
        if ($this->value === null) {
            return '';
        }
        if ($this->getAttribute('value', false)) {
            $value = $this->getAttribute('value');
        } else {
            $value = $this->value;
        }
        return ' value="' . $value . '"';
    }

    public function render(): string
    {
        $extraHTML = '';
        if ($this->getAttribute('choices') && is_array($this->getAttributes()['choices'])) {
            $extraHTML .= '<datalist id="' . $this->getAttribute('list') . '">';
            foreach ($this->getAttributes()['choices'] as $key => $value) {
                $extraHTML .= '<option value="' . $value . '"></option>';
            }
            $extraHTML .= '</datalist>';
        }
        return '<div class="form-group">' . ($this->label !== null ? (new Label($this->label, $this->name, ['class' => 'form-label']))->render() : '') . '<input type="text" id="' . $this->getName() . '"' . $this->getHTMLValue() . $this->getHTMLAttributes() . '>' . $extraHTML . '</div>';
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->getAttribute('value', '');
    }

}