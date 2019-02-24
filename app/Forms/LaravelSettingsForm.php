<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class LaravelSettingsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('site-name', 'text', [
                'label' => 'Website Name',
                'rules' => ['required', 'max:255']
            ])
            ->add('company-name', 'text', [
                'label' => 'Company Name',
                'rules' => ['required', 'max:255']
            ])
            ->add('email', 'email', [
                'label' => 'Email',
                'rules' => ['required', 'max:255']
            ])
        ;
    }
}
