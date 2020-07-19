<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use App\Form\ContactType;
use App\Entity\Contact;
use App\Entity\Tag;
use App\Repository\ContactRepository;
use App\Repository\TagRepository;
use PHPUnit\Framework\TestCase;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


final class ContactControllerTest extends TestCase
{
    private $contact;

    public function setUp():void
    {
        $this->test = new Contact();
        $this->test->newContact('testFirstName', 'testLastName', 'testValide@gmail.com', '06.06.06.06.06', 'Commercial');
        $this->empty = new Contact();
        $this->empty->newContact('', '', '', '', '');
        $this->testInvalid = new Contact();
        $this->testInvalid->newContact('invalide', 'invalide', 'test@gmail', '06XX', 'Commercial');
    }
    
    public function testPhoneFormat(){
        $this->assertIsString($this->test->getPhone());
    }

    public function testValidPhone(){
        $this->assertFalse($this->test->verifPhone($this->test->getPhone()));
    }

    public function testInvalidPhone(){
        $this->assertFalse($this->testInvalid->verifPhone($this->testInvalid->getPhone()));
    }

}

