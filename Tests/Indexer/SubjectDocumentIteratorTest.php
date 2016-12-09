<?php

namespace Markup\NeedleBundle\Tests\Indexer;

use Markup\NeedleBundle\Indexer\SubjectDocumentIterator;

/**
* A test for an iterator that can go over an iteration of subjects and emit documents for Solarium.
*/
class SubjectDocumentIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testIsIterator()
    {
        $it = new \ReflectionClass('Markup\NeedleBundle\Indexer\SubjectDocumentIterator');
        $this->assertTrue($it->implementsInterface('\Iterator'));
    }

    public function testStringInConstructorForSubjectsThrowsInvalidArgumentException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $docGenerator = $this->createMock('Markup\NeedleBundle\Indexer\SubjectDocumentGeneratorInterface');
        $it = new SubjectDocumentIterator('subjects', $docGenerator);
    }

    public function testEmitExpectedNumberOfDocuments()
    {
        $subject = new \stdClass();

        $allSubjects = [$subject, $subject, $subject, $subject];
        $docGenerator = $this->createMock('Markup\NeedleBundle\Indexer\SubjectDocumentGeneratorInterface');
        $doc = $this->getMockBuilder('Solarium\QueryType\Update\Query\Document')
                ->disableOriginalConstructor()
                ->getMock();
        $docGenerator
            ->expects($this->any())
            ->method('createDocumentForSubject')
            ->will($this->returnValue($doc));
        $it = new SubjectDocumentIterator($allSubjects, $docGenerator);
        $this->assertEquals(4, iterator_count($it));
        $this->assertContainsOnly('Solarium\QueryType\Update\Query\Document', iterator_to_array($it));
    }

    public function testSetAndGetSubjects()
    {
        $docGenerator = $this->createMock('Markup\NeedleBundle\Indexer\SubjectDocumentGeneratorInterface');
        $it = new SubjectDocumentIterator([], $docGenerator);
        $this->assertCount(0, iterator_to_array($it->getSubjects()));
        $subject = new \stdClass();
        $someSubjects = [$subject, $subject, $subject];
        $it->setSubjects($someSubjects);
        $this->assertEquals($someSubjects, iterator_to_array($it->getSubjects()));
    }

    public function testCallbacksExecuted()
    {
        $subject = $this->createMock('Doctrine\Common\Collections\Collection');
        $subject
            ->expects($this->once())
            ->method('get');
        $callback = function ($subject) {
            $subject->get('skdjhfskjdfh');
        };
        $subjects = [$subject];
        $docGenerator = $this->createMock('Markup\NeedleBundle\Indexer\SubjectDocumentGeneratorInterface');
        $it = new SubjectDocumentIterator($subjects, $docGenerator, [$callback]);
        iterator_to_array($it);
    }
}
