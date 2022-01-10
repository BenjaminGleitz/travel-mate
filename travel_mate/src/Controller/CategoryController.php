<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $categoryRepository, EventRepository $eventRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $events = $eventRepository->findAll();


        return $this->render('category/index.html.twig', [
            'categoryName' => 'Derniers Ajouts',
            'categories' => $categories,
            'events' => $events,
        ]);
    }

    /**
     * Affiche les détails d'un article en fonction de son ID
     *
     * @Route("/{id}", name="show", requirements={"id":"\d+"})
     *
     * @return Response
     */
    public function show(int $id, CategoryRepository $repositoryCategory, EventRepository $eventRepository): Response
    {
        // On récupère les informations de l'article dont L'ID est égal à $id
        // La méthode find($id) Retourne :
        // - Les informations de l'article si celui-ci existe
        // - null si l'article n'existe pas en BDD
        $categories = $repositoryCategory->findAll();
        $categoryById = $repositoryCategory->find($id);
        $events = $eventRepository->findAll();

        dump($categories);

        // Si l'article n'existe pas
        if (!$categoryById) {
            throw $this->createNotFoundException("L'article dont l'id est $id n'existe pas");
        }
        
        // Si l'article existe...on l'affiche à partir de la vuecategory
        // show.html.twig
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
            'categoryById' => $categoryById,
            'tvShows' => $events
        ]);
    }

    

}
