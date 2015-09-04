<?php
/*
* @category   Magazento
* @package    Magazento_Exportblock
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportblock_Block_Admin_Item extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    
    public function __construct()
    {
        
        $this->_controller = 'admin_item';
        $this->_blockGroup = 'exportblock';
        $this->_headerText = Mage::helper('exportblock')->__('Static Block Export Profiles');
        $this->_addButtonLabel = Mage::helper('exportblock')->__('Add Profile');
        parent::__construct();

        $this->setTemplate('widget/grid/container.phtml');
    }
    
}
