<?php

namespace App\Controller;

use App\Repository\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PersonaController
 * @package App\Controller
 *
 * @Route(path="/API/")
 */
class PersonaController extends AbstractController
{
    private $personaRepository;

    public function __construct(PersonaRepository $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }


    /**
     * @Route("persona", name="guarda_persona", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nombre = $data['nombre'];
        $fechaNacimiento = \DateTime::createFromFormat('Y-m-d', $data['fechaNacimiento']);

        if (empty($nombre) || empty($fechaNacimiento)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->personaRepository->guardaPersona($nombre, $fechaNacimiento);

        return new JsonResponse(['status' => 'Persona creada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("persona/{id}", name="ver_persona", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $persona = $this->personaRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $persona->getId(),
            'nombre' => $persona->getNombre(),
            'fechaNacimiento' => $persona->getFechaNacimiento()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("personas", name="ver_personas", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $personas = $this->personaRepository->findAll();
        $data = [];

        foreach ($personas as $persona) {
            $data[] = [
                'id' => $persona->getId(),
                'nombre' => $persona->getNombre(),
                'fechaNacimiento' => $persona->getFechaNacimiento()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("persona/{id}", name="actualiza_persona", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $persona = $this->personaRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['nombre']) ? true : $persona->setNombre($data['nombre']);
        empty($data['fechaNacimiento']) ? true : $persona->setFechaNacimiento(\DateTime::createFromFormat('Y-m-d', $data['fechaNacimiento']));

        $updatedPersona = $this->personaRepository->actualizaPersona($persona);

        return new JsonResponse(['status' => 'Persona Actualizada!'], Response::HTTP_OK);
    }

    /**
     * @Route("persona/{id}", name="borrar_persona", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $persona = $this->personaRepository->findOneBy(['id' => $id]);

        $this->personaRepository->borraPersona($persona);

        return new JsonResponse(['status' => 'Persona Borrada'], Response::HTTP_OK);
    }
}
