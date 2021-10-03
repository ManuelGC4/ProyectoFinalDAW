<?php

namespace App\Controller;

use App\Entity\Noticia;
use App\Entity\Comentario;
use App\Form\Type\NoticiaAnadirType;
use App\Form\Type\NoticiaEditarType;
use App\Form\Type\ComentarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogController extends AbstractController
{
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $noticias = $entityManager->getRepository(Noticia::class)->findAll();

        return $this->render('blog/index.html.twig', array(
            'noticias' => $noticias,
        ));
    }

    public function indexSinLocale()
    {
        return $this->redirectToRoute('index', ['_locale' => 'es']);
    }

    public function verNoticia($id, Request $request, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $noticia = $entityManager->getRepository(Noticia::class)->find($id);

        if (!$noticia) {
            throw $this->createNotFoundException(
                $translator->trans('noticia.noEncontrada') . $id
            );
        }

        $comentario = new Comentario();

        $form = $this->createForm(ComentarioType::class, $comentario, ['attr' => ['id' => 'frmComentario']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comentario = $form->getData();

            $comentario->setFecha(new \DateTime());
            $comentario->setNoticia($noticia);

            $usuario = $this->getUser();
            $comentario->setUsuario($usuario);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($comentario);

            $entityManager->flush();

            return $this->redirectToRoute('verNoticia', array('id' => $id));
        }

        return $this->render('blog/noticia.html.twig', array(
            'noticia' => $noticia, 'form' => $form->createView(),
        ));
    }

    public function verNoticiaSinLocale()
    {
        return $this->redirectToRoute('verNoticia', ['_locale' => 'es']);
    }

    public function nuevaNoticia(Request $request)
    {
        $noticia = new Noticia();

        $form = $this->createForm(NoticiaAnadirType::class, $noticia);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noticia = $form->getData();

            $noticia->setFecha(new \DateTime());

            $usuario = $this->getUser();
            $noticia->setAutor($usuario);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($noticia);

            $entityManager->flush();

            return $this->redirectToRoute('noticiaCreada');
        }

        return $this->render('blog/nuevaNoticia.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function nuevaNoticiaSinLocale()
    {
        return $this->redirectToRoute('nuevaNoticia', ['_locale' => 'es']);
    }

    public function noticiaCreada()
    {
        return $this->render('blog/noticiaCreada.html.twig');
    }

    public function noticiaCreadaSinLocale()
    {
        return $this->redirectToRoute('noticiaCreada', ['_locale' => 'es']);
    }

    public function editarNoticia(Request $request, $id, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $noticia = $entityManager->getRepository(Noticia::class)->find($id);

        if (!$noticia) {
            throw $this->createNotFoundException(
                $translator->trans('noticia.noEncontrada') . $id
            );
        }

        $form = $this->createForm(NoticiaEditarType::class, $noticia);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noticia = $form->getData();

            $entityManager->flush();

            return $this->redirectToRoute('verNoticia', array('id' => $id));
        }

        return $this->render('blog/editarNoticia.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editarNoticiaSinLocale()
    {
        return $this->redirectToRoute('editarNoticia', ['_locale' => 'es']);
    }

    public function borrarNoticia($id, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $noticia = $entityManager->getRepository(Noticia::class)->find($id);

        if (!$noticia) {
            throw $this->createNotFoundException(
                $translator->trans('noticia.noEncontrada') . $id
            );
        }
        $entityManager->remove($noticia);
        $entityManager->flush();
        return $this->render('blog/noticiaBorrada.html.twig');
    }

    public function borrarNoticiaSinLocale()
    {
        return $this->redirectToRoute('borrarNoticia', ['_locale' => 'es']);
    }
}
