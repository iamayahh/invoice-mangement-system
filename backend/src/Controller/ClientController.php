<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClientController extends AbstractController
{
    #[Route('/clients', name: 'app_clients', methods:['GET'])]
    public function index(Request $request, ClientRepository $clientRepository): Response
    {
       $searchTerm = trim($request->query->get('q'));
       try{
        if($searchTerm!==''){
            $clients=$clientRepository->findBySearchTerm($searchTerm);
        }
        else{
            $clients=$clientRepository->findAll();
        }
        return $this->json($clients,Response::HTTP_OK,[],[
            'groups'=>['client:read']
        ]);
       } 
       catch(\Exception $ex){
        return $this->json([
            'error'=> 'An error occured.',
            'message'=>$ex->getMessage()
        ],Response::HTTP_INTERNAL_SERVER_ERROR);

       }
    }
}
