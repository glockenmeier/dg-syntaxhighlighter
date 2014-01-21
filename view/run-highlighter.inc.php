<?php

/*
 * Copyright 2013, Darius Glockenmeier.
 * 
 * Description of run-highlighter
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * 
 */

?>
<script type="text/javascript">
    SyntaxHighlighter.defaults['auto-links'] = <?php echo $this->autolinks ?>;
    SyntaxHighlighter.defaults['class-name'] = '<?php echo $this->classname?>';
    SyntaxHighlighter.defaults['collapse'] = <?php echo $this->collapse ?>;
    SyntaxHighlighter.defaults['first-line'] = 1;
    SyntaxHighlighter.defaults['gutter'] = <?php echo $this->gutter ?>;
    SyntaxHighlighter.defaults['highlight'] = null;
    SyntaxHighlighter.defaults['html-script'] = <?php echo $this->htmlscript ?>;
    SyntaxHighlighter.defaults['smart-tabs'] = true;
    SyntaxHighlighter.defaults['tab-size'] = <?php echo $this->tabsize ?>;
    SyntaxHighlighter.defaults['toolbar'] = <?php echo $this->toolbar ?>;
    SyntaxHighlighter.all()
</script>