<?php
namespace App\Controller;

use App\Model\UserModel;
use Fagathe\Framework\Controller\AbstractController;
use Fagathe\Framework\Form\Form;
use Fagathe\Framework\Form\TextareaField;
use Fagathe\Framework\Form\TextField;
use Fagathe\Framework\Helpers\DateTimeHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DefaultController extends AbstractController
{
    use DateTimeHelperTrait;
    public function index(array $params): Response
    {
        $userModel = new UserModel();
        $form = (new Form(name: 'user_create'))
            ->add(
                new TextField(
                    name: 'animal_totem',
                    label: 'Quel est votre animal totem ?',
                    attributes: ['class' => 'form-control', 'autofocus' => true],
                )
            )
            ->add(
                new TextareaField(
                    name: 'message',
                    label: 'Laissez nous un message !',
                    attributes: ['class' => 'form-control', 'rows' => 4, 'value' => 'Lorem ipsum dolor sit amet.'],
                )
            );
        return $this->render('index.twig', compact('form'));
    }
}