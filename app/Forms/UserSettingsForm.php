<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class UserSettingsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('email', 'text', [
                'label' => 'Notifications E-mail',
            ])
        ;
    }
}
