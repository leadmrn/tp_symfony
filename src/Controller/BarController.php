<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Entity\Beer;
use App\Entity\Category;
use App\Entity\Client;
use App\Repository\CategoryRepository;
use App\Repository\BeerRepository;

class BarController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function mainMenu(string $category_id, string $routeName): Response{

        $catRepo = $this->getDoctrine()->getRepository(Category::class);

        return $this->render('partials/menu.html.twig', [
            'title' => "Page d'accueil",
            'categories' => $catRepo->findByTerm('normal'),
            'route_name' => $routeName,
            'category_id' => $category_id
        ]);
    }

    /**
     * @Route("/bar", name="bar")
     */
    public function index(): Response
    {
        return $this->render('bar/index.html.twig', [
            'title' => 'The bar',
            'info' => 'Hello World'
        ]);
    }

    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions()
    {
        return $this->render('mentions/index.html.twig', [
            'title' => 'Mentions légales',
        ]);
    }

    /**
     * @Route("/beers", name="beers")
     */
    public function beers()
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);


        return $this->render('beers/index.html.twig', [
            'title' => 'Page beers',
            'beers' => $beers = $repository->findAll()
        ]);
    }

    /**
     * @Route("/beer/{id}", name="beer")
     */
    public function show(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);
        $repositoryCat = $this->getDoctrine()->getRepository(Category::class);

        $beer = $repository->findOneBy([
            'id' => $id
        ]);

        $catNormal = $repositoryCat->findCatNormal($id);
        $catSpecial = $repositoryCat->findCatSpecial($id);

        return $this->render('beer/index.html.twig', [
            'beer' => $beer,
            'catNormal' => $catNormal,
            'catSpecial' => $catSpecial
        ]);
    }

    /**
     * @Route("/category/{id}", name="category")
     */
    public function showCat(int $id): Response
    {

        $catRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $catRepo->find($id);
        $beers = $category->getBeers();

        return $this->render('category/index.html.twig', [
            'beers' => $beers
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home()
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);

        return $this->render('home/index.html.twig', [
            'title' => "Page d'accueil",
            'beers' => $repository->findThreeLastBeer()
        ]);
    }

    /**
     * @Route("/statistic", name="statistic")
     */
    public function statistic()
    {
        $repository = $this->getDoctrine()->getRepository(Client::class);
        $clients = $repository->findByOrderDesc();

        //Calcul de la moyenne
        $somme = 0;
        foreach ($clients as $client){
            $somme += $client->getNumberBeer();
        }
        $moyenne = $somme / count($clients);

        //Calcul de l'écart type
        $sommeCarre = 0;
        foreach ($clients as $client){
            $sommeCarre += pow($client->getNumberBeer() -  $moyenne,  2);
        }
        $ecartType = sqrt($sommeCarre / count($clients));


        return $this->render('statistic/index.html.twig', [
            'title' => "Statistiques",
            'clients' => $clients,
            'moyenne' => $moyenne,
            'ecartType' => $ecartType
        ]);
    }
}
