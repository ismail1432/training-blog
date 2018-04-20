<?php
namespace NV\MiniFram\Form;

use NV\MiniFram\Entity;

class Form
{
    protected $entity;
    protected $fields = [];

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function add(Field $field)
    {
        if (!empty($field->getName())) {
            $attr = 'get'.ucfirst($field->getName());
            $field->setValue($this->entity->$attr());
        }

        $this->fields[] = $field;
        return $this;
    }

    public function createView()
    {
        $view = '';

        foreach ($this->fields as $field) {
            $view .= $field->buildWidget().'<br>';
        }
        return $view;
    }

    public function isValid()
    {
        $valid = true;

        foreach ($this->fields as $field) {
            if (!$field->isValid()) {
                $valid = false;
            }
        }

        return $valid;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
