<?php
namespace Fagathe\Framework\Form;

use Fagathe\Framework\Form\AbstractField;
use Fagathe\Framework\Form\Label;

final class SelectField extends AbstractField
{

    private const CUSTOM_FIELD_ATTRIBUTES = ['name', 'list', 'placeholder', 'choices', 'multiple', 'selected'];
    private const DEFAULT_PLACEHOLDER_LABEL = '---- Selectionnez une valeur ----';

    public function render(): string
    {
        $options = '';
        if ($this->getAttribute('choices') && is_array($this->getAttributes()['choices'])) {
            $placeholder = $this->getAttribute('placeholder');
            $choices = $this->getAttribute('choices');
            if (!is_null($placeholder)) {
                if (is_bool($placeholder) && $placeholder === true) {
                    $options = '<option value="">' . self::DEFAULT_PLACEHOLDER_LABEL . '</option>';
                }
                if (is_string($placeholder)) {
                    $options = '<option value="">' . $placeholder . '</option>';
                }
            }
            foreach ($choices as $k => $v) {
                $options .= '<option value="' . $k . '"' . ($k === $this->getAttribute('value') ? ' selected' : '') . '>' . $v . '</option>';
            }
        }
        return '<div class="form-group">' . ($this->label !== null ? (new Label($this->label, $this->name, ['class' => 'form-label']))->render() : '') . '<select class="form-select" id="' . $this->getName() . '"' . $this->getHTMLAttributes() . '>' . $options . '</select></div>';
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->getAttribute('value', '');
    }

}