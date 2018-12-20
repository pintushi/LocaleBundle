<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface PaymentsSubjectInterface
{
    /**
     * @return Collection|PaymentInterface[]
     */
    public function getPayments(): Collection;

    /**
     * @return bool
     */
    public function hasPayments(): bool;

    /**
     * @param PaymentInterface $payment
     */
    public function addPayment(PaymentInterface $payment): void;

    /**
     * @param PaymentInterface $payment
     */
    public function removePayment(PaymentInterface $payment): void;

    /**
     * @param PaymentInterface $payment
     *
     * @return bool
     */
    public function hasPayment(PaymentInterface $payment): bool;
}
