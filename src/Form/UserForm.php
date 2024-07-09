<?php
namespace App\Form;

use Fagathe\Framework\Form\Field\EmailField;
use Fagathe\Framework\Form\Field\SelectField;
use Fagathe\Framework\Form\Field\TextareaField;
use Fagathe\Framework\Form\Field\TextField;
use Fagathe\Framework\Form\Form;

final class UserForm
{

    public function __construct()
    {
    }

    public function build(array $data = []): Form
    {
        return (new Form(name: 'user_create'))
            ->add(
                new TextField(
                    name: 'animal_totem',
                    label: 'Quel est votre animal totem ?',
                    attributes: ['class' => 'form-control', 'autofocus' => true],
                )
            )
            ->add(
                new EmailField(
                    name: 'email',
                    label: 'E-mail',
                    attributes: ['class' => 'form-control',],
                )
            )
            ->add(
                new TextareaField(
                    name: 'message',
                    label: 'Laissez nous un message !',
                    attributes: ['class' => 'form-control', 'rows' => 4, 'value' => 'Lorem ipsum dolor sit amet.'],
                )
            )
            ->add(
                new SelectField(
                    name: 'roles',
                    label: 'Rôles',
                    attributes: [
                        'id' => 'machin_truc',
                        'class' => 'form-select',
                        'rows' => 4,
                        'placeholder' => true,
                        'choices' => ['ROLE_ADMIN' => 'Administrateur', 'ROLE_USER' => 'Utilisateur', 'ROLE_MANAGER' => 'Gestionnaire', 'ROLE_EDITOR' => 'Éditeur']
                    ],
                )
            )
            ->setData($data)
        ;
    }

}
