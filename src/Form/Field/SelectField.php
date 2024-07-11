<?php
namespace Fagathe\Framework\Form\Field;

use Fagathe\Framework\Form\Field\AbstractField;
use Fagathe\Framework\Form\Field\Label;

final class SelectField extends AbstractField
{

    protected const CUSTOM_FIELD_ATTRIBUTES = ['name', 'list', 'placeholder', 'choices', 'multiple', 'selected'];
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
                $val = $this->getData();
                $selected = false;
                if (is_array($val)) {
                    $selected = in_array($k, $val);
                } else {
                    $selected = $k === $val;
                }
                $options .= '<option value="' . $k . '"' . ($selected ? 'selected' : '') . '>' . $v . '</option>';
            }
        }
        return '<div class="form-group">' . ($this->label !== null ? (new Label($this->label, $this->name, ['class' => 'form-label']))->render() : '') . '<select class="form-select" id="' . $this->getName() . '"' . $this->getHTMLAttributes() . '>' . $options . '</select></div>';
    }

    /**
     * @return null|string|array
     */
    public function getData(): mixed
    {
        $value = $this->getAttribute('value', '');
        if (is_string($value)) {
            $encodedValue = json_decode($value);
            $value = is_array($encodedValue) ? $encodedValue : $value;
        }

        return $value;
    }

}