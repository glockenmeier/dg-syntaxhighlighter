<?php

/*
 * Copyright 2014, Darius Glockenmeier.
 */

/*
 * Description of dgshAdminController
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * @package your_package_name_here
 * 
 */
class dgshAdminController extends DopeController {
    
    public function __construct(DopePlugin $plugin) {
        parent::__construct($plugin);
        $this->init_settings();
    }
    
    public function init_settings() {
        $opt = new DopeOptions('dgsh_');
        
        $settings = new DopeSettings("dg-syntaxhighlighter");
        $settings->addOptionsPage("DG's SyntaxHighlighter", "Syntaxhighlighter");
        
        /* Plugin Section */
        $section_plugin = new SimpleDopeSettingsSection("Plugin", "Plugin settings", "Control the way the plugin works.");
        $settings->addSection($section_plugin);
        
        $section_plugin->addTextField('dgsh_tag', 'code', 'Shortcode tag', '');
        
        $shortcut_tags = implode(', ', array_keys(dgshShortCode::generateBrushMap()));        
        $shortcut_description = sprintf('Register additional tags such as [php] [css] [c++]. Available tags:<br />%s', $shortcut_tags);;
        $section_plugin->addCheckboxField('dgsh_shortcut', true, 'Enable shortcut', $shortcut_description);
        
        $section_plugin->addCheckboxField('dgsh_comments', true, 'Shortcode on comments', 'Enable shortcode on comments.');
        
        /* Syntaxhighlighter Section */
        $section_sh = new SimpleDopeSettingsSection('Syntaxhighlighter', 'Syntaxhighlighter settings', "Modify the look & feel of the syntaxhighlighter.");
        $settings->addSection($section_sh);
        
        $field_theme = new SimpleDopeSettingsField("dgsh_theme", "Theme");
        $options = DopeFormInputSelect::singleArrayToKV(dgshShortCode::generateThemeNames());
        $dropdown = new DopeFormInputSelect("dgsh_theme", $options);
        $dropdown->setSelected($opt->get('theme', 'Default'));
        $field_theme->setTemplate($dropdown);
        $section_sh->addField($field_theme);

        $section_sh->addCheckboxField('dgsh_autolinks', true, 'Auto-links', 'Allows you to turn detection of links in the highlighted element on and off. If the option is turned off, URLs won’t be clickable.');        
        $section_sh->addTextField('dgsh_classname', '', 'Class name', 'Allows you to add a custom class (or multiple classes) to every highlighter element that will be created on the page.');        
        $section_sh->addCheckboxField('dgsh_collapse', false, 'Collapse', 'Allows you to force highlighted elements on the page to be collapsed by default.');
        $section_sh->addCheckboxField('dgsh_gutter', true, 'Gutter', 'Allows you to turn gutter with line numbers on and off.');
        $section_sh->addNumberField('dgsh_tabsize', 4, 'Tab size', 'Allows you to adjust tab size.');
        $section_sh->addCheckboxField('dgsh_toolbar', false, 'Toolbar', ' Toggles toolbar on/off.');
        $section_sh->addCheckboxField("dgsh_htmlscript", false, "Html script", 'Allows you to highlight a mixture of HTML/XML code and a script which is very common in web development.');
        
        $settings->register();
    }
}