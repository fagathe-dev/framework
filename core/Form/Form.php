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
        $this->setUpAtributeFields();
        $this->mappedFields();
    }

    private function setUpAtributeFields(): void
    {
        if ($this->getAttribute('method') === null) {
            $this->setAttribute('method', 'POST');
        }
    }

    public function prefixFieldNames(): void
    {
        if ($this->getName() !== '') {
            foreach ($this->getFields() as $f) {
                $f->setName($this->getName() . '[' . $f->getName() . ']');
            }
        }
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
     * @param int|bool|array|string $default
     * 
     * @return int|bool|array|string
     */
    public function getAttribute(string $attribute, mixed $default = null): mixed
    {
        return $this->attributes[$attribute] ?? $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * 
     * @return self
     */
    public function setAttribute(string $name, mixed $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
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
        $this->prefixFieldNames();
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