<!doctype html>
<html class="no-js">
  <head>
    <!--[if lt IE 10]>
    <link rel="stylesheet" href="/css/ie.css">
    <![endif]-->
    <script src="/js/react.js"></script>
    <script src="/js/JSXTransformer.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
  </head>
  <body>

    <!--[if lte IE 9]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    {% block content %}{% endblock %}


    <?php $this->assets->outputJs(); ?>
  </body>
</html>
