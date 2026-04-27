<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\InvoiceRepository;
use App\Entity\Invoice;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/invoices')]
final class InvoiceController extends AbstractController
{
    #[Route('', name: 'app_invoices_list',methods:['GET'])]
    public function list(InvoiceRepository $invoiceRepository): Response
    {
        return $this->json($invoiceRepository->findAll(), 200, [], [
            'groups'=>['invoice:read']
        ]);
    }

    #[Route('',name:'app_invoices_create',methods:['POST'])]
    public function create(Request $request,EntityManagerInterface $em):Response{

        $data = json_decode($request->getContent(),true);

        $clientId =$data['clientId'];
        $client = $em->getRepository(Client::class)->find($clientId);

        if(!$client){
            return $this->json(['error'=>'Client not found'],Response::HTTP_NOT_FOUND);
        }

        try{
        $invoice=new Invoice();
        $invoice->setClient($client);
        $invoice->setCreationDate(new \DateTimeImmutable());
        
        $calculatedTotal =0;

        if(isset($data['items']) && is_array($data['items'])){
            foreach($data['items']as $itemData){
                $item = new \App\Entity\InvoiceItem();
                $item->setDescription($itemData['description']);
                $item->setQuantity($itemData['quantity']);
                $item->setUnitPrice($itemData['unitPrice']);
                $item->setInvoice($invoice);

                $calculatedTotal+=($item->getQuantity()*$item->getUnitPrice());
                $em->persist($item);
            }
        }
        $invoice->setTotal($calculatedTotal);

        $em->persist($invoice);
        $em->flush();

        return $this->json(['message'=>'Invoice created successfully'],Response::HTTP_CREATED);
        }
        catch(\Exception $ex){
            return $this->json(['error'=>$ex->getMessage()]);
        }
    }

    #[Route('/import', name: 'app_invoices_import', methods:['POST'])]
    public function import(Request $request, ClientRepository $clientRepository, EntityManagerInterface $em): Response
    {
        $file = $request->files->get('file');
        if(!$file){
            return $this->json(['error' =>'No file uploaded'],Response::HTTP_BAD_REQUEST);
        }

        $handle =fopen($file->getRealPath(),'r');
        fgetcsv($handle);

        $count=0;

        while(($row = fgetcsv($handle))!==false){
            $email = $row[0];
            $amount = $row[1];

            $client = $clientRepository->findOneBy(['email'=>$email]);

            if($client){
                $invoice =new Invoice();
                $invoice->setTotal($amount);
                $invoice->setCreationDate(new \DateTimeImmutable());
                $invoice->setClient($client);

                $em->persist($invoice);
                $count++;
            }
        }
        fclose(($handle));
        $em->flush();

        return $this->json([
            'message' =>"Successfully imported $count invoices.",
        ]);
   
    }
}
