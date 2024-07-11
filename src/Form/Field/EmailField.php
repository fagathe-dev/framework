<?php
namespace Fagathe\Framework\Form\Field;

use Fagathe\Framework\Form\Field\Label;
use Fagathe\Framework\Form\Field\AbstractField;

final class EmailField extends AbstractField
{

    private const CUSTOM_FIELD_ATTRIBUTES = ['name', 'list', 'placeholder', 'choices', 'onkeyup', 'oninput', 'minlength', 'maxlengeth', 'size'];

    public function render(): string
    {
        return '<div class="form-group">' . ($this->label !== null ? (new Label($this->label, $this->name, ['class' => 'form-label']))->render() : '') . '<input type="email" id="' . $this->getName() . '"' . $this->getHTMLAttributes() . '></div>';
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->getAttribute('value', '');
    }

}