<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopCartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_shop_cart_")
     */
    public function index(): Response
    {
        return $this->render('shop_cart/index.html.twig', [
            'controller_name' => 'ShopCartController',
        ]);
    }


    /**
     * @Route("/cart/affiche", name="app_shop_cart")
     * @param PanierRepository $rep
     * @param ProduitRepository $prodrep
     * @return Response
     */
    public function afficherCart(PanierRepository $rep,ProduitRepository $prodrep)

    {    $prixTotal=0;
        $produits =[];
        $criteria = array('idUser' => 1);              //change 1 with logged user.id
        $paniers=$rep->findBy($criteria);

        foreach($paniers as $panier){
            $prod = $prodrep->find($panier->getRefProd());             //afficher panier
            $produits[] = $prod;
            $prixTotal += $panier->getTotalPanier();

        }
        return $this->render('shop_cart/index.html.twig', [
            'panierlist' => $paniers,
            'produits'=>$produits,
            'prixTotal'=>$prixTotal
        ]);
    }

    /**
     * @param CartRepository $repo
     * @return Response
     * @Route("/cart/deletecart/{id}", name="deleteFunc")
     */
    public function delete_cart($id, PanierRepository $repository){
        $cart=$repository->find($id);
        $em=$this->getDoctrine()->getManager();                              //supprimer panier
        $em->remove($cart);
        $em->flush();
        return $this->redirectToRoute('app_shop_cart');
    }

    /**
     * @Route("/cart/quntitePlus/{idpanier}", name="app_shop_cart_quantite_plus")
     * @param $idpanier
     * @param PanierRepository $rep
     * @param ProduitRepository $prodrep
     * @return Response
     */
    public function quantityPlus($idpanier,PanierRepository $rep,ProduitRepository $prodrep)

    {                                                               //panier quntite ++
   $cart_new_quantite=$rep->find($idpanier);
   $prixProd= (($cart_new_quantite->getTotalPanier())/($cart_new_quantite->getQuantite()));
   $cart_new_quantite->setQuantite($cart_new_quantite->getQuantite()+1);
        $cart_new_quantite->setTotalPanier($prixProd*$cart_new_quantite->getQuantite());
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $prixTotal=0;
        $produits =[];
        $criteria = array('idUser' => 1);              //change 1 with logged user.id
        $paniers=$rep->findBy($criteria);

        foreach($paniers as $panier){
            $prod = $prodrep->find($panier->getRefProd());
            $produits[] = $prod;
            $prixTotal += $panier->getTotalPanier();

        }
        return $this->redirectToRoute('app_shop_cart');
    }


    /**
     * @Route("/cart/quntiteMinus/{idpanier}", name="app_shop_cart_quantite_minus")
     * @param $idpanier
     * @param PanierRepository $rep
     * @param ProduitRepository $prodrep
     * @return Response
     */
    public function quantityMoin($idpanier,PanierRepository $rep,ProduitRepository $prodrep)

    {                                                               //panier quntite --
        $cart_new_quantite=$rep->find($idpanier);
        $prixProd= (($cart_new_quantite->getTotalPanier())/($cart_new_quantite->getQuantite()));
        $cart_new_quantite->setQuantite($cart_new_quantite->getQuantite()-1);
        $cart_new_quantite->setTotalPanier($prixProd*$cart_new_quantite->getQuantite());
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $prixTotal=0;
        $produits =[];
        $criteria = array('idUser' => 1);              //change 1 with logged user.id
        $paniers=$rep->findBy($criteria);

        foreach($paniers as $panier){
            $prod = $prodrep->find($panier->getRefProd());
            $produits[] = $prod;
            $prixTotal += $panier->getTotalPanier();

        }
        return $this->redirectToRoute('app_shop_cart');
    }

}
