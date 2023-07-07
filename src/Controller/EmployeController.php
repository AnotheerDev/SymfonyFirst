<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\Persistence\ManagerRegistry;
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


    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}