<?php

namespace App\DataFixtures;

use App\Entity\Video;
use App\Entity\Comentario;
use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BlogFixtures extends Fixture
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
        $video1->setTitulo("Video 1");
        $video1->setFecha(new \DateTime('2018-10-20 08:34:26'));

        $video2 = new Video();
        $video2->setTitulo("Video 2");
        $video2->setFecha(new \DateTime('2018-10-09 11:20:35'));

        $video3 = new Video();
        $video3->setTitulo("Video 3");
        $video3->setFecha(new \DateTime('2018-09-19 18:30:46'));

        // Usuario administrador
        $usuario = new Usuario();
        $usuario->setNombre("Fernando");
        $usuario->setApellidos("León");
        $usuario->setNick("Fernan2");
        $usuario->setEmail("fernando123@gmail.com");
        $usuario->setPassword($this->passwordEncoder->encodePassword(
            $usuario,
            'Betis20'
        ));

        // Le damos el rol de administrador (ROLE_ADMIN).
        $usuario->setRoles(array("ROLE_ADMIN"));

        $comentario = new Comentario();
        $comentario->setTexto('The samba beat comes from two frogs. One big one goes boorup, the other goes be-be-be…..be-be-be. Probably goes on all night long.');
        $comentario->setFecha(new \DateTime('2018-10-01 09:35:33'));
        $video3->addComentario($comentario);
        $usuario->addComentario($comentario);

        $manager->persist($comentario);
        $manager->persist($usuario);

        // Usuario normal
        $usuario = new Usuario();
        $usuario->setNombre("Manuel");
        $usuario->setApellidos("Garcia");
        $usuario->setNick("Manolito");
        $usuario->setEmail("manolito14@gmail.com");
        $usuario->setPassword($this->passwordEncoder->encodePassword(
            $usuario,
            'pass' // La contraseña
        ));

        $comentario = new Comentario();
        $comentario->setTexto('i love it.');
        $comentario->setFecha(new \DateTime('2018-10-10 10:38:35'));
        $video3->addComentario($comentario);
        $usuario->addComentario($comentario);

        $usuario->addVideo($video1);
        $usuario->addVideo($video2);
        $usuario->addVideo($video3);

        $manager->persist($comentario);
        $manager->persist($usuario);
        $manager->persist($video1);
        $manager->persist($video2);
        $manager->persist($video3);

        $manager->flush();
    }
}
