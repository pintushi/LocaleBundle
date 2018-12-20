<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Generator;

use Doctrine\Common\Persistence\ObjectManager;
use Pintushi\Bundle\PromotionBundle\Exception\FailedGenerationException;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Repository\PromotionCouponRepositoryInterface;
use Webmozart\Assert\Assert;
use Pintushi\Bundle\PromotionBundle\Factory\PromotionCouponFactory;

final class PromotionCouponGenerator implements PromotionCouponGeneratorInterface
{
    /**
     * @var PromotionCouponFactory
     */
    private $couponFactory;

    /**
     * @var PromotionCouponRepositoryInterface
     */
    private $couponRepository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var GenerationPolicyInterface
     */
    private $generationPolicy;

    /**
     * @param PromotionCouponFactory $couponFactory
     * @param PromotionCouponRepositoryInterface $couponRepository
     * @param ObjectManager $objectManager
     * @param GenerationPolicyInterface $generationPolicy
     */
    public function __construct(
        PromotionCouponFactory $couponFactory,
        PromotionCouponRepositoryInterface $couponRepository,
        ObjectManager $objectManager,
        GenerationPolicyInterface $generationPolicy
    ) {
        $this->couponFactory = $couponFactory;
        $this->couponRepository = $couponRepository;
        $this->objectManager = $objectManager;
        $this->generationPolicy = $generationPolicy;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(PromotionInterface $promotion, PromotionCouponGeneratorInstructionInterface $instruction): array
    {
        $generatedCoupons = [];

        $this->assertGenerationIsPossible($instruction);
        for ($i = 0, $amount = $instruction->getAmount(); $i < $amount; ++$i) {
            $code = $this->generateUniqueCode($instruction->getCodeLength(), $generatedCoupons);
            $coupon = $this->couponFactory->createNew();
            $coupon->setPromotion($promotion);
            $coupon->setCode($code);
            $coupon->setUsageLimit($instruction->getUsageLimit());
            $coupon->setExpiresAt($instruction->getExpiresAt());

            $generatedCoupons[$code] = $coupon;

            $this->objectManager->persist($coupon);
        }

        $this->objectManager->flush();

        return $generatedCoupons;
    }

    /**
     * @param int $codeLength
     * @param array $generatedCoupons
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private function generateUniqueCode(int $codeLength, array $generatedCoupons): string
    {
        Assert::nullOrRange($codeLength, 1, 40, 'Invalid %d code length should be between %d and %d');

        do {
            $hash = sha1((string) microtime(true));
            $code = strtoupper(substr($hash, 0, $codeLength));
        } while ($this->isUsedCode($code, $generatedCoupons));

        return $code;
    }

    /**
     * @param string $code
     * @param array $generatedCoupons
     *
     * @return bool
     */
    private function isUsedCode(string $code, array $generatedCoupons): bool
    {
        if (isset($generatedCoupons[$code])) {
            return true;
        }

        return null !== $this->couponRepository->findOneBy(['code' => $code]);
    }

    /**
     * @param PromotionCouponGeneratorInstructionInterface $instruction
     *
     * @throws FailedGenerationException
     */
    private function assertGenerationIsPossible(PromotionCouponGeneratorInstructionInterface $instruction)
    {
        if (!$this->generationPolicy->isGenerationPossible($instruction)) {
            throw new FailedGenerationException($instruction);
        }
    }
}
