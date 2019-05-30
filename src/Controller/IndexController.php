<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 024 24.05.2019
 * Time: 23:55
 */

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    /**
     * @Route("/index/{number}")
     */
    public function showAction($number)
    {
        return new Response(sprintf('Hello! Test project: %s', $number));
    }
}