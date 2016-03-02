<?php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Acme\StoreBundle\Entity\Product;
use Acme\StoreBundle\Entity\Category;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcmeStoreBundle:Default:index.html.twig');
    }

    public function createAction()
    {
        $product = new Product();

        $product->setName('A Foo Bar');
        $product->setPrice('19.99');
        $product->setDescription('Lorem ipsum dolor');

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response('Created product id '.$product->getId());
    }

    public function showAction($id)
    {
        /*$product = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Product')
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        return $this->render('AcmeStoreBundle:Default:index.html.twig');*/

        $product = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Product')
            ->find($id);

        /**
         * Retorna a categoria, porem não cria um join real na query.
         */
        $categoryName = $product->getCategory()->getName();

        return $this->render('AcmeStoreBundle:Default:index.html.twig');

    }

    public function showJoinAction($id)
    {

        $product = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Product')
            ->findOneByIdJoinedToCategory($id);

        $category = $product->getCategory();
        var_dump($category);

        return $this->render('AcmeStoreBundle:Default:index.html.twig');

    }


    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AcmeStoreBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        $product->setName('New product name!');
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }

    public function createProductAction($idCategory)
    {
        /*$category = new Category();
        $category->setName('Main Products');*/

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AcmeStoreBundle:Category')->find($idCategory);

        $product = new Product();
        $product->setName('Foo');
        $product->setPrice(19.99);
        $product->setDescription('Descrição do produto');
        // relaciona a categoria com esse produto
        $product->setCategory($category);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($product);
        $em->flush();

        return new Response(
            'Created product id: '.$product->getId().' and category id: '.$category->getId()
        );
    }

}
