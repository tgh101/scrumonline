<?php
include "config.php";
include "templates/templates.php";


$templates = Template::getAll();

// Find all templates with their own navigation item
$navItems = [];
foreach($templates as $index=>$template)
{
  if ($template->isNavigation)
    $navItems[$index] = $template;
}
?>
<!doctype html>
<html class="no-js" lang="en-EN">
<head>
  <meta charset="utf-8">
  <base href="/">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Scrumpoker Online</title>
  <meta name="description" content="Scrumpoker online is an open source web implementation of planning poker for scrum teams to determine the complexity of stories. It aims to integrate ticketing systems like Redmine, Github and Gitlab.">  
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="apple-touch-icon.png">
    
  <script src="/js/modernizr-2.8.3.min.js"></script>
  
  <!-- Style sheets -->
  <link rel="stylesheet" href="/css/bootstrap.min.css">

  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="/css/scrumonline.css">

  <!-- Pretty cookie consent and styling -->
  <?php include("templates/cookie_notice.php") ?>

  <!-- AdSense code -->
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body ng-app="scrum-online">
<!--[if lt IE 8]>
   <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<?php if($layout_switch['enable_fork_banner']){?>
    <!--Github Fork Badge -->
    <div class="github-fork-ribbon-wrapper hidden-xs">
      <div class="github-fork-ribbon">
        <a target="_blank" href="">Fork me on GitHub</a>
      </div>
    </div>
<?php }?>
<!-- Top navigation bar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    	<a class="navbar-brand" href="/">Scrum Poker</a>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
      <?php foreach($navItems as $navItem): ?>
        <li data-toggle="collapse" data-target=".navbar-collapse.in"><a href="<?php echo $navItem->link ?>"><?php echo $navItem->navigationTag ?></a></li>
      <?php endforeach; ?>
      </ul>
    </div> <!--/.nav-collapse -->
  </div>
</nav>

<!-- Add your site or application content here -->
<div class="container-fluid main" ng-view></div>

<!-- Side banner -->
<div id="banner_left" class="side-banner"></div>
<div id="banner_right" class="side-banner"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-route.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-cookies.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-sanitize.min.js"></script>
<script src="https://cdn.rawgit.com/showdownjs/showdown/1.8.6/dist/showdown.min.js"></script>  
<script src="/js/angular-google-analytics.js"></script>
<script src="/js//bootstrap.min.js"></script>
<script src="/js/J2M.js"></script>
<script type="text/javascript">
  var ga_id = '<?= $ga ?>';
  var disableStr = 'ga-disable-' + ga_id; 
  if (document.cookie.indexOf(disableStr + '=true') > -1) { 
      window[disableStr] = true;
  } 
  function gaOptout() { 
      document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/'; 
      window[disableStr] = true; 
      alert('Tracking disabled'); 
  }

  var cardSets = [
<?php foreach($cardSets as $key=>$cardSet) { ?>
    { set: <?= $key ?>, cards: <?= json_encode($cardSet) ?>  },
<?php } ?>
  ];
</script>
<script src="/js/main.js"></script>
<?php foreach($plugins as $plugin) {?>
<script src="/js/<?= strtolower($plugin) ?>-plugin.js"></script>
<?php } ?>
  
<!-- Templates of the page -->
<?php
  foreach($templates as $template)
  {
     $template->render();
  }
?>
</body>
</html>
