<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // récupérer toutes les employes de la BDD        Pour trier les noms par ordre alphabetique on utilise findBy et on met un paramètre le premier tableau vide( des employés) puis le nom ou sinon on peut demander à sortir que ceux qui sont de Strasbourg ça fait comme une requete SQL  SELECT * FROM employe WHERE ville = Strasbourg ORDER BY nom ASC
        $employes = $doctrine->getRepository(Employe::class)->findAll([], ["nom" => "ASC"]);
        // $employes = $doctrine->getRepository(Employe::class)->findBy(["ville" => "Strasbourg"], ["nom" => "ASC"]);
        return $this->render('employe/index.html.twig', [
            'employes' => $employes
        ]);
    }


    #[Route('/employe/add', name: 'add_employe')]
    #[Route('/employe/{id}/edit', name: 'edit_employe')]
    public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request): Response
    {

        if(!$employe) {
            $employe = new Employe();
        }

        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $employe = $form->getData();
            $entityManager = $doctrine->getManager();
            //equivalent prepare request pour éviter les failles SQL
            $entityManager->persist($employe);
            // insert into (execute)
            $entityManager->flush();

            return $this->redirectToRoute('app_employe');
        }
        //vue pour affiche le formaulaire d'ajout 
        return $this->render('employe/add.html.twig', [
            'formAddEmploye' => $form->createView(),
            'edit' => $employe->getId(),
        ]);
    }


    #[Route('/employe/{id}/delete', name: 'delete_employe')]
    public function delete(ManagerRegistry $doctrine, Employe $employe): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($employe);
        $entityManager->flush();

        return $this->redirectToRoute('app_employe');
    }


    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}