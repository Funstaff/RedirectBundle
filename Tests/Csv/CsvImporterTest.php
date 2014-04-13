<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\Importer;

use Funstaff\Bundle\RedirectBundle\Tests\Csv\AbstractCsvTest;
use Funstaff\Bundle\RedirectBundle\Csv\CsvImporter;
use Funstaff\Bundle\RedirectBundle\Exception\FileLoaderException;
use Funstaff\Bundle\RedirectBundle\Exception\FieldMissingException;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;

/**
 * CsvImporterTest.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class CsvImporterTest extends AbstractCsvTest
{
    public function testWithMissingFile()
    {
        $importer = new CsvImporter(
                        $this->getCsvEntityManager(),
                        $this->getClass()
                    );
        try {
            $importer->import('foo/bar.txt');
        } catch (FileLoaderException $exception) {
            return;
        }

        $this->fail('Catch exception on missing file.');
    }

    
    public function testWithMissingHeaders()
    {
        $importer = new CsvImporter(
                        $this->getCsvEntityManager(),
                        $this->getClass()
                    );
        try {
            $importer->import(__DIR__.'/files/redirectMissingHeaders.txt');
        } catch (FieldMissingException $exception) {
            return;
        }

        $this->fail('Catch exception on missing headers.');

    }

    public function testWithFile()
    {
        $importer = new CsvImporter(
                        $this->getCsvEntityManager(),
                        $this->getClass()
                    );
        $result = $importer->import(__DIR__.'/files/redirect.txt');
        $this->assertTrue($result);
    }


    protected function getCsvEntityManager()
    {
        $meta = $this->getMockBuilder('\Doctrine\ORM\Mapping\ClassMetadata')
                ->disableOriginalConstructor()
                ->getMock();
        $hasFieldCallback = function($value) {
            $fields = array('enabled', 'source', 'destination', 'statusCode');

            return in_array($value, $fields);
        };

        $meta->expects($this->any())
            ->method('hasField')
            ->will($this->returnCallback($hasFieldCallback));

        $em = $this->getEntityManager();
        $em->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($meta));

        $repo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                ->disableOriginalConstructor()
                ->getMock();
        $findCallback = function($value) {
            if ('bar' !== $value['source']) {
                return;
            }

            return new Redirect();
        };

        $repo->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnCallback($findCallback));

        $em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repo));

        return $em;
    }
}