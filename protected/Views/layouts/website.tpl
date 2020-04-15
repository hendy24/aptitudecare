<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name= "description" content="Aspen Creek provides senior assistance and elderly care in Alaska. Find the right place for your loved ones by visiting our state-of-the-art community! "/>
    <!-- Global site tag (gtag.js) - Google Analytics -->

    <!-- CSS -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <link rel="stylesheet" href="{$CSS}/slick-theme.css">
    <link rel="stylesheet" href="{$CSS}/slick.css">
    <link rel="stylesheet" type="text/css" href="fonts/fonts.css">
    <!-- <link rel="stylesheet" type="text/css" href="{$CSS}/style.css"> -->
    <link rel="stylesheet" type="text/css" href="{$CSS}/public-custom.css"> 

    <link href="{$JS}/lity-2.4.0/dist/lity.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="{$JS}/lity-2.4.0/dist/lity.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 
    <script src="https://kit.fontawesome.com/5df6dcce04.js" crossorigin="anonymous"></script>
    <script src="{$JS}/website.js"></script>
    
    <title>{$title} | Senior Living in Anchorage, AK </title>
</head>

<body>
    <!-- covid-19 message -->
    <div id="covid-19-message" class="sticky-top">
        <div id="inner-message" class="alert alert-warning">
            <a href="{$SITE_URL}/blog/posts/covid-19">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <p class="text-center">Updates on how we are protecting our residents from COVID-19</p>
            </a>
        </div>
    </div>
    <!-- /covid-19 message -->

    <!-- nav header -->
    <header>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-xl navbar-light bg-light">
                <a class="navbar-brand" href="/"><img src="{$IMAGES}/aspencreek-logo.svg" alt="assisted living Anchorage, AK"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                    
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="{$SITE_URL}"><i class="fas fa-home"></i></a></li>
                
                        <!-- assisted living dropdown -->
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Assisted Living</a>
                                
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{$SITE_URL}/living-at-aspen-creek">Living at Aspen Creek</a>
                                <a class="dropdown-item" href="{$SITE_URL}/faq">FAQ</a>
                            </div>       
                        </li>                     
                        <!-- /assisted living dropdown -->

                        <!-- meet the team dropdown -->
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="meetTheTeamDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">MEET THE TEAM</a>
                            
                            <div class="dropdown-menu" aria-labelledby="meetTheTeamDropdown">
                                <a class="dropdown-item" href="{$SITE_URL}/leadership-team">Leadership Team</a>
                                <a class="dropdown-item" href="{$SITE_URL}/caregiving-team">Caregiving Team</a>
                            </div>
                        </li>
                        <!-- /meet the team dropdown -->

                        <!-- about dropdown -->
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="aboutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ABOUT</a>
                  
                            <div class="dropdown-menu" aria-labelledby="aboutDropdown">
                                <a href="{$SITE_URL}/menu" class="dropdown-item">Current Menu</a>
                                <a href="{$SITE_URL}/activities" class="dropdown-item">Current Activities</a>
                            </div>
                        </li>
                        <!-- /about dropdown -->

                        <!-- contact -->
                        <li class="nav-item">
                            <a class="nav-link" href="{$SITE_URL}/contact">Contact</a>
                        </li>
                        <!-- /contact -->

                        <!-- blog -->
                        <li class="nav-item">
                            <a class="nav-link" href="{$SITE_URL}/blog">Blog</a>
                        </li>   
                        <!-- /blog -->
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <!-- /nav header -->


    <!-- page body -->
    {if $flashMessages}
    <div class="container">
        <div class="row mx-4">
        {foreach $flashMessages as $class => $message}
            <div class="col-12 text-center alert {$class}" role="alert">
                {foreach $message as $m}
                {$m}
                {/foreach}
            </div>
        {/foreach}
        </div> 
    </div>
    {/if}
  
    <!-- load page content -->
    {include file=$content}
    <!-- /load page content -->
    <!-- /page body -->


    <!-- footer -->
    <footer>
        <div class="container-fluid footer-background">

            <!-- row 1 -->
            <div class="row py-5">
                <div class="col-lg-8 col-sm-12 logo">
                    <a href="/"><img class="img-fluid" src="{$IMAGES}/aspencreek-logo_white.png" alt="Aspen Creek White Logo senior living Anchorage, AK /assisted living Anchorage, AK /assisted living facility Anchorage, AK /senior care center Anchorage, Alaska elderly care Alaska/Elderly Care Center Alaska/elderly care Alaska/senior assistance Alaska" ></a>
                </div>
                <div class="social col-lg-4 col-sm-12">
                    <a href="https://www.facebook.com/aspencreekalaska" target="_blank"><i class="fab fa-facebook fa-2x ml-2"></i></a>
                    <a href="https://www.instagram.com/aspencreekalaska/" target="_blank"><i class="fab fa-instagram fa-2x ml-2"></i></a>
                    <a href="https://www.youtube.com/channel/UCeYE9V77h4y_NcM2EpjhvFQ?view_as=subscriber" target="_blank"><i class="fab fa-youtube fa-2x ml-2"></i></a>
                    <a href="https://vimeo.com/user100366085"><i class="fab fa-vimeo fa-2x ml-2"></i></a>
                </div>
            </div>
            <!-- /row 1 -->

            <!-- row 2 -->
            <div class="row py-2">
                <div class="col-lg-3 col-md-6 ml-5 address-area">
                    <p><i class="fas fa-map-marked-alt"></i> 
                        <span class="ml-1">5915 Petersburg Street</span><br>
                        <span class="ml-4">Anchorage, Alaska 99507</span>
                    </p>                    
                    <p><i class="fas fa-phone-volume"></i><span class="ml-2">&nbsp;907.868.2688</span></p>
                </div>
            
                <div class="col-lg-8 col-md-6">
                    <ul class="nav float-right">
                        <li class="nav-item">
                            <a href="{$SITE_URL}" class="nav-link"><i class="fas fa-home"></i></a>
                        </li>
                        <li class="nav-item">
                            <a href="{$SITE_URL}/living-at-aspen-creek" class="nav-link">Living at Aspen Creek</a>
                        </li>
                        <li class="nav-item">
                            <a href="{$SITE_URL}/faq" class="nav-link">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a href="{$SITE_URL}/contact" class="nav-link">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a href="{$SITE_URL}/careers" class="nav-link">Careers</a>
                        </li>
                        <li class="nav-item">
                            {if !$auth->isLoggedIn()}
                                <a href="{$SITE_URL}/login" class="nav-link">Login</a>
                            {else}
                                <a href="{$SITE_URL}/login/logout" class="nav-link">Logout</a>
                            {/if}
                            
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 text-12 text-dark-grey text-center copyright">
                    <p>All Content &copy; {$smarty.now|date_format:"%Y"} <a href="https://www.springcreekenterprise.com/" class="text-dark-grey" target="new">Spring Creek Enterprise</a>. All Rights Reserved.</p>
                </div>
            </div>
    </footer>
    <!-- /footer -->


    <!-- podium script -->
    <script defer src="https://connect.podium.com/widget.js#API_TOKEN=5a2423dd-edc2-41e3-abef-c417d13b723f" id="podium-widget" data-api-token="5a2423dd-edc2-41e3-abef-c417d13b723f"></script>
    <!-- /podium script -->  
    
</body>
<script>
    {include file=$jsfile}
    }
</script>

</html>