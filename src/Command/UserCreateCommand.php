<?php

namespace App\Command;

use App\Entity\User;
use App\Security\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'user:create';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $question = new Question('Please enter email for new user: ', 'user@example.com');
        $email = $helper->ask($input, $output, $question);

        $question = new Question(\sprintf('Enter password for user %s: ', $email));
        $question->setHidden(true);
        $password = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion('Select role for new user?', Role::toArray(), 0);
        $question->setErrorMessage('Role \'%s\' is invalid.');
        $role = $helper->ask($input, $output, $question);

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$role]);
        $user->setActive(true);
        $user->setPlainPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success(\sprintf('Successfully created user %s', $email));

        return 0;
    }
}
