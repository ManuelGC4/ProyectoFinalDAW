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

        $categoria4 = new Categoria();
        $categoria4->setNombre("Entretenimiento");

        $categoria5 = new Categoria();
        $categoria5->setNombre("Navidad");

        $video1 = new Video();
        $video1->setTitulo("Lago en el valle");
        $video1->setDescripcion("Perspectiva aérea del lago de un valle");
        $video1->setFecha(new \DateTime('2021-11-01 20:17:26'));
        $video1->setCategoria($categoria1);

        $video1->setThumbnail("thumbnailVideo-1.jpg");
        $video1->setVideo("video-1.mp4");

        $video2 = new Video();
        $video2->setTitulo("Esqueleto Bailarín");
        $video2->setDescripcion("Esqueleto bailando con todo lo que tiene");
        $video2->setFecha(new \DateTime('2021-11-30 23:20:12'));
        $video2->setCategoria($categoria4);

        $video2->setThumbnail("thumbnailVideo-2.jpg");
        $video2->setVideo("video-2.mp4");

        $video3 = new Video();
        $video3->setTitulo("Vaca pastando");
        $video3->setDescripcion("Vaca pastando tranquilamente");
        $video3->setFecha(new \DateTime('2021-11-18 15:06:25'));
        $video3->setCategoria($categoria2);

        $video3->setThumbnail("thumbnailVideo-3.jpg");
        $video3->setVideo("video-3.mp4");

        $video4 = new Video();
        $video4->setTitulo("Anochecer en la costa");
        $video4->setDescripcion("Perspectiva de una ciudad portuaria mientras anochece");
        $video4->setFecha(new \DateTime('2021-11-06 18:11:58'));
        $video4->setCategoria($categoria3);

        $video4->setThumbnail("thumbnailVideo-4.jpg");
        $video4->setVideo("video-4.mp4");

        $video5 = new Video();
        $video5->setTitulo("Bullicio de autos");
        $video5->setDescripcion("Vehículos recorriendo la ciudad mientras anochece");
        $video5->setFecha(new \DateTime('2021-11-15 16:12:14'));
        $video5->setCategoria($categoria3);
        $video5->setThumbnail("thumbnailVideo-5.jpg");
        $video5->setVideo("video-5.mp4");

        $video6 = new Video();
        $video6->setTitulo("Árbol de Navidad");
        $video6->setDescripcion("Árbol de Navidad de luces creado en espiral");
        $video6->setFecha(new \DateTime('2021-11-28 20:22:52'));
        $video6->setCategoria($categoria5);
        $video6->setThumbnail("thumbnailVideo-6.jpg");
        $video6->setVideo("video-6.mp4");

        $video7 = new Video();
        $video7->setTitulo("Esqueleto cachas");
        $video7->setDescripcion("Esqueleto haciendo abdominales para ponerse fuerte");
        $video7->setFecha(new \DateTime('2021-11-20 12:25:15'));
        $video7->setCategoria($categoria4);
        $video7->setThumbnail("thumbnailVideo-7.jpg");
        $video7->setVideo("video-7.mp4");

        $video8 = new Video();
        $video8->setTitulo("Santa Claus Saludando");
        $video8->setDescripcion("Santa Claus saluda mientras esta colgado");
        $video8->setFecha(new \DateTime('2021-11-16 08:44:52'));
        $video8->setCategoria($categoria5);
        $video8->setThumbnail("thumbnailVideo-8.jpg");
        $video8->setVideo("video-8.mp4");

        // Usuario administrador
        $usuario1 = new Usuario();
        $usuario1->setNombre("View");
        $usuario1->setApellidos("Tube");
        $usuario1->setNick("ViewTube");
        $usuario1->setEmail("viewtube@gmail.com");
        $usuario1->setPassword($this->passwordEncoder->encodePassword(
            $usuario1,
            'ViewTube'
        ));
        $usuario1->setTema("claro");
        $usuario1->setIdioma("ES");
        $usuario1->setAvatar("avatar-1.png");

        // Le damos el rol de administrador (ROLE_ADMIN).
        $usuario1->setRoles(array("ROLE_ADMIN"));

        $usuario2 = new Usuario();
        $usuario2->setNombre("Femur");
        $usuario2->setApellidos("Fernández");
        $usuario2->setNick("XEsqueletoRX");
        $usuario2->setEmail("esqueletor@gmail.com");
        $usuario2->setPassword($this->passwordEncoder->encodePassword(
            $usuario2,
            '1234'
        ));
        $usuario2->setTema("claro");
        $usuario2->setIdioma("ES");
        $usuario2->setAvatar("avatar-2.png");

        $usuario3 = new Usuario();
        $usuario3->setNombre("Santa");
        $usuario3->setApellidos("Claus");
        $usuario3->setNick("ChristmasGuy");
        $usuario3->setEmail("santaclaus@gmail.com");
        $usuario3->setPassword($this->passwordEncoder->encodePassword(
            $usuario3,
            '1234'
        ));
        $usuario3->setTema("claro");
        $usuario3->setIdioma("ES");
        $usuario3->setAvatar("avatar-3.png");

        $usuario1->addVideo($video1);
        $usuario1->addVideo($video3);
        $usuario1->addVideo($video4);
        $usuario1->addVideo($video5);
        $usuario2->addVideo($video2);
        $usuario2->addVideo($video7);
        $usuario3->addVideo($video6);
        $usuario3->addVideo($video8);

        $manager->persist($categoria1);
        $manager->persist($categoria2);
        $manager->persist($categoria3);
        $manager->persist($categoria4);
        $manager->persist($categoria5);
        $manager->persist($usuario1);
        $manager->persist($usuario2);
        $manager->persist($usuario3);
        $manager->persist($video1);
        $manager->persist($video2);
        $manager->persist($video3);
        $manager->persist($video4);
        $manager->persist($video5);
        $manager->persist($video6);
        $manager->persist($video7);
        $manager->persist($video8);

        $manager->flush();
    }
}
