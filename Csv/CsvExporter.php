<?php

namespace Funstaff\Bundle\RedirectBundle\Csv;

use Funstaff\Bundle\RedirectBundle\Csv\AbstractCsv;
use Funstaff\Bundle\RedirectBundle\Exception\FolderException;

/**
 * CsvExporter.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class CsvExporter extends AbstractCsv
{
    /**
     * Export
     *
     * @param string            $file
     * @param ArrayCollection   $record
     *
     * @return boolean
     */
    public function export($file, Array $records)
    {
        if(!is_writable($dir = dirname($file))) {
            throw new FolderException(sprintf(
                'The Folder "%s" doesn\'t exist.',
                $dir
            ));
        } else {
            $f = fopen($file, 'w');
            $fields = $this->getExportFields();
            $output = implode("\t", $fields);
            fwrite($f, "$output\n");
            foreach ($records as $record) {
                $line = array();
                foreach ($fields as $field) {
                    $accessor = sprintf('get%s', $field);
                    array_push($line, $record->$accessor());
                }
                $output = implode("\t", $line);
                fwrite($f, "$output\n");
            }

            return fclose($f);
        }
    }

    /**
     * Get Export Fields
     *
     * @return array
     */
    protected function getExportFields()
    {
        $excludeFields = array(
            'id',
            'createdAt',
            'updatedAt',
            'lastAccessed',
            'statCount'
        );
        $metadata = $this->getMetadata();
        $entityFields = $metadata->getFieldNames();

        return array_diff($entityFields, $excludeFields);
    }
}