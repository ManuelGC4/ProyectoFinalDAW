<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Entity\Usuario;
use App\Entity\Video;
use App\Entity\Comentario;
use App\Form\Type\VideoAnadirType;
use App\Form\Type\VideoEditarType;
use App\Form\Type\ComentarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ViewtubeController extends AbstractController
{

    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categorias = $entityManager->getRepository(Categoria::class)->findAll();

        $categoriaId = $request->request->get('categoria');

        if ($categoriaId != null || $categoriaId != '') {

            if ($request->isXMLHttpRequest()) {

                $categoria = $entityManager->getRepository(Categoria::class)->find($categoriaId);
                $videos = $entityManager->getRepository(Video::class)->findBy($categoria);
                return new JsonResponse(array('videos' => $videos, 'categorias' => $categorias));
            }

            return new Response('This is not ajax!', 400);
        } else {
            $videos = $entityManager->getRepository(Video::class)->findAll();
        }

        return $this->render('viewtube/index.html.twig', array(
            'videos' => $videos, 'categorias' => $categorias,
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

        $videos = $entityManager->getRepository(Video::class)->findAll();

        return $this->render('viewtube/video.html.twig', array(
            'video' => $video, 'form' => $form->createView(), 'videos' => $videos,
        ));
    }

    public function verVideoSinLocale()
    {
        return $this->redirectToRoute('verVideo', ['_locale' => 'es']);
    }

    public function nuevoVideo(Request $request, SluggerInterface $slugger)
    {
        $video = new Video();
        $form = $this->createForm(VideoAnadirType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();

            $video->setFecha(new \DateTime());

            $usuario = $this->getUser();
            $video->setUsuario($usuario);

            $thumbnail = $form->get('thumbnail')->getData();

            if ($thumbnail) {
                $filename = 'thumbnailVideo-' . $video . getId() . '.' . $thumbnail->guessExtension();

                try {
                    $thumbnail->move(
                        $this->getParameter('thumbnails_videos'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $video->setThumbnail($filename);
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($video);

            $entityManager->flush();

            return $this->redirectToRoute('verPerfil', array('id' => $usuario->getId()));
        }

        return $this->render('viewtube/nuevoVideo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function nuevoVideoSinLocale()
    {
        return $this->redirectToRoute('nuevoVideo', ['_locale' => 'es']);
    }

    public function editarVideo(Request $request, $id, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $video = $entityManager->getRepository(Video::class)->find($id);
        $usuario = $video->getUsuario();

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

            return $this->redirectToRoute('verPerfil', array('id' => $usuario->getId()));
        }

        return $this->render('viewtube/editarVideo.html.twig', array(
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
        $usuarioId = $video->getUsuario()->getId();

        if (!$video) {
            throw $this->createNotFoundException(
                $translator->trans('video.noEncontrada')
            );
        }

        $entityManager->remove($video);
        $entityManager->flush();

        return $this->redirectToRoute('verPerfil', array('id' => $usuarioId));
    }

    public function borrarVideoSinLocale()
    {
        return $this->redirectToRoute('borrarVideo', ['_locale' => 'es']);
    }

    public function verPerfil($id, Request $request, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $usuario = $entityManager->getRepository(Usuario::class)->find($id);
        $categorias = $entityManager->getRepository(Categoria::class)->findAll();

        if (!$usuario) {
            throw $this->createNotFoundException(
                $translator->trans('usuario.noEncontrado')
            );
        }

        return $this->render('viewtube/perfil.html.twig', array('usuario' => $usuario, 'categorias' => $categorias));
    }

    public function verPerfilSinLocale()
    {
        return $this->redirectToRoute('verPerfil', ['_locale' => 'es']);
    }

    /*
    public function crearThumbnailVideo($id)
    {

        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($assetPackage->getUrl('videos/video-' . $id . '.mp4'));
        $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(1));
        $frame->save($assetPackage->getUrl('videos/video-' . $id . '.mp4'));

        return $frame;
    }
    */
}
