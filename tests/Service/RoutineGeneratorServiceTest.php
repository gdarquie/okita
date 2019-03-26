<?php

namespace App\Tests\Service;

use App\Service\RoutineGeneratorService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutineGeneratorServiceTest extends WebTestCase
{
    /**
     * @var RoutineGeneratorService
     */
    private $routineGenerator;

    public function setUp()
    {
        parent::__construct();
        $kernel = static::bootKernel([]);
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->routineGenerator = new RoutineGeneratorService($em);
    }

//    public function testGetActions()
//    {
//        $routines = $this->routineGenerator->getActions([ 'travailler', 'jouer', 'danser']);
//        $this->assertEquals([ 'dormir', 'travailler', 'jouer', 'danser','dormir'], $routines);
//
//    }

//    public function testCreateRoutines()
//    {
//        $routines = $this->routineGenerator->getActions([ 'travailler', 'jouer', 'danser']);
//        dd($routine);
//        $this->assertEquals($routine, $routine);
//
//    }
}
