<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 26/05/2022
 * Time: 14:26
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Coupon;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="base")
     */
    public function base():Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/index", name="index")
     */
    public function index():Response
    {
        $em = $this->getDoctrine()->getManager();
        $coupons = $em->getRepository(Coupon::class)->findBy(["isDeleted"=>false], ['createdAt'=>'desc'], 6,0);;
        
        return $this->render('index.html.twig', compact('coupons'));
    }

    /**
     * @Route("/code promotionnel--B5511--1xbet", name="1xbet")
     */
    public function index_1xbet():Response
    {
        $em = $this->getDoctrine()->getManager();
        $coupons = $em->getRepository(Coupon::class)->findBy(["isDeleted"=>false], ['createdAt'=>'desc'], 6,0);;
        
        return $this->render('1xbet/1xbet.html.twig', compact('coupons'));
    }

    /**
     * @Route("/code promotionnel--B5511--1win", name="1win")
     */
    public function index_1win():Response
    {
        $em = $this->getDoctrine()->getManager();
        $coupons = $em->getRepository(Coupon::class)->findBy(["isDeleted"=>false], ['createdAt'=>'desc'], 6,0);;
        
        return $this->render('1win/1win.html.twig', compact('coupons'));
    }

    /**
     * @Route("/code promotionnel--B5511--betwinner", name="betwinner")
     */
    public function index_betwinner():Response
    {
        $em = $this->getDoctrine()->getManager();
        $coupons = $em->getRepository(Coupon::class)->findBy(["isDeleted"=>false], ['createdAt'=>'desc'], 6,0);;
        
        return $this->render('betwinner/betwinner.html.twig', compact('coupons'));
    }

    /**
     * @Route("/code promotionnel--B5511--linebet", name="linebet")
     */
    public function index_linebet():Response
    {
        $em = $this->getDoctrine()->getManager();
        $coupons = $em->getRepository(Coupon::class)->findBy(["isDeleted"=>false], ['createdAt'=>'desc'], 6,0);;
        
        return $this->render('linebet/linebet.html.twig', compact('coupons'));
    }

    /**
     * @Route("/code promotionnel--ml_170670--melbet", name="melbet")
     */
    public function index_melbet():Response
    {
        $em = $this->getDoctrine()->getManager();
        $coupons = $em->getRepository(Coupon::class)->findBy(["isDeleted"=>false], ['createdAt'=>'desc'], 6,0);;
        
        return $this->render('melbet/melbet.html.twig', compact('coupons'));
    }
}

