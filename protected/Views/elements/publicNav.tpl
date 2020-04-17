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
                            <a class="dropdown-item" href="{$SITE_URL}/care-team">Care Team</a>
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
                        <a class="nav-link" href="{$SITE_URL}/news">News</a>
                    </li>   
                    <!-- /blog -->
                </ul>
            </div>
        </nav>
    </div>
</header>
<!-- /nav header -->
