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
     * @Route("/", name="poll")
     */
    public function poll(Request $request)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->findOneBy(['status' => 'active']);

        if($request->isMethod('post')) {
            dump($request);
        }

        return $this->render('survey/poll.html.twig', array('survey' => $survey));

    }


    /**
     * @Route("/list", name="survey_list")
     */
    public function survey_list(Request $request)
    {

        $surveys = $this->getDoctrine()->getRepository(Survey::class)->findAll();
        $color = $request->query->get('color') or 'red';
        $message = $request->query->get('message');
        $data =[
            'surveys' => $surveys,
            'color' => $color ? $color : '',
            'message' => $message ? $message : '',
        ];

        return $this->render('survey/survey_list.html.twig', $data);
    }


    /**
     * @Route("/new", name="new")
     */
    public function create(Request $request)
    {
        $survey = new Survey();
        $question1 = new Question();
        $answer11 = new Answer();
        $answer12 = new Answer();
        $question1->addAnswer($answer11);
        $question1->addAnswer($answer12);
        $survey->addQuestion($question1);


        $form = $this->createForm(SurveyType::class, $survey);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($survey);
            $em->flush();

            return $this->redirectToRoute('survey_list');
        }

        return $this->render('survey/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/edit/{id}", name="survey_edit")
     */
    public function edit(Request $request, Survey $survey) {

        $form = $this->createForm(SurveyType::class, $survey);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($survey);
            $em->flush();

            return $this->redirectToRoute('survey_list');
        }

        return $this->render('survey/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/survey_activate/{id}", name="survey_activate")
     */
    public function activate(Request $request, Survey $survey) {
        $surveys = $this->getDoctrine()->getRepository(Survey::class)->findAll();
        foreach($surveys as $survey_one) {
            if($survey_one->getStatus() == "active") {

                return $this->redirectToRoute('survey_list', array('message' => 'Уже есть активный опрос', 'color' => 'red'));
            }

        }
        $em = $this->getDoctrine()->getManager();
        $survey->setStatus('active');
        $em->persist($survey);
        $em->flush();
        return $this->redirectToRoute('survey_list', array('message' => 'Опрос активирован', 'color' => 'green'), 301);
    }


    /**
     * @Route("/survey_del/{id}", name="survey_del")
     */
    public function delete(Request $request, Survey $survey) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($survey);
        $em->flush();

        return $this->redirectToRoute('survey_list', array('message' => 'Опрос удален', 'color' => 'green'), 301);
    }


    /**
     * @Route("/survey_close/{id}", name="survey_close")
     */
    public function close(Request $request, Survey $survey) {

        $em = $this->getDoctrine()->getManager();
        $survey->setStatus('closed');
        $em->persist($survey);
        $em->flush();

        return $this->redirectToRoute('survey_list', array('message' => 'Опрос закрыт', 'color' => 'green'), 301);
    }


    /**
     * @Route("/survey_view_result/{id}", name="survey_view_result")
     */
    public function view_result(Request $request, Survey $survey) {

        $form = $this->createForm(SurveyType::class, $survey);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($survey);
            $em->flush();

            return $this->redirectToRoute('survey_list', 301);
        }

        return $this->render('survey/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
