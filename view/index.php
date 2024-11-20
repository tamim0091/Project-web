<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<title>ChronoVoyage</title>

  	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="">
  	<meta name="description" content="">

	<!-- stylesheets css -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/loaders.css"/>
  	<link rel="stylesheet" href="css/magnific-popup.css">
	<link rel="stylesheet" href="css/animate.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
  	<link rel="stylesheet" href="css/nivo-lightbox.css">
  	<link rel="stylesheet" href="css/nivo_themes/default/default.css">
  	<link rel="stylesheet" href="css/hover-min.css">
    <link rel="stylesheet" href="css/contact-input-style.css">

  	
</head>
<body>
 <div class="loader loader-bg">
        <div class="loader-inner ball-pulse-rise">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>

<!------------Static navbar ------------>
    <nav class="navbar navbar-default top-bar affix" data-spy="affix" data-offset-top="250" >
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Chronovoyage</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#banner">Home</a></li>
              <li><a href="login.php">Sign in</a></li>
              <li><a href="createuser.php">sign up</a></li>
              <li><a href="index4.html">AboutUs</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>


    
<!------------ Home Banner ------------>
<section id="banner" class="parallax">
  <div class="gradient-overlay"></div>
    <div class="container">
      <div class="row">

          <div class="col-md-offset-2 col-md-8 col-sm-12">
              <h1 class="wow fadeInUp" data-wow-delay="1s">Chronovoyage</h1>
              <p class="wow fadeInUp" data-wow-delay="1s"><br>
                  <small>ChronoVoyage est une plateforme immersive pour explorer l'histoire et la culture tunisiennes. Partez à la découverte des sites historiques, des personnages marquants, et des événements qui ont façonné la Tunisie, grâce à une carte interactive, des visites virtuelles, et des quiz éducatifs. Plongez dans le passé et découvrez la richesse du patrimoine tunisien !.</small>
                  </p>
              <a href="#todo" class="wow fadeInUp btn btn-transparent-white btn-capsul btn-lg smoothScroll" data-wow-delay="1.3s">Discover Now</a>
          </div>

      </div>
    </div>
</section>


<!------------ todo Section ------------>
<section id="todo" class="parallax">
  <div class="container">
    <div class="row">

      <div class="col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 text-center">
          <div class="wow fadeInUp section-title" data-wow-delay="0.6s">
            <h2>Chronovoyage<small>Preparing for a Road Trip</small></h2>
          </div>
      </div>

      <div class="clearfix"></div>

      <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
        <div class="feature-thumb">
          <div class="feature-icon">
             <span><img class="img-responsive" src="images/todo-icon-01.png" /></span>
          </div>
          <h3>Chronovoyage</h3>
          <p>Chronovoyage est un concept de voyage temporel qui offre la possibilité d'explorer des époques passées ou futures. Imaginez pouvoir parcourir les grands moments de l'Histoire, visiter des civilisations disparues ou plonger dans des futurs possibles.</p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.6s">
        <div class="feature-thumb">
          <div class="feature-icon">
            <span><img class="img-responsive" src="images/todo-icon-02.png" /></span>
          </div>
          <h3>Site Historique</h3>
          <p>Découvrez des lieux historiques fascinants, empreints de récits et d'événements qui ont marqué le cours du temps. Cette section vous invite à explorer des sites uniques, riches en patrimoine et en culture, où chaque pierre raconte une histoire</p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.9s">
        <div class="feature-thumb">
          <div class="feature-icon">
            <span><img class="img-responsive" src="images/todo-icon-03.png" /></span>
          </div>
           <h3>plats</h3>
           <p>Les plats traditionnels tunisiens sont une invitation à un voyage culinaire au cœur de la Méditerranée et du Maghreb. 
            Riche en saveurs, cette cuisine combine des influences berbères, arabes, andalouses, et méditerranéennes, offrant des recettes généreuses et épicées..</p>
        </div>
      </div>
        
        <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
        <div class="feature-thumb">
          <div class="feature-icon">
             <span><img class="img-responsive" src="images/todo-icon-04.png" /></span>
          </div>
          <h3>Contribution Utilisateur</h3>
          <p>permet aux visiteurs de votre site de participer activement en partageant leurs propres expériences, connaissances et avis. Qu’il s’agisse de commentaires, d’histoires personnelles, 
            de photos ou de recommandations, chaque contribution enrichit la communauté et apporte une perspective unique.</p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.6s">
        <div class="feature-thumb">
          <div class="feature-icon">
            <span><img class="img-responsive" src="images/todo-icon-05.png" /></span>
          </div>
          <h3>Personnage historique</h3>
          <p>Les personnages historiques sont des figures marquantes dont les actions, idées, ou réalisations ont eu un impact majeur sur leur époque et parfois sur l’histoire du monde entie.</p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.9s">
        <div class="feature-thumb">
          <div class="feature-icon">
            <span><img class="img-responsive" src="images/todo-icon-06.png" /></span>
          </div>
          <a class="navbar-brand" href="epoque.html">Epoque et civilisations</a>
        </div>
      </div>

    </div>
  </div>
</section>


<!------------ Video section ------------>
<section id="video-sec" class="parallax">
  <div class="overlay"></div>
    <div class="container">
      <div class="row">

          <div class="col-md-offset-2 col-md-8 col-sm-12">
              <h2 class="wow fadeInUp" data-wow-delay="0.5s">Watch the video<small>All journeys have secret destinations of which the traveler is unaware.</small></h2>
              <a class="popup-youtube" href="https://www.youtube.com/watch?v=NE7nScR0suQ&ab_channel=FPACLASSICS%28LobsterFilmsCollection%29" ><i class="fa fa-play"></i></a>
              <small>Vintage color postcards capture life in 19th century Tunisia</small>
          </div>

      </div>
    </div>
</section>

<!------------ Footer section ------------>
<footer>
    
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3">&copy;<script type="text/javascript">document.write(new Date().getFullYear());</script> Chrnovoyage</div>
            </div>
        
        </div>
    
    </footer>



<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/jquery.backstretch.min.js"></script>
<script src="js/isotope.js"></script>
<script src="js/imagesloaded.min.js"></script>
<script src="js/nivo-lightbox.min.js"></script>
<script src="js/jquery.parallax.js"></script>
<script src="js/smoothscroll.js"></script>
<script src="js/wow.min.js"></script>
<script src="js/core.js"></script>


</body>
</html>