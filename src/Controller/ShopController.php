<?php

namespace App\Controller;
use App\Entity\Panier;
use App\Entity\Produit;

use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use App\services\QrcodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="app_shop")
     */
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    /**
     * @Route("/shopback", name="app_shopback")
     */
    public function indexback(): Response
    {
        return $this->render('shop/shopback.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    /**
     * @Route("/produit/affiche", name="produit_list")
     */
    public function afficher(ProduitRepository $rep)
    {     $qrCode = null;
        $produitlist = $rep->findAll();
        return $this->render('shop/index.html.twig', [
            'produitlist' => $produitlist,
            'qrCode' => $qrCode
        ]);
    }

    /**
     * @Route("/produit/afficheBack", name="produit_list_Back")
     */
    public function afficherBack(ProduitRepository $rep)
    {
        $produitlist = $rep->findAll();
        return $this->render('shop/shopback.html.twig', [
            'produitlist' => $produitlist,
        ]);
    }

    /**
     * @param $idp
     * @param ProduitRepository $rep
     * @return RedirectResponse
     * @Route ("delete/{idp}", name="delp")
     */
    public function supprimer($idp,ProduitRepository $rep)
    {
        $produit=$rep->find($idp);
        $em=$this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('produit_list_Back');

    }

    /**
     * @Route("/produit/add_form", name="add_form")
     */
    public function addprod(): Response
    {
        return $this->render('shop/Form/add_produit.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }



    /**
     * @Route("/produit/add_prod", name="add_prod", methods="GET")
     * @param Request $request
     * @param ProduitRepository $rep
     * @return Response
     */
    public function saveprod(Request $request,ProduitRepository $rep): Response
    {
                                                                                         //ajouter Produit

        $entityManager = $this->getDoctrine()->getManager();
        $produit=new Produit();
        $produit->setNomProd($request->query->get("name"));
        $produit->setCategorieProd($request->query->get("Categorie"));
        $produit->setDescriptif($request->query->get("Description"));
        $produit->setDisponibilite($request->query->get("Disponibilite"));
        $produit->setPrix($request->query->get("Prix"));
        $produit->setTotalEnStock($request->query->get("Stock"));
        $produit->setImage($request->query->get("image"));
        $entityManager->persist($produit);
        $entityManager->flush();
        $produitlist = $rep->findAll();
        return $this->render('shop/shopback.html.twig', [

            'produitlist' => $produitlist]);
    }

    /**
     * @Route ("/produit/update/{idp}", name="modp")
     * @param $idp
     * @param ProduitRepository $rep
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function updateprod($idp,ProduitRepository $rep,Request $request){                         //Update Produit
        $produit=$rep->find($idp);
        $form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('produit_list_Back');

        }
        return $this->render('shop/updatep.html.twig', [
            'pupdate' => $form->createView(),
        ]);


    }
    /**
     * @Route("/produit/add_cart", name="add_cart",  methods="GET")
     * @param ProduitRepository $repprod
     * @param UserRepository $repouser
     * @return RedirectResponse
     */
    public function addtocart(ProduitRepository $repprod,UserRepository $repouser,Request $request)
    {
        $loggeduser=$repouser->find(1);                                                       //change with logged user
        $entityManager = $this->getDoctrine()->getManager();
        $produit=$repprod->find($request->query->get("ref_prod"));
        $panier= new Panier();
        $panier->setRefProd($produit->getRefProd());
        $panier->setQuantite($request->query->get("quantite"));
        $panier->setIdUser($loggeduser) ;
        $panier->setTotalPanier($produit->getPrix()*$panier->getQuantite());
        $entityManager->persist($panier);
        $entityManager->flush();
        $this->addFlash("success","Product added to cart");                          //Notification
        return $this->redirectToRoute("produit_list");


    }

    /**
     * @Route("/produit/qrCode/{produitName}", name="Qrcode")
     * @param QrcodeService $qrcodeService
     * @param $produitName
     * @param ProduitRepository $rep
     * @return Response
     */
    public function qr( QrcodeService $qrcodeService,$produitName,ProduitRepository $rep): Response
    {

        $qrCode = $qrcodeService->qrcode($produitName);

        $produitlist = $rep->findAll();

        return $this->render('shop/index.html.twig', [
            'produitlist' => $produitlist,
            'qrCode' => $qrCode                            //qrcode image
        ]);
    }

    /**
     * @Route("/produit/pdf", name="pdf")
     * @param Knp\Snappy\Pdf $knpSnappyPdf
     * @param ProduitRepository $rep
     * @return PdfResponse
     */
    public function pdf(Pdf $knpSnappyPdf,ProduitRepository $rep)
    {       $produitlist = $rep->findAll();
        $html = $this->renderView('shop/affichepdf.html.twig', array(
            'produitlist' => $produitlist,
        ));
        $knpSnappyPdf->setOption('enable-local-file-access', true);
        return new PdfResponse(
            $knpSnappyPdf->generateFromHtml(

                $this->renderView('shop/affichepdf.html.twig', array(
                    'produitlist' => $produitlist,
                )),

                'c:\ProductsList.pdf'

            )
        );

    }


}
