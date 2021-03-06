<?php

namespace App\Repository;

use App\Entity\UserAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method UserAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAnswer[]    findAll()
 * @method UserAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserAnswer::class);
    }


    public function findAllAnswersWithGroupCountUA($survey_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT a.id as answers_id, COUNT(ua.id) as val_count 
                FROM answer a 
                LEFT JOIN user_answer ua ON a.id=ua.answer_id 
                INNER JOIN question q ON q.id=a.question_id 
                INNER JOIN survey s ON s.id=q.survey_id 
                WHERE s.id= :survey_id GROUP BY a.id';
        $stmt = $conn->prepare($sql);
        try {
            $stmt->execute(['survey_id' => $survey_id]);
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Данные не найдены');
        }
        return $stmt->fetchAll();
    }


    public function findAllUsersWithFilter($questions_answers, $survey_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'CREATE VIEW answs_users AS SELECT user_id, GROUP_CONCAT(answer_id) as conc_answers FROM user_answer GROUP BY user_id HAVING ' . $questions_answers;
        try {
            $conn->prepare($sql)->execute();
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Данные не найдены');
        }
        $make_string = $conn->prepare('SELECT GROUP_CONCAT(conc_answers) AS u_answers FROM answs_users');
        try {
            $make_string->execute();
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Данные не найдены');
        }
        $query_count_users = $conn->prepare('SELECT COUNT(user_id) as ids FROM answs_users');
        try {
            $query_count_users->execute();
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Данные не найдены');
        }
        $count_users_arr = $query_count_users->fetchAll();
        $count_users = $count_users_arr[0]['ids'];
        try {
            $conn->prepare('DROP VIEW answs_users')->execute();
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Данные не найдены');
        }
        $u_answers = $make_string->fetchAll();
        $sq2 = 'SELECT a.id as answers_id 
                FROM answer a
                INNER JOIN question q ON q.id=a.question_id 
                INNER JOIN survey s ON s.id=q.survey_id 
                WHERE s.id= :survey_id GROUP BY a.id';
        $stmt = $conn->prepare($sq2);
        try {
            $stmt->execute(['survey_id' => $survey_id]);
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Данные не найдены');
        }
        $answers_for_survey = $stmt->fetchAll();
        $answers_count = [];
        foreach($answers_for_survey as $id) {
            $answers_count[$id['answers_id']] = substr_count($u_answers[0]['u_answers'],$id['answers_id']);
        }
        return array($answers_count, $count_users);
    }

}
