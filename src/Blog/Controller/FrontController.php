<?php
namespace Blog\Controller;

use NV\MiniFram\Controller;
use NV\MiniFram\Request;
use Blog\Entity\Image;

class FrontController extends Controller
{
    public function executeIndex(Request $request)
    {
        $page = $request->getData('page');
        $ArticleRepo = $this->manager->getRepository('Article');
        $nbPages = $ArticleRepo->getNbPages($this->app->getConfig()->getParameter('articles_per_page'));
        if ($page === '') {
            $page = 1;
        }
        if ($page <= 0 || $page > $nbPages) {
            $this->app->getResponse()->redirect404();
        }
        $articles = $ArticleRepo->findLastX($this->app->getConfig()->getParameter('articles_per_page'), (int) $page);
        $nbPages = $ArticleRepo->getNbPages($this->app->getConfig()->getParameter('articles_per_page'));
        
        return $this->render('Front/index.html.twig', array(
          'articles' => $articles,
          'page' => (int) $page,
          'nbPages'=> (int) $nbPages,
        ));
    }

    public function executeArticleview(Request $request)
    {
        if ($request->getExists('id') && $request->getData('id') > 0) {
            $article = $this->manager->getRepository('Article')->findById($request->getData('id'));

            if ($article === null) {
                $this->app->getResponse()->redirect404();
            }
            if ($article->getImageId() !== null) {
                $article->setImage($this->manager->getRepository('Image')->findById($article->getImageId()));
            } else {
                $article->setImage(new Image(array('adress' => 'home-bg.jpg')));
            }

            return $this->render('Front/articleView.html.twig', array('article' => $article));
        }
    }

    public function executeContact(Request $request)
    {
        return $this->render('Front/contact.html.twig');
    }
}
