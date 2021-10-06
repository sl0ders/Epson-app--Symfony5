<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $companyAdmin = new Company();
        $companyAdmin->setInkBreakingUpLvl(30)
            ->setBacBreakingUpLvl(70)
            ->setInkBreakingUpDays(30)
            ->setBacBreakingUpDays(30)
            ->setIsEnabled(true)
            ->setName("AdminCompany")
            ->setEmail("quentin.sommesous@sfr.fr")
            ->setUpdatedAt(new \DateTime())
            ->setCode("AD");
        $manager->persist($companyAdmin);

        $companyYourEntreprise = new Company();
        $companyYourEntreprise
            ->setInkBreakingUpLvl(30)
            ->setBacBreakingUpLvl(70)
            ->setInkBreakingUpDays(30)
            ->setBacBreakingUpDays(30)
            ->setIsEnabled(true)
            ->setName("YOUR-ENTREPRISE")
            ->setEmail("quentin.sommesous@sfr.fr")
            ->setUpdatedAt(new \DateTime())
            ->setCode("NU");
        $manager->persist($companyYourEntreprise);

        $userAdmin = new User();
        $userAdmin->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($userAdmin, "258790"))
            ->setEmail("quentin.sommesous@sfr.fr")
            ->setFirstname("Quentin")
            ->setLastname("Sommesous")
            ->setPhone("0670017086")
            ->setCity("le soler")
            ->setCountry("France")
            ->setStreet("Rue des epiceas")
            ->setStreetNumber("10")
            ->setIsEmailRecipient(true)
            ->setCompany($companyAdmin);
        $manager->persist($userAdmin);

        $userCompany = new User();
        $userCompany->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordEncoder->encodePassword($userCompany, "258790"))
            ->setEmail("quentin.sommesous@gmail.com")
            ->setFirstname("Anne")
            ->setLastname("Sommesous")
            ->setPhone("0621658954")
            ->setCity("le soler")
            ->setCountry("France")
            ->setStreet("Rue des epiceas")
            ->setStreetNumber("10")
            ->setIsEmailRecipient(true)
            ->setCompany($companyYourEntreprise);
        $manager->persist($userCompany);
        $manager->flush();
    }
}
