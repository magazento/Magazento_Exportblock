<?php
/*
* @category   Magazento
* @package    Magazento_Exportblock
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportblock_Block_Admin_Item_Edit_Tab_Other extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $model = Mage::registry('exportblock_item');
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('additional_form', array('legend'=>Mage::helper('exportblock')->__('Additional information ')));



        $fieldset->addField('samplefield', 'text', array(
            'name' => 'samplefield',
            'label' => Mage::helper('exportblock')->__('Samplefield'),
            'title' => Mage::helper('exportblock')->__('Samplefield'),
            'required' => false,
        ));

//        $form->setUseContainer(true);
        $form->setValues($model->getData());
        $this->setForm($form);

      return parent::_prepareForm();
  }
}