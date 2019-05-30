<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 026 26.05.2019
 * Time: 18:19
 */

namespace App\Controller;


use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LuckyController extends AbstractController
{
    /**
     * @Rest\Route("/lucky/number", name = "success")
     */
    public function number()
    {
        $number = mt_rand(0,100);

        return $this->render('lucky/number.html.twig', array(
            'number' => $number,
        ));
    }
}