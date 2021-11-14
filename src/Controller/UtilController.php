<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\Type\TemaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class UtilController extends AbstractController
{
    public function temaUsuario($id, $path, Request $request, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $usuario = $entityManager->getRepository(Usuario::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException(
                $translator->trans('usuario.noEncontrado')
            );
        }

        $form = $this->createForm(TemaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('claro')->isClicked()) {
                $usuario->setTema('claro');
            } else {
                $usuario->setTema('oscuro');
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($usuario);

            $entityManager->flush();

            return $this->redirectToRoute($this->generateUrl($path));
        }

        return $this->render('viewtube/index.html.twig', array('temaForm' => $form->createView()));
    }
}
