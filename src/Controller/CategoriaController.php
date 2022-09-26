<?php

namespace App\Controller;

use App\Entity\Categorias;
use App\Repository\CategoriasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaController extends AbstractController
{
    private $catRepository;//esto y el constructor nos servirra para tener las categorias que existen
                           //Y luego listarlas en la funcion de listar categorias
    public function __construct(CategoriasRepository $catRepository)
    {
        $this->catRepository=$catRepository;
    }

    /**
     * @Route ("/categoria", name="create_categoria")
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     *
     */
    public function createCategoria(EntityManagerInterface $entityManager, Request $request)
    {
        $categoriaNombre=$request->get("categoria");//si existe guardara el nombre
        if (!$categoriaNombre){ //sino le hemos puesto un nombre no se añadira nada
            $response = new JsonResponse();
            $response->setData([
                'succes' => false, //por eso es false
                'data' => null //y data null
            ]);
            return $response;//esto hace que se pare y no se ejecuten los siguientes codigos
        }
        //si si que existe:
        $categoria = new Categorias();
        $categoria->setCategoria($categoriaNombre);//le daremos el nombre que hemos selecionado
        $entityManager->persist($categoria);//esto es para indicarle que lo prepare para añadirlo a la base de datos
        $entityManager->flush();//el flush es para que lo guarde en la base de datos

        $response = new JsonResponse();
        $response->setData([
          'succes' => true,
           'data' => [
                         ['id' => $categoria->getId(),
                             'categoria' => $categoria->getCategoria()
                         ]
                     ]
                 ]);
                $response->setStatusCode(200);//significa que todo esta bien
                 return $response;
    }

    /**
     * @Route("/categoria/list",name="categoria_list")
     * @return JsonResponse
     */

    public  function listCategoria()
    {
        $categorias = $this->catRepository->findAll();//esta funcion se creo sola al crear la entidad categoria y su repositorio
        $categoriasArray=[];//creamos un array en el que metemos todas las categorias de la base de datos
        foreach ($categorias as $cat){
            $categoriasArray[]=[
                'id'=>$cat->getId(),
                'categoria'=>$cat->getCategoria()
            ];

        }
        //luego mostramos todas las categorias con la ruta /categoria/list"
        $response = new JsonResponse();
        $response->setData([
            'succes'=>true,
            'data'=>$categoriasArray
        ]);
        $response->setStatusCode(200);
        return $response;
    }
}
