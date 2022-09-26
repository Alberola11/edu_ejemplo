<?php

namespace App\Controller\Api;


use App\Entity\Categorias;
use App\Form\CategoriasFormType;
use App\Repository\CategoriasRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class CategoriasController extends AbstractFOSRestController
{
private  $catRepository;
private $em;

public function __construct(CategoriasRepository $categoriasRepository, EntityManagerInterface $em)
{
    $this-> catRepository = $categoriasRepository;
    $this->em= $em;

}

    //como vemos cambia el route por el rest que es el de Fos controller.
    //el Rest tiene muchos mas metodos no solo el get
    //el rest view lo encontramos en categoria.yaml en el group
    //erializerEnableMaxDepthChecks--> para impedir que entre en un bucle

    //Recordar borrar cache de vez en cuando cunado se hagan cambios importantes
    /**
     * @Rest\Get(path="/categorias")
     * @Rest\View(serializerGroups={"categoria"},serializerEnableMaxDepthChecks=true)
     * @return \App\Entity\Categorias[]
     */

public function getCategorias(){
    $categorias=$this->catRepository->findAll();
    return $categorias;
}
        //el post es para crear que significa enviar
    /**
     * @Rest\Post(path="/add_categoria")
     * @Rest\View(serializerGroups={"categoria"}, serializerEnableMaxDepthChecks=true )
     * @return Categorias
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    //el request es para que el usuario envie lo que el selecione
public function createCategoria(Request $request){
    $categoria= new Categorias();
    //Aqui estamos haciendo el form
    $form= $this->createForm(CategoriasFormType::class, $categoria);

     $form->handleRequest($request);//el formulario saca la informacion del request para rellenar la categoria
    //submitted-->es enviado y es valido
    if ($form->isSubmitted() && $form->isValid()){
        $this->em->persist($categoria);
        $this->em->flush();
        return $categoria;
    }
    return $form;
}





}