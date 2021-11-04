<?php

namespace App\DataFixtures;

use App\Entity\Video;
use App\Entity\Comentario;
use App\Entity\Usuario;
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

        $video1 = new Video();
        $video1->setTitulo("Lago en el valle");
        $video1->setDescripcion("Perspectiva aérea del lago de un valle");
        $video1->setFecha(new \DateTime('2021-11-01 20:17:26'));

        $video2 = new Video();
        $video2->setTitulo("Lago en la montaña");
        $video2->setDescripcion("Perspectiva aérea del lago en una montaña");
        $video2->setFecha(new \DateTime('2021-11-01 21:20:35'));

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

        $manager->persist($comentario);
        $manager->persist($usuario);
        $manager->persist($video1);
        $manager->persist($video2);

        $manager->flush();
    }
}
