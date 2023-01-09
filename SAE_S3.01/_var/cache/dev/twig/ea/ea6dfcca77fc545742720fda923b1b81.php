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

/* series/index.html.twig */
class __TwigTemplate_cad3f093493d45928794c9ad387938e9 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "series/index.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "series/index.html.twig"));

        // line 2
        $context["pagination"] = 10;
        // line 1
        $this->parent = $this->loadTemplate("base.html.twig", "series/index.html.twig", 1);
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

        echo "Series index";
        
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
        echo "    <h1>Series index</h1>

    <table class=\"table\">
        <thead>
            <tr>
                <th>Title</th>
                <th>Poster</th>
                <th>Director</th>
                <th>YoutubeTrailer</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>

        ";
        // line 20
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($context["series"]);
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["series"]) {
            // line 21
            echo "            <tr>
                <td>";
            // line 22
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["series"], "title", [], "any", false, false, false, 22), "html", null, true);
            echo "</td>
                <td><img src=\"";
            // line 23
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("poster_series_show", ["id" => twig_get_attribute($this->env, $this->source, $context["series"], "id", [], "any", false, false, false, 23)]), "html", null, true);
            echo "\"/></td>
                <td>";
            // line 24
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["series"], "director", [], "any", false, false, false, 24), "html", null, true);
            echo "</td>
                <td>";
            // line 25
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["series"], "youtubeTrailer", [], "any", false, false, false, 25), "html", null, true);
            echo "</td>
                <td>
                    <a href=\"";
            // line 27
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_series_show", ["id" => twig_get_attribute($this->env, $this->source, $context["series"], "id", [], "any", false, false, false, 27)]), "html", null, true);
            echo "\">show</a>
                    <input type=\"checkbox\" onchange=\"followSerie(series.id)\">
                    
                </td>
            </tr>
        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 33
            echo "            <tr>
                <td colspan=\"11\">no records found</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['series'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 37
        echo "        ";
        if ((array_key_exists("page", $context) && (((isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 37, $this->source); })()) - (isset($context["pagination"]) || array_key_exists("pagination", $context) ? $context["pagination"] : (function () { throw new RuntimeError('Variable "pagination" does not exist.', 37, $this->source); })())) >= 0))) {
            // line 38
            echo "            <a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_series_index_page", ["page" => ((isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 38, $this->source); })()) - (isset($context["pagination"]) || array_key_exists("pagination", $context) ? $context["pagination"] : (function () { throw new RuntimeError('Variable "pagination" does not exist.', 38, $this->source); })()))]), "html", null, true);
            echo "\"> précédent </a>

            <a href=\"";
            // line 40
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_series_index_page", ["page" => ((isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 40, $this->source); })()) + (isset($context["pagination"]) || array_key_exists("pagination", $context) ? $context["pagination"] : (function () { throw new RuntimeError('Variable "pagination" does not exist.', 40, $this->source); })()))]), "html", null, true);
            echo "\"> suivant </a>
        ";
        } else {
            // line 42
            echo "        <a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_series_index_page", ["page" => twig_length_filter($this->env, (isset($context["series"]) || array_key_exists("series", $context) ? $context["series"] : (function () { throw new RuntimeError('Variable "series" does not exist.', 42, $this->source); })()))]), "html", null, true);
            echo "\"> suivant </a>
        ";
        }
        // line 44
        echo "

         ";
        // line 47
        echo "         ";
        // line 56
        echo "                </td>
            </tr>
            #}
        </tbody>
    </table>
   ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "series/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  178 => 56,  176 => 47,  172 => 44,  166 => 42,  161 => 40,  155 => 38,  152 => 37,  143 => 33,  132 => 27,  127 => 25,  123 => 24,  119 => 23,  115 => 22,  112 => 21,  107 => 20,  91 => 6,  81 => 5,  62 => 3,  51 => 1,  49 => 2,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}
{% set pagination = 10 %}
{% block title %}Series index{% endblock %}

{% block body %}
    <h1>Series index</h1>

    <table class=\"table\">
        <thead>
            <tr>
                <th>Title</th>
                <th>Poster</th>
                <th>Director</th>
                <th>YoutubeTrailer</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>

        {% for series in series %}
            <tr>
                <td>{{ series.title }}</td>
                <td><img src=\"{{ path('poster_series_show', {'id': series.id} ) }}\"/></td>
                <td>{{ series.director }}</td>
                <td>{{ series.youtubeTrailer }}</td>
                <td>
                    <a href=\"{{ path('app_series_show', {'id': series.id}) }}\">show</a>
                    <input type=\"checkbox\" onchange=\"followSerie(series.id)\">
                    
                </td>
            </tr>
        {% else %}
            <tr>
                <td colspan=\"11\">no records found</td>
            </tr>
        {% endfor %}
        {% if page is defined and page-pagination >= 0 %}
            <a href=\"{{ path('app_series_index_page', {'page': page-pagination } ) }}\"> précédent </a>

            <a href=\"{{ path('app_series_index_page', {'page': page+pagination } ) }}\"> suivant </a>
        {% else %}
        <a href=\"{{ path('app_series_index_page', {'page': series|length } ) }}\"> suivant </a>
        {% endif %}


         {# Permet de gérer l'aleatoire #}
         {#
            {% set randomSerie = random(series) %}
            <td>{{ randomSerie.title }}</td>
                <td><img src=\"{{ path('poster_series_show', {'id': randomSerie.id} ) }}\"/></td>
                <td>{{ randomSerie.director }}</td>
                <td>{{ randomSerie.youtubeTrailer }}</td>
                <td>
                    <a href=\"{{ path('app_series_show', {'id': randomSerie.id}) }}\">show</a>
                    {# <a href=\"{{ path('app_series_edit', {'id': randomSerie.id}) }}\">edit</a> #}
                </td>
            </tr>
            #}
        </tbody>
    </table>
   {# <a href=\"{{ path('app_series_new') }}\">Create new</a> #}
{% endblock %}

", "series/index.html.twig", "/mnt/roost/users/ymerciertall/S3/projetFinalS3/sae_s3.01/SAE_S3.01/templates/series/index.html.twig");
    }
}
