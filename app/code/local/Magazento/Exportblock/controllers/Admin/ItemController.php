<?php
/*
* @category   Magazento
* @package    Magazento_Exportblock
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportblock_Admin_ItemController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('system/exportblock')
            ->_addBreadcrumb(Mage::helper('exportblock')->__('exportblock'), Mage::helper('exportblock')->__('exportblock'))
            ->_addBreadcrumb(Mage::helper('exportblock')->__('exportblock Items'), Mage::helper('exportblock')->__('exportblock Items'))
        ;
        return $this;
    }

    /**
     * Related part
     */    
    public function relatedAction() {
        
        $this->loadLayout();
        $this->getLayout()->getBlock('related.grid');
        $this->renderLayout();
    }

    public function relatedgridAction() {

        $this->loadLayout();
        $this->getLayout()->getBlock('related.grid');
        $this->renderLayout();
    }

    public function indexAction() {
        $this->_initAction()
                ->_addContent($this->getLayout()->createBlock('exportblock/admin_item'))
                ->renderLayout();
    }


    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        
        $id = $this->getRequest()->getParam('item_id');
        
        if (Mage::helper('exportblock')->versionUseAdminTitle()) {
            $this->_title($this->__('exportblock'));
        }

        $model = Mage::getModel('exportblock/item');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('exportblock')->__('This item no longer exists'));
                $this->_redirect('*/*/');
                return;
            }
        }
        
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        
        Mage::register('exportblock_item', $model);
        
        
        $this->loadLayout(array('default', 'editor'))
            ->_setActiveMenu('system/exportblock');

        $this->getLayout()->getBlock('head')
            ->setCanLoadExtJs(true)
            ->setCanLoadRulesJs(true)
            ->addItem('js', 'magazento_export/adminhtml/tabs.js');
        
        $this-> _addBreadcrumb($id ? Mage::helper('exportblock')->__('Edit Item') : Mage::helper('exportblock')->__('New Item'), $id ? Mage::helper('exportblock')->__('Edit Item') : Mage::helper('exportblock')->__('New Item'))
                ->_addContent($this->getLayout()->createBlock('exportblock/admin_item_edit')->setData('action', $this->getUrl('*/admin_item/save')))
                ->_addLeft($this->getLayout()->createBlock('exportblock/admin_item_edit_tabs'))
                ->renderLayout();
    }


    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

//            var_dump($data);
//            exit();

            // Order Statuses
            $status = $this->getRequest()->getParam('status');
            $status = array_filter($status);
            $data['status'] = implode(',',$status);


//            var_dump($data);
//            exit();
            // Assigned items
            if (isset($data['related_list'])) {
                $data['related'] = $data['related_list'];
            }
            if (isset($data['in_related'])) $data['in_related'] = true;

            $model = Mage::getModel('exportblock/item');
            $model->setData($data);
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('exportblock')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('item_id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('item_id' => $model->getId()));
                return;
            }
        }
        $this->_redirect('*/*/');
    }



    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('item_id')) {
            try {
                $model = Mage::getModel('exportblock/item');
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('exportblock')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('item_id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('exportblock')->__('Unable to find a item to delete'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $itemIds = $this->getRequest()->getParam('massaction');
        if(!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('exportblock')->__('Please select item(s)'));
        } else {
            try {
                foreach ($itemIds as $itemId) {
                    $mass = Mage::getModel('exportblock/item')->load($itemId);
                    $mass->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('exportblock')->__(
                        'Total of %d record(s) were successfully deleted', count($itemIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('exportblock/item');
    }

    public function wysiwygAction() {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $content = $this->getLayout()->createBlock('adminhtml/catalog_helper_form_wysiwyg_content', '', array(
            'editor_element_id' => $elementId
        ));
        $this->getResponse()->setBody($content->toHtml());
    }

}