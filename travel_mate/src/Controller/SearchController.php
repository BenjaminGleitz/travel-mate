<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search", name="search_")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $categoryRepository, EventRepository $eventRepository, Request $request): Response
    {
        $categories = $categoryRepository->findAll();

        // we get the search input content
        $query = $request->query->get('search');

        // we get the select content 
        $category = $request->query->get('category');

        // if category is empty, we send a enpty string to the method
        if (empty($category)) {
            $category='';
        }

        // 2) we get all the matching results
        $results = $eventRepository->searchEventByCity($query, $category);



        $form = $this->createForm(SearchType::class);

        return $this->render('search/index.html.twig', [
            'results' => $results,
            'query' => $query,
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }
}
