<?php
/*
 * Copyright 2014, Darius Glockenmeier.
 */

/*
 * 
 * Description of dgshController
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * @package core
 * @subpackage controller
 * 
 */
class dgshController extends DopeController {
    private $temp_shortcode_tags;
    private $shortcode_tag;
    
    public function __construct(DopePlugin $plugin) {
        parent::__construct($plugin);
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('wp_footer', array($this, 'runHighlighter'));
        
        if (get_option('dgsh_comments', "1" !== "")) {
            add_filter('comments_template', array($this, 'init_comment_shortcode'));
            add_filter('dynamic_sidebar', array($this, 'restore_shortcodes'));
        }
        $this->init_shortcode();
    }
    
    public function init_shortcode() {
        $this->shortcode_tag = get_option('dgsh_tag', 'code');
        // change wpautop priority to execute after shortcode has been processed
        // see http://sww.co.nz/solution-to-wordpress-adding-br-and-p-tags-around-shortcodes/
        if (has_filter('the_content', 'wpautop')){
            remove_filter('the_content', 'wpautop');
            add_filter('the_content', 'wpautop', 12);
        }
        
        $shortcode = new dgshShortCode($this->shortcode_tag, $this->plugin);
        $shortcode->add();
    }
    
    public function init_comment_shortcode() {
        $this->remove_shortcodes();
        $shortcode = new dgshShortCode($this->shortcode_tag, $this->plugin);
        $shortcode->add();
        
        add_filter('comment_text', 'do_shortcode');
    }
    
    public function remove_shortcodes() {
        global $shortcode_tags;
        $this->temp_shortcode_tags = $shortcode_tags;
        remove_all_shortcodes();
    }
    
    public function restore_shortcodes() {
        global $shortcode_tags;
        if ( ! empty($this->temp_shortcode_tags) ) {
            $shortcode_tags = $this->temp_shortcode_tags;
        }
    }
    
    public function enqueueScripts() {
        $theme = get_option("dgsh_theme", 'Default');
        // NOTE: commented out CommonJS script from brushes
        // somehow dojo doesnt like that require line.
        $this->plugin->enqueueScript('shCore', array(), false, false);
        $this->plugin->enqueueStyle('shCore');
        $this->plugin->enqueueStyle('shCore' . $theme);
        $this->plugin->enqueueStyle('shTheme' . $theme);
        
        if (get_option('dgsh_htmlscript', "") !== "") {
            // Setting html-code requires that we have shBrushXml.js loaded
            $this->plugin->enqueueScript('shBrushXml');
        }
    }
    
    public function runHighlighter() {
        if (is_single() || is_page()){
            $opt = new DopeOptions('dgsh_');
            $autolinks = $opt->get('autolinks', "1") !== "" ? "true" : "false";
            $collapse = $opt->get('collapse', "") !== "" ? "true" : "false";
            $gutter = $opt->get('gutter', "1") !== "" ? "true" : "false";
            $toolbar = $opt->get('toolbar', "") !== "" ? "true" : "false";
            $htmlscript = $opt->get('htmlscript', "") !== "" ? "true" : "false";
            $strip_brs = $opt->get('strip_brs', "") !== "" ? "true" : "false";
            
            $view = new SimpleDopeView($this->plugin->getDirectory());
            $view->assign('autolinks', $autolinks)
                    ->assign('classname', esc_attr($opt->get('classname', "")))
                    ->assign('collapse', $collapse)
                    ->assign('gutter', $gutter)
                    ->assign('toolbar', $toolbar)
                    ->assign('htmlscript', $htmlscript)
                    ->assign('tabsize', intval($opt->get('tabsize', 4)))
                    ->assign('strip_brs', $strip_brs)
                    ->render('run-highlighter');
        }
    }
    
    public function defaultAction() {
    }
}
