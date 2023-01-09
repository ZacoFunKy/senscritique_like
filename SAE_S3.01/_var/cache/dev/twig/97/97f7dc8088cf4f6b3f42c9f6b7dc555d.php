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

/* default/index.html.twig */
class __TwigTemplate_310a262f46d936b0eb23bd59d6ae2496 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "default/index.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "default/index.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "default/index.html.twig", 1);
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

        echo "Netflop France - Accueil";
        
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
        echo "<style>
    @import url('https://fonts.googleapis.com/css2?family=Rancho&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Concert+One&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
        color: white;
        margin:0;
    }

    html{
        height: 100%;
    }

    body {
        background-image: url('fond.jpg'); /* A réparer */
    }

    #tout{
        background-color: rgba(0, 0, 0, 0.534);
        height: 100vh;
    }

    header {
        display:flex;
        justify-content:space-between;
        background: linear-gradient(black 10%, transparent);
    }
    header * {
        margin: 2% 2%;
    }
    header a {      
        color: #f44336;
        font-family: 'Bebas Neue', sans-serif;
        font-size: 3vw;
        letter-spacing: 4px;
        text-shadow: 5px 3px 10px rgb(0, 0, 0);
        text-decoration: none;
    }
    #id {
        display:flex;
    }
    #id input {
        background-color: #f44336;
        border: none;
        color: black;
        padding: 10px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 4px;
        margin-right: 10%;
        cursor: pointer;
    }
    #id a{
        margin-right: 20px;
    }
        
    #content {
        display:flex;
        flex-direction:column;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.63);
        margin-right:2%;
        margin-left:2%;
        height:fit-content;
        padding-bottom: 0.5%;
    }
    #content h1 {
       margin-top: 5%;
    }
    #content ul {
        display:flex;
        margin-top:3%;
        list-style-type: none;
    }
    #content img {
        height: 600px;
    }
    #content li {
        margin: 0 2vw;
    }
</style>

<div id=\"tout\">
    <header>
        <a href=\"page.html\">Netflop</a>
        <div id=\"id\">
            <a href=\"connexion.html\"><input name=\"connexion\" type=\"submit\" value=\"Connexion\"></a>
            <a href=\"inscription.html\"><input name=\"inscription\" type=\"submit\" value=\"Inscription\"></a>
        </div>
    </header>
    <div id= \"content\">
        <h1>Quelques séries de notre catalogue:</h1>
        <ul>
            <li><img src=\"got.jpg\" alt=\"got\"> </li>
            <li><img src=\"volt.jpg\" alt=\"got\"> </li>
            <li><img src=\"interstellar.jpg\" alt=\"got\"> </li>
        </ul>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "default/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 6,  78 => 5,  59 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Netflop France - Accueil{% endblock %}

{% block body %}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Rancho&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Concert+One&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
        color: white;
        margin:0;
    }

    html{
        height: 100%;
    }

    body {
        background-image: url('fond.jpg'); /* A réparer */
    }

    #tout{
        background-color: rgba(0, 0, 0, 0.534);
        height: 100vh;
    }

    header {
        display:flex;
        justify-content:space-between;
        background: linear-gradient(black 10%, transparent);
    }
    header * {
        margin: 2% 2%;
    }
    header a {      
        color: #f44336;
        font-family: 'Bebas Neue', sans-serif;
        font-size: 3vw;
        letter-spacing: 4px;
        text-shadow: 5px 3px 10px rgb(0, 0, 0);
        text-decoration: none;
    }
    #id {
        display:flex;
    }
    #id input {
        background-color: #f44336;
        border: none;
        color: black;
        padding: 10px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 4px;
        margin-right: 10%;
        cursor: pointer;
    }
    #id a{
        margin-right: 20px;
    }
        
    #content {
        display:flex;
        flex-direction:column;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.63);
        margin-right:2%;
        margin-left:2%;
        height:fit-content;
        padding-bottom: 0.5%;
    }
    #content h1 {
       margin-top: 5%;
    }
    #content ul {
        display:flex;
        margin-top:3%;
        list-style-type: none;
    }
    #content img {
        height: 600px;
    }
    #content li {
        margin: 0 2vw;
    }
</style>

<div id=\"tout\">
    <header>
        <a href=\"page.html\">Netflop</a>
        <div id=\"id\">
            <a href=\"connexion.html\"><input name=\"connexion\" type=\"submit\" value=\"Connexion\"></a>
            <a href=\"inscription.html\"><input name=\"inscription\" type=\"submit\" value=\"Inscription\"></a>
        </div>
    </header>
    <div id= \"content\">
        <h1>Quelques séries de notre catalogue:</h1>
        <ul>
            <li><img src=\"got.jpg\" alt=\"got\"> </li>
            <li><img src=\"volt.jpg\" alt=\"got\"> </li>
            <li><img src=\"interstellar.jpg\" alt=\"got\"> </li>
        </ul>
    </div>
</div>
{% endblock %}
", "default/index.html.twig", "/mnt/roost/users/ymerciertall/S3/projetFinalS3/sae_s3.01/SAE_S3.01/templates/default/index.html.twig");
    }
}
