<?php

namespace App\DataFixtures;

use App\Entity\Video;
use App\Entity\Comentario;
use App\Entity\Usuario;
use App\Entity\Categoria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ViewtubeFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $connection = $manager->getConnection();
        $connection->exec("ALTER TABLE usuario AUTO_INCREMENT = 1;");
        $connection->exec("ALTER TABLE video AUTO_INCREMENT = 1;");
        $connection->exec("ALTER TABLE comentario AUTO_INCREMENT = 1;");
        $connection->exec("ALTER TABLE categoria AUTO_INCREMENT = 1;");

        $categoria1 = new Categoria();
        $categoria1->setNombre("Naturaleza");

        $categoria2 = new Categoria();
        $categoria2->setNombre("Animales");

        $categoria3 = new Categoria();
        $categoria3->setNombre("Ciudad");

        $video1 = new Video();
        $video1->setTitulo("Lago en el valle");
        $video1->setDescripcion("Perspectiva aérea del lago de un valle");
        $video1->setFecha(new \DateTime('2021-11-01 20:17:26'));
        $video1->setCategoria($categoria1);
        $video1->setThumbnail("thumbnailVideo-1.jpg");
        $video1->setVideo("video-1.mp4");

        $video2 = new Video();
        $video2->setTitulo("Lago en la montaña");
        $video2->setDescripcion("Perspectiva aérea del lago en una montaña");
        $video2->setFecha(new \DateTime('2021-11-01 21:20:35'));
        $video2->setCategoria($categoria1);
        $video2->setThumbnail("thumbnailVideo-2.jpg");
        $video2->setVideo("video-2.mp4");

        $video3 = new Video();
        $video3->setTitulo("Vaca pastando");
        $video3->setDescripcion("Vaca pastando tranquilamente");
        $video3->setFecha(new \DateTime('2021-11-15 18:06:25'));
        $video3->setCategoria($categoria2);
        $video3->setThumbnail("thumbnailVideo-3.jpg");
        $video3->setVideo("video-3.mp4");

        $video4 = new Video();
        $video4->setTitulo("Anochecer en la costa");
        $video4->setDescripcion("Perspectiva de una ciudad portuaria mientras anochece");
        $video4->setFecha(new \DateTime('2021-11-15 18:11:58'));
        $video4->setCategoria($categoria3);
        $video4->setThumbnail("thumbnailVideo-4.jpg");
        $video4->setVideo("video-4.mp4");

        $video5 = new Video();
        $video5->setTitulo("Bullicio de autos");
        $video5->setDescripcion("Vehículos recorriendo la ciudad mientras anochece");
        $video5->setFecha(new \DateTime('2021-11-15 18:12:14'));
        $video5->setCategoria($categoria3);
        $video5->setThumbnail("thumbnailVideo-5.jpg");
        $video5->setVideo("video-5.mp4");

        $video6 = new Video();
        $video6->setTitulo("Mirada felina");
        $video6->setDescripcion("Gato mirando a la cámara");
        $video6->setFecha(new \DateTime('2021-11-16 17:09:42'));
        $video6->setCategoria($categoria2);
        $video6->setThumbnail("thumbnailVideo-6.jpg");
        $video6->setVideo("video-6.mp4");

        // Usuario administrador
        $usuario = new Usuario();
        $usuario->setNombre("View");
        $usuario->setApellidos("Tube");
        $usuario->setNick("ViewTube");
        $usuario->setEmail("viewtube@gmail.com");
        $usuario->setPassword($this->passwordEncoder->encodePassword(
            $usuario,
            'ViewTube'
        ));
        $usuario->setTema("claro");
        $usuario->setIdioma("ES");

        // Le damos el rol de administrador (ROLE_ADMIN).
        $usuario->setRoles(array("ROLE_ADMIN"));

        $comentario = new Comentario();
        $comentario->setTexto('Me encanta!!');
        $comentario->setFecha(new \DateTime('2021-11-01 21:35:33'));
        $video1->addComentario($comentario);
        $usuario->addComentario($comentario);

        $usuario->addVideo($video1);
        $usuario->addVideo($video2);
        $usuario->addVideo($video3);
        $usuario->addVideo($video4);
        $usuario->addVideo($video5);
        $usuario->addVideo($video6);

        $manager->persist($categoria1);
        $manager->persist($categoria2);
        $manager->persist($categoria3);
        $manager->persist($comentario);
        $manager->persist($usuario);
        $manager->persist($video1);
        $manager->persist($video2);
        $manager->persist($video3);
        $manager->persist($video4);
        $manager->persist($video5);
        $manager->persist($video6);

        $manager->flush();
    }
}
