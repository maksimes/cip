<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class MainController extends AbstractController
{
    /**
     * @Route("/news/{slug}")
     */
    public function show($slug)
    {
        $comments = [
            'Реализация намеченных плановых заданий процветает, как ни в чем не бывало',
            'Частокол на границе починят', 'Главные СМИ предупреждают: зима близко',
            'Как бы то ни было, доблесть наших правозащитников разочаровала'
        ];

        return $this->render('article/show.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments,
        ]);
    }
}
