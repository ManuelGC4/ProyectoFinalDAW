<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Video;
use App\Entity\Comentario;
use App\Form\Type\VideoAnadirType;
use App\Form\Type\VideoEditarType;
use App\Form\Type\ComentarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ViewtubeController extends AbstractController
{
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $videos = $entityManager->getRepository(Video::class)->findAll();

        return $this->render('blog/index.html.twig', array(
            'videos' => $videos,
        ));
    }

    public function indexSinLocale()
    {
        return $this->redirectToRoute('index', ['_locale' => 'es']);
    }

    public function verVideo($id, Request $request, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $video = $entityManager->getRepository(Video::class)->find($id);

        if (!$video) {
            throw $this->createNotFoundException(
                $translator->trans('video.noEncontrada')
            );
        }

        $comentario = new Comentario();

        $form = $this->createForm(ComentarioType::class, $comentario, ['attr' => ['id' => 'frmComentario']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comentario = $form->getData();

            $comentario->setFecha(new \DateTime());
            $comentario->setVideo($video);

            $usuario = $this->getUser();
            $comentario->setUsuario($usuario);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($comentario);

            $entityManager->flush();

            return $this->redirectToRoute('verVideo', array('id' => $id));
        }

        return $this->render('blog/video.html.twig', array(
            'video' => $video, 'form' => $form->createView(),
        ));
    }

    public function verVideoSinLocale()
    {
        return $this->redirectToRoute('verVideo', ['_locale' => 'es']);
    }

    public function nuevaVideo(Request $request)
    {
        $video = new Video();

        $form = $this->createForm(VideoAnadirType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();

            $video->setFecha(new \DateTime());

            $usuario = $this->getUser();
            $video->setUsuario($usuario);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($video);

            $entityManager->flush();

            return $this->redirectToRoute('videoCreada');
        }

        return $this->render('blog/nuevaVideo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function nuevaVideoSinLocale()
    {
        return $this->redirectToRoute('nuevaVideo', ['_locale' => 'es']);
    }

    public function videoCreada()
    {
        return $this->render('blog/videoCreada.html.twig');
    }

    public function videoCreadaSinLocale()
    {
        return $this->redirectToRoute('videoCreada', ['_locale' => 'es']);
    }

    public function editarVideo(Request $request, $id, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $video = $entityManager->getRepository(Video::class)->find($id);

        if (!$video) {
            throw $this->createNotFoundException(
                $translator->trans('video.noEncontrada')
            );
        }

        $form = $this->createForm(VideoEditarType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();

            $entityManager->flush();

            return $this->redirectToRoute('verVideo', array('id' => $id));
        }

        return $this->render('blog/editarVideo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editarVideoSinLocale()
    {
        return $this->redirectToRoute('editarVideo', ['_locale' => 'es']);
    }

    public function borrarVideo($id, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $video = $entityManager->getRepository(Video::class)->find($id);

        if (!$video) {
            throw $this->createNotFoundException(
                $translator->trans('video.noEncontrada')
            );
        }
        $entityManager->remove($video);
        $entityManager->flush();
        return $this->render('blog/videoBorrada.html.twig');
    }

    public function borrarVideoSinLocale()
    {
        return $this->redirectToRoute('borrarVideo', ['_locale' => 'es']);
    }

    public function verPerfil($id, Request $request, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $usuario = $entityManager->getRepository(Usuario::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException(
                $translator->trans('usuario.noEncontrado')
            );
        }

        $videos = $entityManager->getRepository(Video::class)->findAll();

        return $this->render('blog/perfil.html.twig', array('usuario' => $usuario, 'videos' => $videos));
    }

    public function verPerfilSinLocale()
    {
        return $this->redirectToRoute('verPerfil', ['_locale' => 'es']);
    }
}
