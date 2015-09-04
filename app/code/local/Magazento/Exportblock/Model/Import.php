<?php
/*
* @category   Magazento
* @package    Magazento_Exportblock
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportblock_Model_Import
{
    private $XML = null;
    private $importModel = null;
    private $errors = array();

    /*
     * Import Items
     */
    public function importFromFile($xmlFile)
    {
        $total = 0;
        $xmlContents = file_get_contents($xmlFile);
        $this->XML = simplexml_load_string($xmlContents);

        foreach ($this->XML as $item) {
            try {

                $total++;
                $this->importModel = Mage::getModel('cms/block');

                // Item Values
                $itemValues = json_decode(json_encode($item->ItemValues));
                foreach ($itemValues as $k=>$v) {
                    if ($k == 'content') continue;
                    if ($k == 'block_id') continue;

                    $this->importModel->setData($k, (string)$v);
                }
                $content = (string)$item->ItemValues->content;
                $storeId = (string)$item->ItemValues->store_id;
                $this->importModel->setData('content',$content);
                $this->importModel->setStores(array($storeId));
                $this->importModel->save();
//                var_dump($this->importModel->getData());
//                exit();
            } catch(Exception $e) {
                $total--;
                $this->errors[] = $item->ItemValues->block_id . ' : ' . $e->getMessage();
            }
        }

        $result = array(
            'total' => $total,
            'errors' => $this->errors,
        );


        return $result;

    }

}

