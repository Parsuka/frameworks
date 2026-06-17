<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/restaurants')]
class RestaurantController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $restaurants = $em->getRepository(Restaurant::class)->findAll();
        $data = array_map(fn($r) => [
            'id' => $r->getId(),
            'name' => $r->getName(),
            'address' => $r->getAddress(),
            'phone' => $r->getPhone(),
            'cuisine' => $r->getCuisine(),
        ], $restaurants);
        return $this->json($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $r = $em->getRepository(Restaurant::class)->find($id);
        if (!$r) return $this->json(['error' => 'Not found'], 404);
        return $this->json([
            'id' => $r->getId(),
            'name' => $r->getName(),
            'address' => $r->getAddress(),
            'phone' => $r->getPhone(),
            'cuisine' => $r->getCuisine(),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $r = new Restaurant();
        $r->setName($data['name']);
        $r->setAddress($data['address']);
        $r->setPhone($data['phone']);
        $r->setCuisine($data['cuisine']);
        $em->persist($r);
        $em->flush();
        return $this->json(['id' => $r->getId()], 201);
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $r = $em->getRepository(Restaurant::class)->find($id);
        if (!$r) return $this->json(['error' => 'Not found'], 404);
        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) $r->setName($data['name']);
        if (isset($data['address'])) $r->setAddress($data['address']);
        if (isset($data['phone'])) $r->setPhone($data['phone']);
        if (isset($data['cuisine'])) $r->setCuisine($data['cuisine']);
        $em->flush();
        return $this->json(['status' => 'updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $r = $em->getRepository(Restaurant::class)->find($id);
        if (!$r) return $this->json(['error' => 'Not found'], 404);
        $em->remove($r);
        $em->flush();
        return $this->json(['status' => 'deleted']);
    }
}