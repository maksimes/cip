<?php

namespace App\Controller;

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


        $form = $this->createForm(SurveyType::class, $survey);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();

            $questions = $survey->getQuestions();
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
}
