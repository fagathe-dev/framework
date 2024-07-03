<?php
namespace Fagathe\Framework\Form;

final class Form
{

    private const ALLOWED_FORM_ATTRIBUTES = ['action', 'method', 'name', 'onsubmit', 'autocomplete', 'enctype',];

    /**
     * @var array
     */
    protected array $htmlAttributes = [];

    private array $mappedFields = [];

    public function __construct(public string $name = '', public array $fields = [], public array $attributes = [])
    {
        $this->mappedFields();
    }

    public function mappedFields(): void
    {
        foreach ($this->getFields() as $field) {
            if (!array_key_exists($field->getName(), $this->mappedFields)) {
                $this->mappedFields[$field->getName()] = $field;
            }
        }

        return;
    }

    /**
     * @param string $htmlAttribute
     * 
     * @return self
     */
    protected function addHTMLAttributes(?string $htmlAttribute): self
    {
        $this->htmlAttributes[] = $htmlAttribute;

        return $this;
    }

    /**
     * @return string
     */
    protected function getHTMLAttributes(): string
    {
        foreach ($this->getAttributes() as $name => $value) {
            $this->addHTMLAttributes($name . '="' . $value . '"');
        }

        return count($this->htmlAttributes) ? ' ' . join(' ', $this->htmlAttributes) : '';
    }

    /**
     * @param object $field
     * 
     * @return self
     */
    public function add(object $field): self
    {
        $this->fields[] = $field;
        $this->mappedFields();
        return $this;
    }

    /**
     * @param string $attribute
     * 
     * @return mixed
     */
    public function getAttribute(string $attribute): mixed
    {
        return $this->attributes[$attribute];
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $attribute
     * 
     * @return bool
     */
    public function isAllowedAttributes(string $attribute): bool
    {
        return in_array($attribute, self::ALLOWED_FORM_ATTRIBUTES);
    }

    /**
     * @return string
     */
    public function start(): string
    {
        return '<form' . $this->getHTMLAttributes() . ">";
    }

    public function widget(string $name): string
    {
        return $this->mappedFields[$name]->render();
    }

    /**
     * @return string
     */
    public function end(): string
    {
        return '</form>';
    }


    /**
     * Get the value of fields
     */
    public function getFields()
    {
        return $this->fields;
    }
}