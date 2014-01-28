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
        $section_plugin = new SimpleDopeSettingsSection("Plugin", __("Plugin settings", 'dg-syntaxhighlighter'), __("Control the way the plugin works.", 'dg-syntaxhighlighter'));
        $settings->addSection($section_plugin);
        
        $section_plugin->addTextField('dgsh_tag', 'code', __('Shortcode tag', 'dg-syntaxhighlighter'), '');
        
        $shortcut_tags = implode(', ', array_keys(dgshShortCode::generateBrushMap()));        
        $shortcut_description = sprintf(__('Register additional tags such as [php] [css] [c++]. Available tags:<br />%s', 'dg-syntaxhighlighter'), $shortcut_tags);;
        $section_plugin->addCheckboxField('dgsh_shortcut', true, __('Enable shortcut', 'dg-syntaxhighlighter'), $shortcut_description);
        
        $section_plugin->addCheckboxField('dgsh_comments', true, __('Shortcode on comments', 'dg-syntaxhighlighter'), __('Enable shortcode on comments.', 'dg-syntaxhighlighter'));
        
        /* Syntaxhighlighter Section */
        $section_sh = new SimpleDopeSettingsSection('Syntaxhighlighter', __('Syntaxhighlighter settings', 'dg-syntaxhighlighter'), __("Modify the look & feel of the syntaxhighlighter.", 'dg-syntaxhighlighter'));
        $settings->addSection($section_sh);
        
        $field_theme = new SimpleDopeSettingsField("dgsh_theme", __("Theme", 'dg-syntaxhighlighter'));
        $options = DopeFormInputSelect::singleArrayToKV(dgshShortCode::generateThemeNames());
        $dropdown = new DopeFormInputSelect("dgsh_theme", $options);
        $dropdown->setSelected($opt->get('theme', 'Default'));
        $field_theme->setTemplate($dropdown);
        $section_sh->addField($field_theme);

        $section_sh->addCheckboxField('dgsh_autolinks', true, __('Auto-links', 'dg-syntaxhighlighter'), __('Allows you to turn detection of links in the highlighted element on and off. If the option is turned off, URLs won’t be clickable.', 'dg-syntaxhighlighter'));        
        $section_sh->addTextField('dgsh_classname', '', __('Class name', 'dg-syntaxhighlighter'), __('Allows you to add a custom class (or multiple classes) to every highlighter element that will be created on the page.', 'dg-syntaxhighlighter'));        
        $section_sh->addCheckboxField('dgsh_collapse', false, __('Collapse', 'dg-syntaxhighlighter'), __('Allows you to force highlighted elements on the page to be collapsed by default.', 'dg-syntaxhighlighter'));
        $section_sh->addCheckboxField('dgsh_gutter', true, __('Gutter', 'dg-syntaxhighlighter'), __('Allows you to turn gutter with line numbers on and off.', 'dg-syntaxhighlighter'));
        $section_sh->addNumberField('dgsh_tabsize', 4, __('Tab size', 'dg-syntaxhighlighter'), __('Allows you to adjust tab size.', 'dg-syntaxhighlighter'));
        $section_sh->addCheckboxField('dgsh_toolbar', false, __('Toolbar', 'dg-syntaxhighlighter'), __('Toggles toolbar on/off.', 'dg-syntaxhighlighter'));
        $section_sh->addCheckboxField("dgsh_htmlscript", false, __("Html script", 'dg-syntaxhighlighter'), __('Allows you to highlight a mixture of HTML/XML code and a script which is very common in web development.', 'dg-syntaxhighlighter'));
        $section_sh->addCheckboxField('strip_brs', false, esc_html(__("Strip <br />'s", 'dg-syntaxhighlighter')), esc_html(__('If your software adds <br /> tags at the end of each line, this option allows you to ignore those.', 'dg-syntaxhighlighter')));
        
        $settings->register();
    }
}