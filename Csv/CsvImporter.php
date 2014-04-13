<?php

namespace Funstaff\Bundle\RedirectBundle\Csv;

use Funstaff\Bundle\RedirectBundle\Csv\AbstractCsv;
use Funstaff\Bundle\RedirectBundle\Exception\FileLoaderException;
use Funstaff\Bundle\RedirectBundle\Exception\FieldMissingException;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;

/**
 * CsvImporter.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class CsvImporter extends AbstractCsv
{
    /**
     * Import
     *
     * @param string    $file   Path of the file
     *
     * @return boolean|exception
     */
    public function import($file)
    {
        if (false !== $handle = @fopen($file, 'r')) {
            $line = 0;
            while (false !== $data = fgetcsv($handle)) {
                if (0 === $line) {
                    $keys = $this->validateMetadataEntity($data[0]);
                } else {
                    $values = array();
                    $datas = explode("\t", $data[0]);
                    foreach ($datas as $id => $value) {
                        $values[$keys[$id]] = $value;
                    }

                    $record = $this->repository->findOneBy(array(
                        'source' => $values['source']
                    ));
                    if (!$record) {
                        $record = new Redirect();
                    }
                    foreach ($values as $key => $value) {
                        $accessor = sprintf('set%s', ucfirst($key));
                        $record->$accessor($value);
                    }
                    $this->om->persist($record);
                }
                $line++;
            }
            $this->om->flush();

            return fclose($handle);
        } else {
            throw new FileLoaderException(sprintf(
                'The file "%s" doesn\'t exist.',
                $file
            ));
        }
    }

    /**
     * Validate metadata entity
     *
     * @param array    $data
     */
    private function validateMetadataEntity($data)
    {
        $metadata = $this->getMetadata();

        $keys = explode("\t", $data);
        foreach ($keys as $key) {
            if (!$metadata->hasField($key)) {
                throw new FieldMissingException(sprintf(
                    'The column with name "%s" does not exist.',
                    $key
                ));
            }
        }

        return $keys;
    }
}