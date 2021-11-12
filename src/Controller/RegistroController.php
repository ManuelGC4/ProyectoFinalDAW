<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\Type\RegistroType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistroController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registro", name="registro")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard)
    {
        $usuario = new Usuario();

        $form = $this->createForm(RegistroType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();

            $usuario->setPassword($this->passwordEncoder->encodePassword($usuario, $form['password']->getData()));
            $usuario->setTema("claro");
            $usuario->setIdioma("ES");

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($usuario);

            $entityManager->flush();

            return $guard->authenticateUserAndHandleSuccess($usuario, $request, $login, 'main');
        }

        return $this->render('viewtube/nuevoUsuario.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
