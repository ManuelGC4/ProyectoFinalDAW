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
use App\Form\Type\UsuarioEditarType;
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
     * @Route("/{_locale}/registro", name="registro")
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

            $usuarios = $entityManager->getRepository(Usuario::class)->findAll();
            $usuarioId = end($usuarios)->getId() + 1;

            $avatar = $form->get('avatar')->getData();

            if ($avatar) {
                $filename = 'avatar-' . $usuarioId . '.' . $avatar->guessExtension();

                try {
                    $avatar->move(
                        $this->getParameter('rutaAvatares'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $usuario->setAvatar($filename);
            }

            $entityManager->persist($usuario);

            $entityManager->flush();

            return $guard->authenticateUserAndHandleSuccess($usuario, $request, $login, 'main');
        }

        return $this->render('viewtube/nuevoUsuario.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/registro", name="registroSinLocale")
     */
    public function registrationSinLocale()
    {
        return $this->redirectToRoute('registro', ['_locale' => 'es']);
    }

    /**
     * @Route("/{_locale}/editarUsuario/{id}", name="editarUsuario")
     */
    public function editarUsuario($id, Request $request, UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $usuario = $entityManager->getRepository(Usuario::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException(
                $translator->trans('usuario.noEncontrado')
            );
        }

        $form = $this->createForm(UsuarioEditarType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();

            $usuario->setPassword($this->passwordEncoder->encodePassword($usuario, $form['password']->getData()));

            $avatar = $form->get('avatar')->getData();

            if ($avatar) {
                $filename = 'avatar-' . $usuario->getId() . '.' . $avatar->guessExtension();

                try {
                    $avatar->move(
                        $this->getParameter('rutaAvatares'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $usuario->setAvatar($filename);
            } else {
                $usuario->setAvatar(null);
            }

            $entityManager->flush();

            return $guard->authenticateUserAndHandleSuccess($usuario, $request, $login, 'main');
        }

        return $this->render('viewtube/editarUsuario.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/editarUsuario/{id}", name="editarUsuarioSinLocale")
     */
    public function editarUsuarioSinLocale()
    {
        return $this->redirectToRoute('editarUsuario', ['_locale' => 'es']);
    }
}
