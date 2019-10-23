<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Survey;
use App\Form\SurveyType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class SurveyController extends AbstractController
{
    /**
     * @Route("/survey", name="survey")
     */
    public function new(Request $request)
    {
        $survey = new Survey();
        $question1 = new Question();
        $question2 = new Question();
        $question1->setText('текст вопроса 1');
        $question1->setRequired(true);

        $answer11 = new Answer();
        $answer11->setText('текст ответа 1');
        $answer12 = new Answer();
        $answer13 = new Answer();
        $answer21 = new Answer();
        $answer22 = new Answer();
        $answer23 = new Answer();
        $question1->addAnswer($answer11);
        $question1->addAnswer($answer12);
        $question1->addAnswer($answer13);
        $question2->addAnswer($answer21);
        $question2->addAnswer($answer22);
        $question2->addAnswer($answer23);
        $survey->addQuestion($question1);
        $survey->addQuestion($question2);


        $form = $this->createForm(SurveyType::class, $survey);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();


            $questions = $survey->getQuestions();
//            $answers = $questions->getAnswers();

//            foreach ($questions as $question) {
//                $required = $question->getRequired(true);
//            }
//            if (isset($required) && $required == null) {
//                return $this->redirectToRoute('survey');
//            }
//
//            if($survey->getStatus() == 'active') {
//                $repository = $this->getDoctrine()->getRepository(Survey::class);
//                $again_active = $repository->findOneBy(['status' => 'active']);
//                if(isset($again_active)) {
//                    return $this->redirectToRoute('survey');
//                }
//            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($survey);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('survey/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/survey_edit", name="survey_edit")
     */
    public function edit(Request $request) {
        $survey = new Survey();
        $question1 = new Question();
        $answer1 = new Answer();
        $answer2 = new Answer();
        $question1->addAnswer($answer1);
        $question1->addAnswer($answer2);
        $survey->addQuestion($question1);


        $form = $this->createForm(SurveyType::class, $survey);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();




            $em = $this->getDoctrine()->getManager();
            $em->persist($survey);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('survey/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
