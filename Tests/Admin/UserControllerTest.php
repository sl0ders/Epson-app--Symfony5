<?php


namespace App\Tests\Admin;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * @var $urls
     * All urls tested
     */
    private $urls;

    /**
     * @var $clientAuthenticated
     * Client which can authenticated
     */
    private $clientAuthenticated;

    /**
     * @var $entity
     * Entity to test
     */
    private $entity;

    /**
     * @var EntityManager
     */
    private $em;

    /************************************ Init Test functions ************************************/

    /**
     * Define basic urls to test
     */
    private function defineBasicParams()
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();

        /**
         * Client who can authenticated in firewall
         */
        $this->clientAuthenticated = $this->em->getRepository(User::class)->findOneBy(array(
            'email' => 'quentin.sommesous@sfr.fr'
        ));

        /**
         * Entity who was analysed
         */
        $this->entity = $this->em->getRepository(User::class)->findOneBy(array());
        /**
         * Urls who was analyzed
         */
        $basicUrl = 'admin_user_';
        $this->urls = array(
            array('expectedCode' => 200, 'route' => $this->getUrl($basicUrl . 'index', array())),
            array('expectedCode' => 200, 'route' => $this->getUrl($basicUrl . 'new', array()))
        );
    }

    /************************************ Test - routes reachable ************************************/

    /**
     * Test n°1 - No User authenticated
     * If basic urls are blocked
     */
    public function testBasicUrlsAnonymous()
    {
        $this->defineBasicParams();

        /** Create client which test this action */
        $client = $this->createClient();

        foreach ($this->urls as $url) {
            $client->request('GET', $url['route']);
            /** HTTP code attempted */
            $this->assertStatusCode(302, $client);
        }

        /** Close database connection */
        $this->em->getConnection()->close();
    }

    /**
     * Test n°2 - User is authenticated
     * If basic urls are reachable
     */
    public function testBasicUrlsWithAuthUser()
    {
        $this->defineBasicParams();

        /** Log Category Event object + Create client which test this action */
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('quentin.sommesous@sfr.fr');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        foreach ($this->urls as $url) {
            $client->request('GET', $url['route']);
            /** HTTP code attempted */
            $this->assertStatusCode($url['expectedCode'], $client);
        }

        /** Close database connection */
        $this->em->getConnection()->close();
    }
}
