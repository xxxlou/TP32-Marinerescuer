<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* navigation/tree/controls.twig */
class __TwigTemplate_57544ddfab12893f6defce1fafa14135a0dd9fc551d5e523678a4bb643d7fab7 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!-- CONTROLS START -->
<li id=\"navigation_controls_outer\">
    <div id=\"navigation_controls\">
        ";
        // line 4
        echo ($context["collapse_all"] ?? null);
        echo "
        ";
        // line 5
        echo ($context["unlink"] ?? null);
        echo "
    </div>
</li>
<!-- CONTROLS ENDS -->
";
    }

    public function getTemplateName()
    {
        return "navigation/tree/controls.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 5,  42 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "navigation/tree/controls.twig", "/bitnami/wordpress/wp-content/plugins/wp-phpmyadmin-extension/lib/phpMyAdmin_s9zvHjLrkRJyIgnPCM70hWp/templates/navigation/tree/controls.twig");
    }
}
