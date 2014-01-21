<?php
/*
 * Copyright 2014, Darius Glockenmeier.
 */

/* 
 * Description of dgshShortCode
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * @package model
 * 
 */
class dgshShortCode extends DopeShortcode {
    private $brush_map = array();
    private static $loaded_brushes = array();
    private $plugin;
    
    public function __construct($tag, DopePlugin $plugin) {
        parent::__construct($tag);
        $this->plugin = $plugin;
        $this->brush_map = dgshShortCode::generateBrushMap();

        if (get_option('dgsh_shortcut', '1') !== '') {
            $this->registerAllShortcodes();
        }
    }
    
    protected function registerAllShortcodes() {
        foreach (array_keys($this->brush_map) as $tag) {
            $sc = new dgshGenericShortcode($tag, $this);
            $sc->add();
        }
    }
    
    protected function processShortcode($atts, $content = '') {
        $atts = shortcode_atts($this->getDefaultAttributes(), $atts, $this->tag);
        $content = str_replace('<', '&lt;', $content);
        $content = str_replace('>', '&gt;', $content);
        $brush = $atts['brush'];
        $load = $this->loadBrush($brush);
        
        if ($load === false){
            return "[$this->tag] invalid brush: " . $brush;
        }
        
        $htmlscript = $atts['htmlscript'] === "false" ? "" : "html-script: true;";
        $gutter = $atts['gutter'] === "true" ? "" : "gutter: false;";
        $firstline = $atts['firstline'] === "1" ? "" :  sprintf("first-line: %s;", $atts['firstline']);
        
        
        return sprintf('<pre class="brush: %s %s %s %s">%s</pre>', 
                strtolower($this->brush_map[$brush]), $htmlscript, $gutter,
                $firstline, $content);;
    }
    
    private function loadBrush($brush_attr) {
        if ( ! array_key_exists($brush_attr, $this->brush_map) ) {
            return false; // invalid brush
        }
        $brush = $this->brush_map[$brush_attr];
        if (array_search($brush, dgshShortCode::$loaded_brushes) !== false) {
            return true; // brush already loaded
        }
        array_push(dgshShortCode::$loaded_brushes, $brush);
        $this->plugin->enqueueScript('shBrush' . $brush);
        return true;
    }
    
    private function getDefaultAttributes() {
        return array(
            "brush" => "plain",
            "htmlscript" => "false",
            "gutter" => "true",
            "firstline" => "1"
        );
    }
    
    public static function generateBrushMap() {
        return array(
            "php" => "Php",
            "js" => "JScript",
            "javascript" => "JScript",
            "sql" => "Sql",
            "css" => "Css",
            "plain" => "Plain",
            "text" => "Plain",
            "cpp" => "Cpp",
            "c++" => "Cpp",
            "c" => "Cpp",
            "as3" => "AS3",
            "actionscript" => "AS3",
            "applescript" => "AppleScript",
            "bash" => "Bash",
            "csharp" => "CSharp",
            "cs" => "CSharp",
            "c#" => "CSharp",
            "coldfusion" => "ColdFusion",
            "cf" => "ColdFusion",
            "delphi" => "Delphi",
            "diff" => "Diff",
            "erlang" => "Erlang",
            "groovy" => "Groovy",
            "java" => "Java",
            "javafx" => "JavaFX",
            "perl" => "Perl",
            "php" => "Php",
            "powershell" => "PowerShell",
            "ps" => "PowerShell",
            "python" => "Python",
            "py" => "Python",
            "ruby" => "Ruby",
            "rb" => "Ruby",
            "sass" => "Sass",
            "scala" => "Scala",
            "visualbasic" => "Vb",
            "vb" => "Vb",
            "xml" => "Xml"
        );
        
    }
    
    public static function generateThemeNames() {
        return array(
            "Default",
            "Django",
            "Eclipse",
            "Emacs",
            "FadeToGrey",
            "MDUltra",
            "Midnight",
            "RDark",
            
        );
    }
}