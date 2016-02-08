<?php

namespace AppBundle\Command;

use AppBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddAdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:admin:add')
            ->setDescription('Add admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $username = $dialog->ask($output, 'Enter admin username: ', '');
        $firstname = $dialog->ask($output, 'Firstname: ', '');
        $lastname = $dialog->ask($output, 'Lastname: ', '');
        $email = $dialog->ask($output, 'Email: ', '');
        $password = $dialog->ask($output, 'Password: ', '');
        $repeatPassword = $dialog->ask($output, 'Repeat password: ', '');

        if ($password != $repeatPassword) {
            die('Different passwords!' . "\n");
        }

        if (
            $em->getRepository("AppBundle:Author")->findOneBy(["username" => $username]) ||
            $em->getRepository("AppBundle:Author")->findOneBy(["email" => $email])
        ) {
            die('User with those login or password already exist!' . "\n");
        } else {
            $encoder = $this->getContainer()->get('security.password_encoder');;
            $admin = new Author();
            $admin->setUsername($username);
            $admin->setFirstname($firstname);
            $admin->setLastname($lastname);
            $admin->setEmail($email);
            $admin->setIsAdmin(true);
            $encoded = $encoder->encodePassword($admin, $password);

            $admin->setPassword($encoded);
            $em->persist($admin);
            $em->flush();
        }
    }
}