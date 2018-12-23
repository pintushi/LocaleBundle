<?php

namespace Pintushi\Bundle\OrderBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RemoveExpiredCartsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pintushi:remove-expired-carts')
            ->setDescription('Removes carts that have been idle for a configured period. Configuration parameter - pintushi_order.cart_expires_after.');
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $expirationTime = $this->getContainer()->getParameter('pintushi_order.cart_expiration_period');
        $output->writeln(
            sprintf('Command will remove carts that have been idle for <info>%s</info>.', $expirationTime)
        );

        $expiredCartsRemover = $this->getContainer()->get('pintushi.expired_carts_remover');
        $expiredCartsRemover->remove();

        $this->getContainer()->get('pintushi.manager.order')->flush();
    }
}