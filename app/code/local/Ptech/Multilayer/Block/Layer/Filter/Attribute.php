<?php

/**
 * Ptech Multi Select Layer Navigation
 *  
 * @category     Ptech
 * @package      Ptech_Multilayer 
 * @copyright    Copyright (c) 2014-2015 Ptech
 * @author       Ptech (Brijesh Kumar)  
 * @version      Release: 1.0.0
 * @Class        Ptech_Multilayer_Block_Layer_Filter_Attribute
 * @Overwrite    Mage_Catalog_Block_Layer_Filter_Attribute
 */
class Ptech_Multilayer_Block_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute {

    public function __construct() {
        parent::__construct();
        //Load Custom PHTML of attributes 
        $this->setTemplate('multilayer/filter_attribute.phtml');
        //Set Filter Model Name
        $this->_filterModelName = 'multilayer/layer_filter_attribute';
    }

    public function getVar() {
        //Get request variable name which is used for apply filter
        return $this->_filter->getRequestVar();
    }

    public function getClearUrl() {
        //Get URL and rewrite with SEO frieldly URL
        $_seoURL = '';
        //Get request filters with URL 
        $query = Mage::helper('multilayer')->getParams();
        if (!empty($query[$this->getVar()])) {
            $query[$this->getVar()] = null;
            $_seoURL = Mage::getUrl('*/*/*', array(
                        '_use_rewrite' => true,
                        '_query' => $query,
            ));
        }

        return $_seoURL;
    }

    public function getHtmlId($item) {
        //Make HTMLID with requested filter + value of param
        return $this->getVar() . '-' . $item->getValueString();
    }

    public function Selectedfilter($item) {
        //Set Selected filters 
        $ids = (array) $this->_filter->getActiveState();
        return in_array($item->getValueString(), $ids);
    }

    public function getFiltersArray() {

        $_filtersArray = array();
        //Get all filter items  ( use getItems method of Mage_Catalog_Model_Layer_Filter_Abstract )
        foreach ($this->getItems() as $_item) {


            $showSwatches = Mage::getStoreConfig('multilayer/multilayer/show_swatches');
            $_htmlFilters = 'id="' . $this->getHtmlId($_item) . '" ';
            $var_href = "#";

            //Create URL
            $var_href = html_entity_decode($currentUrl = Mage::app()->getRequest()->getBaseUrl() . Mage::getSingleton('core/session')->getRequestPath());
            $_htmlFilters .= 'href="' . $var_href . '" ';

            $_htmlFilters .= 'class="ptech_multilayer_attribute '
                    . ($this->Selectedfilter($_item) ? 'ptech_multilayer_attribute_selected' : '') . '" ';

            //Check the number of products against filter
            $qty = '';
            if (!$this->getHideQty())
                $qty = ''; //'(' .  $_item->getCount() .')';


            if ($this->getName() == "Color") {

                if ($showSwatches == "iconslinks") {

                    $iconCode = Mage::helper('multilayer')->checkColor($_item->getLabel());
                    $_html = "";
                    $_html .= '<div class="color">
                                        <a ' . $_htmlFilters . '><div class="color_box" style="background-color:' . $iconCode . ';"></div>
                                        ' . $_item->getLabel() . '</a><span>' . $qty . '</span>
                                </div>';
                } elseif ($showSwatches == "icons") {

                    $iconCode = Mage::helper('multilayer')->checkColor($_item->getLabel());
                    $_html = "";
                    $_html .= '<div class="color">
                                        <a ' . $_htmlFilters . '><div class="color_box" style="background-color:' . $iconCode . ';"></div>
                                        </a><span>' . $qty . '</span>
                                </div>';
                } else {

                    $_html = "";
                    $_html .= '<a ' . $_htmlFilters . '>' . $_item->getLabel() . '</a><span>' . $qty . '</span>';
                }
            }



            if ($this->getName() == "Color") {
                $_filtersArray[] = $_html;
            } else {
                $_filtersArray[] = '<a ' . $_htmlFilters . '>' . $_item->getLabel() . '</a>' . $qty;
            }
        }

        return $_filtersArray;
    }

}
