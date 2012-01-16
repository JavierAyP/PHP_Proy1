<?php
// src/Acme/StoreBundle/Controller/StoreController.php

namespace Acme\StoreBundle\Controller;

use Acme\StoreBundle\Entity\Product;
use Acme\StoreBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class StoreController extends Controller
{
    
  private function mostrarProductos($productos)
  {
    return $this->render('AcmeStoreBundle:Store:index.html.twig', array('productos' => $productos));
  }

  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $products = $em->getRepository('AcmeStoreBundle:Product')->findAll();
    
    return $this->mostrarProductos($products);
  }

  public function listadoDQLAction($precio)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $query = $em->createQuery( // detalle: nombre de la tabla en la consulta DQL
        'SELECT p FROM AcmeStoreBundle:Product p WHERE p.price > :precio ORDER BY p.price ASC'
      )->setParameter('precio', $precio);
    
    try
    {
      $products = $query->getResult();
    }
    catch (\Doctrine\Orm\NoResultException $e)
    {
      $products = null;
    }
    
    return $this->mostrarProductos($products);
  }
  
   public function listadoDQBAction($precio)
  {
    $repository = $this->getDoctrine()->getRepository('AcmeStoreBundle:Product');
    
    $query = $repository->createQueryBuilder('p')
      ->where('p.price > :precio')
      ->setParameter('precio', $precio)
      ->orderBy('p.price', 'ASC')
      ->getQuery();
    
    try
    {
      $products = $query->getResult();
    }
    catch (\Doctrine\Orm\NoResultException $e)
    {
      $products = null;
    }
    
    return $this->mostrarProductos($products);
  }
  
  public function mostrarNombresAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $products = $em->getRepository('AcmeStoreBundle:Product')
      ->findAllOrderedByName();  // ejemplo de uso de un método personalizado del repositorio
      
    return $this->mostrarProductos($products);
  }
  
  public function createAction()
  {
    $product = new Product();
    $product->setName('A Foo Bar');
    $product->setPrice(rand(10, 100));
    $product->setDescription('Lorem ipsum dolor');

    $em = $this->getDoctrine()->getEntityManager();
    $em->persist($product);
    $em->flush();

    return new Response('Created product id '.$product->getId());
  }
  
  public function updateAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $product = $em->getRepository('AcmeStoreBundle:Product')->find($id);

    if (!$product)
    {
      throw $this->createNotFoundException('No product found for id '.$id);
    }

    $product->setName('New product name!');
    $em->flush();

    return $this->redirect($this->generateUrl('AcmeStoreBundle_index'));
  }
  
  public function removeAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $product = $em->getRepository('AcmeStoreBundle:Product')->find($id);
    
    if (!$product)
    {
      throw $this->createNotFoundException('No product found for id '.$id);
    }
    
    $em->remove($product);
    $em->flush();

    return $this->redirect($this->generateUrl('AcmeStoreBundle_index'));
  }
  
  public function createProductAction() // ahora con FKs
  {
    $category = new Category();
    $category->setName('Main Products');

    $product = new Product();
    $product->setName('Foo');
    $product->setPrice(rand(10, 100));
    $product->setDescription('Descripción producto'); // OJO: Faltaba esta línea...
    // relate this product to the category
    $product->setCategory($category);

    $em = $this->getDoctrine()->getEntityManager();
    $em->persist($category);
    $em->persist($product);
    $em->flush();

    return new Response(
        'Created product id: '.$product->getId().' and category id: '.$category->getId()
    );
  }
}
