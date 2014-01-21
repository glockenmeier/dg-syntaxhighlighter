<?php
/*
 * Copyright 2014, Darius Glockenmeier.
 */

/*
 * Description of dgshGenericShortCode
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * @package model
 * 
 */
class dgshGenericShortcode extends DopeShortcode {
    private $shortcode = null;
    
    public function __construct($tag, DopeShortcode $shortcode) {
        parent::__construct($tag);
        $this->shortcode = $shortcode;
    }
    
    protected function processShortcode($atts, $content = '') {
        if ( ! is_array($atts)){
            $atts = array();
        }
        $brush_attr = array("brush" => $this->tag);
        $atts = array_merge($brush_attr, $atts);
        
        return $this->shortcode->processShortcode($atts, $content);
    }
}