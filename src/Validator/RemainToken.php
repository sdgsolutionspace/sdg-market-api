<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RemainToken extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'There is only "{{ remaining }}" tokens left. You cannot buy {{ value }}" tokens.';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
