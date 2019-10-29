<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Survey;
use App\Entity\User;
use App\Entity\UserAnswer;
use App\Form\SurveyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SurveyController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request) {
        return $this->render('survey/index.html.twig');
    }


    /**
     * @Route("/poll", name="poll")
     */
    public function poll(Request $request) {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->findOneBy(['status' => 'active']);
        if(!$survey) {
            return new Response('Нет доступного опроса');
        }
        if($request->isXmlHttpRequest()) {
            try {
                $answers_id = json_decode($request->getContent());
                $answers_repository = $this->getDoctrine()->getRepository(Answer::class);
            } catch(\Exception $e) {
                throw new NotFoundHttpException('Не удалось получить данные');
            }
            $user = new User();
            $user->setSurvey($survey);
            foreach ($answers_id as $id) {
                $user_answer = new UserAnswer();
                $user_answer->setAnswer($answers_repository->find($id))->setUser($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->persist($user_answer);
                try {
                    $em->flush();
                } catch(\Exception $e) {
                    throw new NotFoundHttpException('Что-то пошло не так, данные не сохранены');
                }
            }
            return new Response($survey->getId());
        }
        return $this->render('survey/poll.html.twig', array('survey' => $survey));
    }


    /**
     * @Route("/list", name="survey_list")
     */
    public function survey_list(Request $request) {
        try {
            $surveys = $this->getDoctrine()->getRepository(Survey::class)->findAll();
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Данные не найдены');
        }
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
    public function create(Request $request) {
        $survey = new Survey();
        $question1 = new Question();
        $answer1 = new Answer();
        $answer2 = new Answer();
        $question1->addAnswer($answer1)->addAnswer($answer2);
        $survey->addQuestion($question1);
        $form = $this->createForm(SurveyType::class, $survey);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($survey);
            try {
                $em->flush();
            } catch(\Exception $e) {
                throw new NotFoundHttpException('Что-то пошло не так, данные не сохранены');
            }
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
            try {
                $em->flush();
            } catch(\Exception $e) {
                throw new NotFoundHttpException('Что-то пошло не так, данные не сохранены');
            }
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
                return $this->redirectToRoute('survey_list', array('message' => 'Уже есть активный опрос',
                    'color' => 'red'));
            }
        }
        $em = $this->getDoctrine()->getManager();
        $survey->setStatus('active');
        $em->persist($survey);
        try {
            $em->flush();
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Не удалось сохранить данные');
        }
        return $this->redirectToRoute('survey_list', array('message' => 'Опрос активирован',
            'color' => 'green'), 301);
    }


    /**
     * @Route("/survey_del/{id}", name="survey_del")
     */
    public function delete(Request $request, Survey $survey) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($survey);
        try {
            $em->flush();
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Попытка удалить опрос завершилась неудачей');
        }
        return $this->redirectToRoute('survey_list', array('message' => 'Опрос удален',
            'color' => 'green'), 301);
    }


    /**
     * @Route("/survey_close/{id}", name="survey_close")
     */
    public function close(Request $request, Survey $survey) {
        $em = $this->getDoctrine()->getManager();
        $survey->setStatus('closed');
        $em->persist($survey);
        try {
            $em->flush();
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Попытка закрыть опрос завершилась неудачей');
        }
        return $this->redirectToRoute('survey_list', array('message' => 'Опрос закрыт',
            'color' => 'green'), 301);
    }


    /**
     * @Route("view_result/{id}", name="view_result")
     */
    public function view_result(Request $request, Survey $survey) {
        if($request->isMethod('post')) {
            try {
                $users_count = $request->request->get('users_count');
                $ua_count_str = $request->request->get('useranswers_count');
                $message = $request->request->get('message');
            } catch(\Exception $e) {
                throw new NotFoundHttpException('Не удалось получить данные');
            }
            $color = 'green';
            $temp_array = explode(',', $ua_count_str);
            foreach($temp_array as $str) {
                $arr = explode(':', $str);
                $ua_count_rebuilt[$arr[0]] = $arr[1];
            }
        } else {
            $ua_count_rebuilt = [];
            $useranswers_count = $this->getDoctrine()->getRepository(UserAnswer::class)
                ->findAllAnswersWithGroupCountUA($survey->getId());
            foreach($useranswers_count as $ua_count) {
                $ua_count_rebuilt[$ua_count['answers_id']] = $ua_count['val_count'];
            }
            $users_count = count($this->getDoctrine()->getRepository(User::class)
                ->findBy(['survey' => $survey->getId()]));
            $message = '';
            $color = '';
        }
        $data = [
            'users_count' => $users_count,
            'useranswers_count' => $ua_count_rebuilt,
            'questions' => $survey->getQuestions(),
            'message' => $message,
            'color'=> $color,
        ];
        return $this->render('survey/poll_result.html.twig',$data );
    }


    /**
     * @Route("filter/{id}", name="filter")
     */
    public function filter(Request $request, Survey $survey) {
        if($request->isXmlHttpRequest()) {
            try {
                $questions_arr = json_decode($request->getContent());
                $message = 'Выборка по пользователям: ' . $questions_arr[0];
            } catch(\Exception $e) {
                throw new NotFoundHttpException('Не удалось получить данные');
            }
            $full_filter = '';
            $ques_count = 0;
            foreach($questions_arr[1] as $question) {
                $str_min = '';
                $ans_count = 0;
                foreach($question as $ans_id) {
                    if ($ans_count != 0) {
                        $str_min .= "OR";
                    }
                    $str_min .= " conc_answers LIKE '%" . $ans_id ."%' ";
                    $ans_count++;
                }
                if($ques_count != 0) {
                    $full_filter .= ' AND ';
                }
                $full_filter .= '('. $str_min .')';
                $ques_count++;
            }
            $useranswers_count = $this->getDoctrine()->getRepository(UserAnswer::class)
                ->findAllUsersWithFilter($full_filter, $survey->getId());
            $data = [
                'users_count' => $useranswers_count[1],
                'useranswers_count' => $useranswers_count[0],
                'survey_id' => $survey->getId(),
                'message' => $message,
            ];
            $data_json = json_encode($data);
            return new Response($data_json);
        }
        return $this->render('survey/filter.html.twig', array('survey' => $survey));
    }

}
