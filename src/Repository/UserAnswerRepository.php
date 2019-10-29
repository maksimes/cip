<?php

namespace App\Repository;

use App\Entity\UserAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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

     /**
      * @return UserAnswer[] Returns an array of UserAnswer objects
      */

    public function findAllByAnswer($answer)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.answer = :val')
            ->setParameter('val', $answer)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
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
        $stmt->execute(['survey_id' => $survey_id]);
        return $stmt->fetchAll();
    }


    public function findAllUsersWithFilter($questions_answers, $survey_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'CREATE VIEW answs_users AS SELECT user_id, GROUP_CONCAT(answer_id) as conc_answers FROM user_answer GROUP BY user_id HAVING ' . $questions_answers;
        $conn->prepare($sql)->execute();
        $make_string = $conn->prepare('SELECT GROUP_CONCAT(conc_answers) AS u_answers FROM answs_users');
        $make_string->execute();
        $query_count_users = $conn->prepare('SELECT COUNT(user_id) as ids FROM answs_users');
        $query_count_users->execute();
        $count_users_arr = $query_count_users->fetchAll();
        $count_users = $count_users_arr[0]['ids'];
        $conn->prepare('DROP VIEW answs_users')->execute();
        $u_answers = $make_string->fetchAll();
        $sq2 = 'SELECT a.id as answers_id 
                FROM answer a
                INNER JOIN question q ON q.id=a.question_id 
                INNER JOIN survey s ON s.id=q.survey_id 
                WHERE s.id= :survey_id GROUP BY a.id';
        $stmt = $conn->prepare($sq2);
        $stmt->execute(['survey_id' => $survey_id]);
        $answers_for_survey = $stmt->fetchAll();
        $answers_count = [];
        foreach($answers_for_survey as $id) {
            $answers_count[$id['answers_id']] = substr_count($u_answers[0]['u_answers'],$id['answers_id']);
        }

        return array($answers_count, $count_users);
    }


    /*
    public function findOneBySomeField($value): ?UserAnswer
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
