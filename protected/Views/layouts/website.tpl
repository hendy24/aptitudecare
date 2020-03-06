
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name= "description" content="Aspen Creek provides senior assistance and elderly care in Alaska. Find the right place for your loved ones by visiting our state-of-the-art community! "/>
  <!-- Global site tag (gtag.js) - Google Analytics -->

  <!-- CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="{$CSS}/slick-theme.css">
    <link rel="stylesheet" href="{$CSS}/slick.css">
    <link rel="stylesheet" type="text/css" href="fonts/fonts.css">
    <link rel="stylesheet" type="text/css" href="{$CSS}/style.css">
    <link rel="stylesheet" type="text/css" href="{$CSS}/responsive.css">
  
  <!-- LITY-->  
  <link href="dist/lity.css" rel="stylesheet">
  
    <title>Senior Living in Anchorage, AK | Aspen Creek </title>
</head>

<body class="pg-home">
    <!-- header start here -->
    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="/"><img src="{$IMAGES}/aspencreek-logo.png" alt="Aspen Creek Black Logo senior living Anchorage, AK /assisted living Anchorage, AK /assisted living facility Anchorage, AK /senior care center Anchorage, Alaska elderly care Alaska/Elderly Care Center Alaska/elderly care Alaska/senior assistance Alaska"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="/"><img src="{$IMAGES}/home.png" alt="home-menu"></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Assisted Living</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="living-at-aspen-creek">Living at Aspen Creek</a>
                                 <a class="dropdown-item" href="faq">FAQ</a>
                            </div>
                            
                        </li>                     
<!--                         <li class="nav-item">
                            <a class="nav-link" href="about.html">ABOUT US</a>
                        </li> -->
<!--                         <li class="nav-item">
                            <a class="nav-link" href="blog">BLOG</a>
                        </li>  
 -->                
                        <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle" id="aboutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ABOUT</a>
                          <div class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <a class="dropdown-item" href="meet-the-team">Meet the Team</a>
                            <a class="dropdown-item" href="our-staff">Our Staff</a>
                            <a href="menu" class="dropdown-item">Current Menu</a>
                            <a href="activities" class="dropdown-item">Current Activities</a>
                          </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="blog">Blog</a>
                        </li>           
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <!-- header end here -->



    <div id="content">
        {if $flashMessages}
        <div id="flash-messages">
            {foreach $flashMessages as $class => $message}
            <div class="{$class}">
                <ul>
                {foreach $message as $m}
                    <li>{$m}</li>
                {/foreach}
                </ul>
            </div>
            <div class="clear"></div>
            {/foreach}
        </div>
        
        {/if}
    
        <div id="page-content">
            {include file=$content}
        </div>
    </div>


    <footer>
        <div class="container footer-background">
          <div class="footer_row1">  
            <div class="d-flex">
              <div class="brand_footer">
                <a href="/"> <img src="{$IMAGES}/aspencreek-logo_white.png" alt="Aspen Creek White Logo senior living Anchorage, AK /assisted living Anchorage, AK /assisted living facility Anchorage, AK /senior care center Anchorage, Alaska elderly care Alaska/Elderly Care Center Alaska/elderly care Alaska/senior assistance Alaska" ></a>
              </div>
              <div class="social-media">
                <ul class="d-flex">
                  <li><a href="https://www.facebook.com/aspencreekalaska" target="_blank"><img src="{$IMAGES}/facebook-circular-logo.png" alt="facebook"></a></li>
                  <li><a href="https://www.instagram.com/aspencreekalaska/" target="_blank"><img src="{$IMAGES}/instagram-logo.png" alt="facebook"></a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer_row2">
            <div class="row">
              <div class="col-12 col-lg-3 col-md-6 col-sm-6">
                  <div class="d-flex">
                    <div class="address_area ">
                      <div class="d-flex">
                        <div class="iconx">
                          <img src="{$IMAGES}/placeholder-location.png" alt="map address" class="img-fluid">
                        </div>
                        <p>5915 Petersburg Street<br>Anchorage, Alaska 99507</p>
                      </div>
                      <div class="d-flex">
                        <div class="iconx">
                          <img src="{$IMAGES}/telephone-handle.png" alt="map address" class="img-fluid">
                        </div>
                        <p>907.868.2688</p>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="col-12 col-lg-9 col-md-6 col-sm-6">
                <div class="row text-center">
<!--              <div class="col-md-4">
                    <h4><a href="about.html">ABOUT US</a></h4>
                    <ul>
                      <li><a href="our-history.html">OUR HISTORY</a></li>
                      <li><a href="team.html">MEET THE TEAM</a></li>
                    </ul>
                  </div> -->
                  <div class="col-md-3">
                    <h4><a href="living-at-aspen-creek">Living at Aspen Creek</a></h4>
                  </div>
<!--                   <div class="col-md-2">
                    <h4><a href="news">NEWS</a></h4>
                  </div>                  
 -->                  <div class="col-md-2">
                    <h4><a href="faq">FAQ</a></h4>
                  </div>  
                   <div class="col-md-3">
                    <h4><a href="contact">Contact</a></h4>
                  </div>                  
                  <div class="col-md-2">
                    <h4><a href="careers">Careers</a></h4>
                  </div>
                  <div class="col-md-2">
                    <h4><a href="{$SITE_URL}/login">Login</a></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="footer_row3 text-center">
            <p>All Content &copy; {$smarty.now|date_format:"%Y"} <a href="https://www.springcreek.co/" target="new">Spring Creek Enterprise</a>. All Rights Reserved.</p>
          </div>
        </div>
    </footer>
    <!-- hero section end here -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="vendor/code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="vendor/cdn.jsdelivr.net/npm/popper.js%401.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/scripts.js"></script>
    <!-- Start Podium Script -->
    <script defer src="https://connect.podium.com/widget.js#API_TOKEN=5a2423dd-edc2-41e3-abef-c417d13b723f" id="podium-widget" data-api-token="5a2423dd-edc2-41e3-abef-c417d13b723f"></script>
    <!-- End Podium Script -->  
    <!-- LITY-->    
    <script src="vendor/jquery.js"></script>
    <script src="dist/lity.js"></script>    
    <script src="vendor/jquery.js"></script>
    <script src="dist/lity.js"></script>  

    <script type="text/javascript">
      $('#date').datepicker({});
    </script> 
  </body>
</html>