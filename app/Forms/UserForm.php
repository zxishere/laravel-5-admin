<?php namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Role;

class UserForm extends Form
{
    protected function getRoles()
    {
        return Role::lists('display_name', 'id')->toArray();
    }

    protected function getRolesSelected()
    {
        return !isset($this->model->id) ?: $this->model->roles()->lists('id')->toArray();
    }

    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => trans('messages.name')])
            ->add('email', 'email', ['label' => trans('messages.email')])
            ->add('roles', 'select', [
                'choices' => $this->getRoles(),
                'selected' => $this->getRolesSelected(),
                'label' => trans('messages.roles'),
                'attr' => [
                    'multiple' => true,
                    'id' => 'roles',
                    'name' => 'roles[]'
                ],
            ])
            ->add('password', 'password', [
                'value' => '',
                'label' => trans('messages.password')
            ])
            ->add('task', 'hidden')
        ;
    }
}
