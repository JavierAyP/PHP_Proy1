<?php

// src/Acme/TaskBundle/Form/Type/TaskType.php

namespace Acme\TaskBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\MinLength;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('task', null, array('max_length' => 40)); // min_length genera error!
        $builder->add('dueDate', null, array('widget' => 'single_text'));
        $builder->add('category', new CategoryType());
    }

    public function getName()
    {
        return 'task';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class'      => 'Acme\TaskBundle\Entity\Task',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'task_item',
        );
    }
    
}