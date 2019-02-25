<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class LaravelSettingsForm extends Form
{
    public function buildForm()
    {
        // Home text
        // Demo filename format
        // Minimum reporter karma to be accepted
        // Maximum target karma to be accepted
        // Karma delta to admin discord
        //

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
