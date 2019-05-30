<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class QuestionController extends AbstractController
{
    /**
     * @Route("/question", name="question")
     */
    public function new(Request $request)
    {
        $question = new Question();


        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $survey = $question->GetSurvey();
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->persist($survey);
            $em->flush();

            return $this->redirectToRoute('success');
        }


        return $this->render('question/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
