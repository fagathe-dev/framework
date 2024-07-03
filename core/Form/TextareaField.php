<?php
namespace Fagathe\Framework\Form;

use Fagathe\Framework\Form\AbstractField;
use Fagathe\Framework\Form\Label;

final class TextareaField extends AbstractField
{

    private const CUSTOM_FIELD_ATTRIBUTES = ['name', 'onclick', 'list', 'placeholder', 'choices', 'onkeyup', 'oninput', 'minlength', 'maxlengeth', 'size'];

    public function render(): string
    {
        $value ='';
        if ($this->getAttribute('value')) {
            $value = $this->getAttribute('value');
        }
        return '<div class="form-group">' . ($this->label !== null ? (new Label($this->label, $this->name, ['class' => 'form-label']))->render() : '') . '<textarea type="text" id="' . $this->getName() . '"' . $this->getHTMLAttributes() . '>'. $value.'</textarea></div>';
    }

    public function getData(): mixed
    {

    }

}