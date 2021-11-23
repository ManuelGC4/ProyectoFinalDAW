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

        if ($request->request->get("textTitulo")) {
            $titulo = $request->request->get("textTitulo");
            if ($titulo != null || $titulo != '') {
                $videos = $entityManager->getRepository(Video::class)->findByTitulo($titulo);

                return $this->render('viewtube/index.html.twig', array(
                    'videos' => $videos, 'categorias' => $categorias
                ));
            }
        }

        if ($request->request->get("selCategoria")) {
            $categoriaId = $request->request->get("selCategoria");
            if ($categoriaId != null && $categoriaId != '' && $categoriaId != 0) {
                $videos = $entityManager->getRepository(Video::class)->findByCategoria($categoriaId);

                return $this->render('viewtube/index.html.twig', array(
                    'videos' => $videos, 'categorias' => $categorias
                ));
            }
        }

        if ($request->request->get("inputFecha")) {
            $fecha = $request->request->get("inputFecha");
            if ($fecha != null && $fecha != '') {
                $videos = $entityManager->getRepository(Video::class)->findBy(array(), array('fecha' => 'DESC'));
                $videosFecha = array();

                foreach ($videos as $video) {
                    $videoFecha = $video->getFecha()->format('Y-m-d');
                    if ($videoFecha == $fecha) {
                        $videosFecha[] = $video;
                    }
                }

                return $this->render('viewtube/index.html.twig', array(
                    'videos' => $videosFecha, 'categorias' => $categorias
                ));
            }
        }

        if ($request->request->get("ordenFecha")) {
            $ordenFecha = $request->request->get("ordenFecha");
            if ($ordenFecha != null && $ordenFecha != "") {
                $videos = $entityManager->getRepository(Video::class)->findBy(array(), array('fecha' => $ordenFecha));

                return $this->render('viewtube/index.html.twig', array(
                    'videos' => $videos, 'categorias' => $categorias
                ));
            }
        }

        $videos = $entityManager->getRepository(Video::class)->findBy(array(), array('fecha' => 'DESC'));

        return $this->render('viewtube/index.html.twig', array(
            'videos' => $videos, 'categorias' => $categorias
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

        $videos = $entityManager->getRepository(Video::class)->findBy(array(), array('fecha' => 'DESC'));

        $categorias = $entityManager->getRepository(Categoria::class)->findAll();

        if (isset($_POST["textTitulo"])) {
            $titulo = $_POST["textTitulo"];
            if ($titulo != null || $titulo != '') {
                $videos = $entityManager->getRepository(Video::class)->findByTitulo($titulo);

                return $this->render('viewtube/index.html.twig', array(
                    'videos' => $videos, 'categorias' => $categorias
                ));
            }
        }

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
        $entityManager = $this->getDoctrine()->getManager();

        $video = new Video();
        $form = $this->createForm(VideoAnadirType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();

            $video->setFecha(new \DateTime());

            $usuario = $this->getUser();
            $video->setUsuario($usuario);

            $videos = $entityManager->getRepository(Video::class)->findAll();
            $videoId = end($videos)->getId() + 1;

            $thumbnail = $form->get('thumbnail')->getData();

            if ($thumbnail) {
                $filename = 'thumbnailVideo-' . $videoId . '.' . $thumbnail->guessExtension();

                try {
                    $thumbnail->move(
                        $this->getParameter('rutaThumbnails'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $video->setThumbnail($filename);
            }

            $videoFile = $form->get('video')->getData();

            if ($videoFile) {
                $filenameVideo = 'video-' . $videoId . '.' . $videoFile->guessExtension();

                try {
                    $videoFile->move(
                        $this->getParameter('rutaVideos'),
                        $filenameVideo
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $video->setVideo($filenameVideo);
            }

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

            $video->setFecha(new \DateTime());

            $thumbnail = $form->get('thumbnail')->getData();

            if ($thumbnail) {
                $filename = 'thumbnailVideo-' . $id . '.' . $thumbnail->guessExtension();

                try {
                    $thumbnail->move(
                        $this->getParameter('rutaThumbnails'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $video->setThumbnail($filename);
            }

            $videoFile = $form->get('video')->getData();

            if ($videoFile) {
                $filenameVideo = 'video-' . $id . '.' . $videoFile->guessExtension();

                try {
                    $videoFile->move(
                        $this->getParameter('rutaVideos'),
                        $filenameVideo
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $video->setVideo($filenameVideo);
            }

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

        if (isset($_POST["textTitulo"])) {
            $titulo = $_POST["textTitulo"];
            if ($titulo != null || $titulo != '') {
                $videos = $entityManager->getRepository(Video::class)->findByTitulo($titulo);

                return $this->render('viewtube/index.html.twig', array(
                    'videos' => $videos, 'categorias' => $categorias
                ));
            }
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
