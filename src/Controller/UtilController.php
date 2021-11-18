<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class UtilController extends AbstractController
{
    public function temaUsuario($id, Request $request, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $usuario = $entityManager->getRepository(Usuario::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException(
                $translator->trans('usuario.noEncontrado')
            );
        }

        $request = $this->container->get('request');
        $ruta = $request->get('_route');

        $tema = null;
        if (isset($_POST["claro"])) {
            $tema = "claro";
        }
        if (isset($_POST["oscuro"])) {
            $tema = "oscuro";
        }

        if ($tema != null) {
            $usuario->setTema($tema);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($usuario);

            $entityManager->flush();

            return $this->redirectToRoute($this->generateUrl($ruta));
        }
    }
}
