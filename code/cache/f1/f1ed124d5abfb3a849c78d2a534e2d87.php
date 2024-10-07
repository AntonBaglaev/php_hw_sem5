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

/* main.twig */
class __TwigTemplate_e5428d68791c18ab97ff5c5a8f0d1a77 extends Template
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
        echo "<!DOCTYPE html>
<html random=";
        // line 2
        echo twig_escape_filter($this->env, ($context["random_int"] ?? null), "html", null, true);
        echo ">
    <head>
        <title>";
        // line 4
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</title>
        <style>";
        // line 5
        echo twig_escape_filter($this->env, ($context["style"] ?? null), "html", null, true);
        echo "</style>
        <link href=\"style.css\" rel=\"stylesheet\" >
    </head>
    <body class=\"body\">
";
        // line 14
        echo "    ";
        $this->loadTemplate("header.twig", "main.twig", 14)->display($context);
        // line 15
        echo "    <div class=\"container\">
        <div class=\"box-left\">
            <p>Пункт 1</p>
            <p>Пункт 2</p>
        </div>
        <div class=\"box-right\">
            ";
        // line 21
        $this->loadTemplate(($context["content_template_name"] ?? null), "main.twig", 21)->display($context);
        // line 22
        echo "        </div>
    </div>
    ";
        // line 24
        $this->loadTemplate("footer.twig", "main.twig", 24)->display($context);
        // line 25
        echo "    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "main.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  75 => 25,  73 => 24,  69 => 22,  67 => 21,  59 => 15,  56 => 14,  49 => 5,  45 => 4,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "main.twig", "/data/mysite.local/src/Domain/Views/main.twig");
    }
}
