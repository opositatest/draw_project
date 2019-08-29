<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Lottery;
use App\Entity\User;
use App\Exceptions\PasswordIncorrectException;
use App\Exceptions\UserNotFoundException;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;

class UserManager
{
    private $userRepository;
    private $logger;

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function getOneUserBy($criteria)
    {
        return $this->userRepository->getOneUserBy($criteria);
    }

    public function newUser(User $user)
    {
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);
        $this->userRepository->addUser($user);
    }

    public function addUserToLottery(User $user, Lottery $lottery){
        $user->addLottery($lottery);
        $this->userRepository->addUser($user);
    }

    public function borrarUserFromLottery($user, Lottery $actual, $num)
    {
        $this->userRepository->removeFromLottery($user, $actual);
    }
}
