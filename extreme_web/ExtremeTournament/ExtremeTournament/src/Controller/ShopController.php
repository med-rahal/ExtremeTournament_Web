<?php

namespace App\Controller;
use App\Entity\Panier;
use App\Entity\Produit;

use App\Form\AddProduitType;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use App\Services\QrcodeService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="app_shop")
     */
    public function index(ProduitRepository $rep): Response
    {
        $qrCode = null;
        $produitlist = $rep->findAll();
        if($produitlist!=null){
            return $this->render('shop/index.html.twig', [
                'produitlist' => $produitlist,
                'qrCode' => $qrCode
            ]);
        }
        else{
            return $this->render('shop/index.html.twig', [
                'produitlist' => $produitlist,
                'qrCode' => $qrCode
            ]);
        }
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
    {
        $qrCode = null;
        $produitlist = $rep->findAll();
        if($produitlist!=null){
            return $this->render('shop/index.html.twig', [
                'produitlist' => $produitlist,
                'qrCode' => $qrCode
            ]);
        }
        else{
            return $this->render('shop/index.html.twig', [
                'produitlist' => $produitlist,
                'qrCode' => $qrCode
            ]);
        }

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
     *@Route("/produit/add_form", name="add_form")
     */
    public function addprod(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(AddProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('produits_directory'),
                $fileName);
            $produit->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_list_Back');
        }
        return $this->render('shop/Form/addproduit.html.twig', ['form' => $form->createView()]);
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
     * @Route("/add_cart", name="add_cart", methods="GET")
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
     * @Route("/produit/pdf", name="produitpdf", methods={"GET"})
     */
    public function listU(ProduitRepository $rep) :Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $reader=$rep->findAll();


        // Retrieve the HTML generated in our twig file

        $html = $this->renderView('shop/affichepdf.html.twig', array(
            'produits'=>$reader
        ));

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("ProduitsList.pdf", [
            "Attachment" => true
        ]);

        // Send some text response
        return new Response("The PDF file has been succesfully generated !");

    }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }



}
