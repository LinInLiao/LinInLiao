<!doctype html>
<html class="no-js">
  <head>
    <!--[if lt IE 10]>
    <link rel="stylesheet" href="/css/ie.css">
    <![endif]-->
    <title>{{ title }}</title>
  </head>
  <body>

    <!--[if lte IE 9]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="page-wrapper">
      <div id="header"></div>
      {% block content %}{% endblock %}
    </div>
    {% if liveload is true %}
      <script src="/bundle.js"></script>
    {% else %}
      <script src="/js/bundle.js"></script>
    {% endif %}
  </body>
</html>
