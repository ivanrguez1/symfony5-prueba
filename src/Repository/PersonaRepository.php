<?php

namespace App\Repository;

use App\Entity\Persona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Persona|null find($id, $lockMode = null, $lockVersion = null)
 * @method Persona|null findOneBy(array $criteria, array $orderBy = null)
 * @method Persona[]    findAll()
 * @method Persona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Persona::class);
        $this->manager = $manager;
    }

    public function guardaPersona($nombre, $fechaNacimiento)
    {
        $nuevaPersona = new Persona();

        $nuevaPersona
            ->setNombre($nombre)
            ->setFechaNacimiento($fechaNacimiento);

        $this->manager->persist($nuevaPersona);
        $this->manager->flush();
    }

    public function actualizaPersona(Persona $persona): Persona
    {
        $this->manager->persist($persona);
        $this->manager->flush();

        return $persona;
    }


    public function borraPersona(Persona $persona)
    {
        $this->manager->remove($persona);
        $this->manager->flush();
    }
}
