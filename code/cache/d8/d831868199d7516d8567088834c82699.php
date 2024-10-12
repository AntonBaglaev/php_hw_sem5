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

/* user-index.twig */
class __TwigTemplate_db2cf526164e2e612e423d6c4c51e316 extends Template
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
        echo "<p>Список пользователей в хранилище</p>

<div class=\"table-responsive small\">
        <table class=\"table table-striped table-sm\">
                <thead>
                <tr>
                        <th scope=\"col\">ID</th>
                        <th scope=\"col\">Имя</th>
                        <th scope=\"col\">Фамилия</th>
                        <th scope=\"col\">День рождения</th>
                        ";
        // line 11
        if (($context["isAdmin"] ?? null)) {
            // line 12
            echo "                                <th scope=\"col\">Редактирование</th>
                                <th scope=\"col\">Удаление</th>
                        ";
        }
        // line 15
        echo "                </tr>
                </thead>
                <tbody>
                ";
        // line 18
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["users"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
            // line 19
            echo "                        <tr>
                                <td>";
            // line 20
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 20), "html", null, true);
            echo "</td>
                                <td>";
            // line 21
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserName", [], "method", false, false, false, 21), "html", null, true);
            echo "</td>
                                <td>";
            // line 22
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserLastName", [], "method", false, false, false, 22), "html", null, true);
            echo "</td>
                                <td>";
            // line 23
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["user"], "getUserBirthday", [], "method", false, false, false, 23))) {
                // line 24
                echo "                                                ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserBirthday", [], "method", false, false, false, 24), "d.m.Y"), "html", null, true);
                echo "
                                        ";
            } else {
                // line 26
                echo "                                                <b>Не задан</b>
                                        ";
            }
            // line 28
            echo "                                </td>
                                ";
            // line 29
            if (($context["isAdmin"] ?? null)) {
                // line 30
                echo "                                        <td scope=\"col\"><a href=\"/user/edit/?id_user=";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 30), "html", null, true);
                echo "\">Редактирование</a></td>
                                        <td scope=\"col\"><a href=\"/user/delete/?id_user=";
                // line 31
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 31), "html", null, true);
                echo "\">Удаление</a></td>
                                ";
            }
            // line 33
            echo "                        </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "                </tbody>
        </table>
</div>

<script>
        let maxId = \$('.table-responsive tbody tr:last-child td:first-child').html();

        setInterval(function () {
                \$.ajax({
                        method: 'POST',
                        url: \"/user/indexRefresh/\",
                        data: { maxId: maxId }
                }).done(function (response) {
                        // \$('.content-template').html(response);

                        let users = \$.parseJSON(response);

                        if(users.length != 0){
                                for(var k in users){

                                        let row = \"<tr>\";

                                        row += \"<td>\" + users[k].id + \"</td>\";
                                        maxId = users[k].id;

                                        row += \"<td>\" + users[k].username + \"</td>\";
                                        row += \"<td>\" + users[k].userlastname + \"</td>\";
                                        row += \"<td>\" + users[k].userbirthday + \"</td>\";

                                        row += \"</tr>\";

                                        \$('.content-template tbody').append(row);
                                }

                        }

                });
        }, 10000);
</script>";
    }

    public function getTemplateName()
    {
        return "user-index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  114 => 35,  107 => 33,  102 => 31,  97 => 30,  95 => 29,  92 => 28,  88 => 26,  82 => 24,  80 => 23,  76 => 22,  72 => 21,  68 => 20,  65 => 19,  61 => 18,  56 => 15,  51 => 12,  49 => 11,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "user-index.twig", "/data/mysite.local/src/Domain/Views/user-index.twig");
    }
}
