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

/* series/show.html.twig */
class __TwigTemplate_58fbb8650973105d3e2609845f1733d8 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "series/show.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "series/show.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "series/show.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        echo "Series";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 5
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 6
        echo "    <h1>Series</h1>

    <table class=\"table\">
        <tbody>
            ";
        // line 14
        echo "            <tr>
                <th>Title</th>
                <td>";
        // line 16
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 16, $this->source); })()), "title", [], "any", false, false, false, 16), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>Plot</th>
                <td>";
        // line 20
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 20, $this->source); })()), "plot", [], "any", false, false, false, 20), "html", null, true);
        echo "</td>
            </tr>
            ";
        // line 26
        echo "            <tr>
                <th>Poster</th>
                <td><img src=\"";
        // line 28
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("poster_series_show", ["id" => twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 28, $this->source); })()), "id", [], "any", false, false, false, 28)]), "html", null, true);
        echo "\"/></td>
            </tr>
            <tr>
                <th>Director</th>
                <td>";
        // line 32
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 32, $this->source); })()), "director", [], "any", false, false, false, 32), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>YoutubeTrailer</th>
                <td>";
        // line 36
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 36, $this->source); })()), "youtubeTrailer", [], "any", false, false, false, 36), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>Awards</th>
                <td>";
        // line 40
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 40, $this->source); })()), "awards", [], "any", false, false, false, 40), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>YearStart</th>
                <td>";
        // line 44
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 44, $this->source); })()), "yearStart", [], "any", false, false, false, 44), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>YearEnd</th>
                <td>";
        // line 48
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 48, $this->source); })()), "yearEnd", [], "any", false, false, false, 48), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <ul> <th>Seasons</th>
                <td>
                ";
        // line 53
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_sort_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 53, $this->source); })()), "seasons", [], "any", false, false, false, 53), function ($__a__, $__b__) use ($context, $macros) { $context["a"] = $__a__; $context["b"] = $__b__; return (twig_get_attribute($this->env, $this->source, (isset($context["a"]) || array_key_exists("a", $context) ? $context["a"] : (function () { throw new RuntimeError('Variable "a" does not exist.', 53, $this->source); })()), "number", [], "any", false, false, false, 53) <=> twig_get_attribute($this->env, $this->source, (isset($context["b"]) || array_key_exists("b", $context) ? $context["b"] : (function () { throw new RuntimeError('Variable "b" does not exist.', 53, $this->source); })()), "number", [], "any", false, false, false, 53)); }));
        foreach ($context['_seq'] as $context["_key"] => $context["season"]) {
            // line 54
            echo "                        <li>Saison ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["season"], "number", [], "any", false, false, false, 54), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, $context["season"], "episodes", [], "any", false, false, false, 54)), "html", null, true);
            echo " épisodes)</li>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['season'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 56
        echo "                </td>
                </ul>
                
            </tr>
            <tr>
        </tbody>
    </table>

    <a href=\"";
        // line 64
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_series_index");
        echo "\">back to list</a> 

   ";
        // line 67
        echo "
   ";
        // line 68
        echo " 
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "series/show.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  190 => 68,  187 => 67,  182 => 64,  172 => 56,  161 => 54,  157 => 53,  149 => 48,  142 => 44,  135 => 40,  128 => 36,  121 => 32,  114 => 28,  110 => 26,  105 => 20,  98 => 16,  94 => 14,  88 => 6,  78 => 5,  59 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Series{% endblock %}

{% block body %}
    <h1>Series</h1>

    <table class=\"table\">
        <tbody>
            {# <tr>
                <th>Id</th>
                <td>{{ series.id }}</td>
            </tr> #}
            <tr>
                <th>Title</th>
                <td>{{ series.title }}</td>
            </tr>
            <tr>
                <th>Plot</th>
                <td>{{ series.plot }}</td>
            </tr>
            {#  <tr>
                <th>Imdb</th>
                <td>{{ series.imdb }}</td>
            </tr> #}
            <tr>
                <th>Poster</th>
                <td><img src=\"{{ path('poster_series_show', {'id': series.id} ) }}\"/></td>
            </tr>
            <tr>
                <th>Director</th>
                <td>{{ series.director }}</td>
            </tr>
            <tr>
                <th>YoutubeTrailer</th>
                <td>{{ series.youtubeTrailer }}</td>
            </tr>
            <tr>
                <th>Awards</th>
                <td>{{ series.awards }}</td>
            </tr>
            <tr>
                <th>YearStart</th>
                <td>{{ series.yearStart }}</td>
            </tr>
            <tr>
                <th>YearEnd</th>
                <td>{{ series.yearEnd }}</td>
            </tr>
            <tr>
                <ul> <th>Seasons</th>
                <td>
                {% for season in series.seasons|sort((a, b) => a.number <=> b.number) %}
                        <li>Saison {{ season.number }} ({{ season.episodes|length }} épisodes)</li>
                {% endfor %}
                </td>
                </ul>
                
            </tr>
            <tr>
        </tbody>
    </table>

    <a href=\"{{ path('app_series_index') }}\">back to list</a> 

   {# <a href=\"{{ path('app_series_edit', {'id': series.id}) }}\">edit</a> #}

   {#  {{ include('series/_delete_form.html.twig') }} #} 
{% endblock %}
", "series/show.html.twig", "/mnt/roost/users/ymerciertall/S3/projetFinalS3/sae_s3.01/SAE_S3.01/templates/series/show.html.twig");
    }
}
