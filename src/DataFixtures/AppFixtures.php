<?php

declare(strict_types=1);


namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Poll;
use App\Entity\Question;
use App\Entity\Prize;
use App\Entity\Answer;
use App\Entity\Result;
use App\Entity\Lottery;
use App\Entity\User;
use App\Exceptions\GanadorNotSettedException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $Pimages = ['https://desempleotecnologico.files.wordpress.com/2013/07/work-relax.jpeg?w=500',
                    'http://www.quieroimagenes.com/i/Homer-trabajando-imagen.jpg',
                    'https://unaodoscopas.files.wordpress.com/2016/07/o_marcalpena_moes.jpg',
                    'https://vignette.wikia.nocookie.net/inciclopedia/images/d/d7/Lisa_simpson_1.gif/revision/latest?cb=20090202033921',
                    'http://www.puzzlesonline.es/puzzles/2015/simpsons/tirachinasdebart.jpg',
                    'http://www.freakingnews.com/pictures/37000/Homer-Asleep-on-a-Sofa-37122.jpg',
                    'http://fotologimg.s3.amazonaws.com/photo/31/25/84/skate_sur/1228481038412_f.jpg',
                    'https://vignette.wikia.nocookie.net/lossimpson/images/1/14/Ralph_Wiggum.png/revision/latest?cb=20150426070659&path-prefix=es',
            ];

        // create  polls
        for ($i = 1; $i <= 13; $i++) {
            $poll = new Poll();
            $poll->setTitle('Poll '.$i);
            $poll->setImg('homer.jpg');
            $manager->persist($poll);
            for ($j = 1; $j <= 4; ++$j) {
                $question = new Question();
                $question->setImage('work-relax.jpeg');
                $question->setText('question '.$j);
                $question->setPoll($poll);
                $manager->persist($question);
                for ($h = 1; $h <= 4; ++$h) {
                    $answer = new Answer();
                    $answer->setText('Answer '.$h.' de la question '.$j.' de la poll '.$i);
                    $answer->setvalue(random_int(0, 5));
                    $answer->setQuestion($question);
                    $manager->persist($answer);
                }
            }
            for ($j = 1; $j <= 3; ++$j) {
                $result = new Result();
                $result->setText('Result '.$j);
                $result->setImage('lisa.jpg');
                $result->setExplanation('Explanation '.$j);
                $result->setMinVal(random_int(0, 10));
                $result->setMaxVal(random_int(10, 20));
                $result->setPoll($poll);
                $manager->persist($result);
            }
            for ($j = 1; $j <= 4; ++$j) {
                $coment = new Comment();
                $coment->setText('Comment '.$j.' de la poll '.$i);
                $coment->setPoll($poll);
                $manager->persist($coment);
            }
        }

        for ($i = 1; $i <= 10; ++$i ) {
            $prize = new Prize();
            $prize->setTitle('Viaje a Hawai para '.$i.' persona(s)');
            $prize->setImagen('');
            $manager->persist($prize);

            $lottery = new Lottery();
            $lottery->setImg('');
            $lottery->setFecha(new \DateTime());
            $lottery->setPrize($prize);
            $manager->persist($lottery);
            for ($j = 1; $j <= 10; ++$j ) {
                $user = new User();
                $user->setEmail('user'.$j.'.'.$i.'@gmail.com');
                $user->setNombre('User '.$j.'.'.$i);
                $coded = password_hash('1234', PASSWORD_BCRYPT);
                $user->setPassword($coded);
                $user->addLottery($lottery);
                $manager->persist($user);
            }
            $hoy = new \DateTimeImmutable();
            if ($lottery->getFecha() <= $hoy) {
                $users_lottery = $lottery->getUsers();
                $ganador = $users_lottery[random_int(0, \count($users_lottery) - 1)];

                try {
                    $lottery->setGanador($ganador);
                } catch (GanadorNotSettedException $gnse) {
                    dump($gnse->getMessage());
                }
                $manager->persist($lottery);
            }
        }

        $manager->flush();
    }
}
