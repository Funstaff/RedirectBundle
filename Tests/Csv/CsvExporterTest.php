<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\Importer;

use Funstaff\Bundle\RedirectBundle\Tests\Csv\AbstractCsvTest;
use Funstaff\Bundle\RedirectBundle\Csv\CsvExporter;
use Funstaff\Bundle\RedirectBundle\Exception\FolderException;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;

/**
 * CsvExporterTest.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class CsvExporterTest extends AbstractCsvTest
{
    public function testMissingFolderExport()
    {
        $exporter = new CsvExporter(
                        $this->getEntityManagerExporter(),
                        $this->getClass()
                    );
        try {
            $exporter->export('/foo/bar.txt', $this->getCollection());
        } catch (FolderException $e) {
            return;
        }

        $this->fail('Catch exception on missing folder.');
    }

    public function testExport()
    {
        $exporter = new CsvExporter(
                        $this->getEntityManagerExporter(),
                        $this->getClass()
                    );

        $filePath = __DIR__.'/export.txt';
        $result = $exporter->export($filePath, $this->getCollection());
        $this->assertTrue($result);
        $this->assertTrue(file_exists($filePath));
        
        $result = <<<EOF
enabled	source	destination	statusCode
1	foo	bar	307
1	bar	dest	301

EOF;
        $this->assertEquals(file_get_contents($filePath), $result);
        unlink($filePath);
    }

    protected function getEntityManagerExporter()
    {
        $em = $this->getEntityManager();

        $meta = $this->getMockBuilder('\Doctrine\ORM\Mapping\ClassMetadata')
                ->disableOriginalConstructor()
                ->getMock();

        $fields = array('enabled', 'source', 'destination', 'statusCode');
        $meta->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue($fields));

        $em->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($meta));

        return $em;
    }
    
    protected function getCollection()
    {
        $redirect = new Redirect();
        $redirect
            ->setEnabled(true)
            ->setSource('foo')
            ->setDestination('bar')
            ->setStatusCode(307);
        $redirect2 = new Redirect();
        $redirect2
            ->setEnabled(true)
            ->setSource('bar')
            ->setDestination('dest')
            ->setStatusCode(301);
        return array(
            $redirect,
            $redirect2
        );
    }
}