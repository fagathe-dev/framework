<?php
namespace Fagathe\Framework\Form\Field;

use Fagathe\Framework\Form\Field\AbstractField;
use Fagathe\Framework\Form\Field\Label;

final class TextareaField extends AbstractField
{

    protected const CUSTOM_FIELD_ATTRIBUTES = ['name', 'list', 'placeholder', 'choices', 'onkeyup', 'oninput', 'minlength', 'maxlengeth', 'size'];

    public function render(): string
    {
        return '<div class="form-group">' . ($this->label !== null ? (new Label($this->label, $this->name, ['class' => 'form-label']))->render() : '') . '<textarea id="' . $this->getName() . '"' . $this->getHTMLAttributes() . '>' . $this->getData() . '</textarea></div>';
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->getAttribute('value', '');
    }

}