<?php

namespace Pintushi\Bundle\ReportBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;

interface ReportInterface extends TimestampableInterface, OrganizationAwareInterface
{
    /**
     * @return mixed
     */
    public function getId();

    // public function getOrder(): OrderInterface;

    /**
     * @param $order
     * @return $this
     */
    // public function setOrder(OrderInterface $order);

    public function getGrades(): Collection;

    /**
     * @param ReportGrade $grade
     * @return $this
     */
    public function addGrade(ReportGrade $grade);

    /**
     * @param ReportGrade $grade
     *
     * @return $this
     */
    public function removeGrade(ReportGrade $grade);

    public function getReview(): string;

    /**
     * @param $review
     * @return $this
     */
    public function setReview($review);
}
