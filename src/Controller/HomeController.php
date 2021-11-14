<?php

namespace App\Controller;

use App\Entity\Article;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getRepository(Article::class)->getQueryPaginator(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('home\index.html.twig', ['pagination' => $pagination]);
    }

    #[Cache(public: false, maxage: 0)]
    public function lastArticles(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->findBy(
                [],
                ['createdAt' => 'DESC'],
                10
            )
        ;

        return $this->render('_last_articles.html.twig', ['articles' => $articles]);
    }
}
