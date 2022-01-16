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

                    <!-- living here dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="livingHereDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">LIVING HERE</a>

                        <div class="dropdown-menu" aria-labelledby="livingHereDropdown">
							<a class="dropdown-item" href="{$SITE_URL}/living-at-aspen-creek">Living at Aspen Creek</a>
							<p class="dropdown-header">Communities</p>
							<a class="dropdown-item" style="text-indent:0.5rem;" href="{$SITE_URL}/communities-anchorage">- Anchorage</a>
							<a class="dropdown-item" style="text-indent:0.5rem;" href="{$SITE_URL}/communities-kenai">- Kenai</a>
							<a class="dropdown-item" href="{$SITE_URL}/assisted-living">Assisted Living</a>
							<a class="dropdown-item" href="{$SITE_URL}/advanced-care">Advanced Care</a>
						</div>
                    </li>
                    <!-- /living here dropdown -->


                    <!-- memory care -->
                    <!-- <li class="nav-item"><a class="nav-link" href="{$SITE_URL}/memory-care">Memory Care</a></li>-->
                    <!-- /memory care -->

                    <!-- about dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="aboutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ABOUT</a>

                        <div class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <a class="dropdown-item" href="{$SITE_URL}/leadership-team">Leadership Team</a>
                            <a class="dropdown-item" href="{$SITE_URL}/care-team">Care Team</a>
                            <a href="{$SITE_URL}/menu" class="dropdown-item">Current Menu</a>
                            <a href="{$SITE_URL}/activities" class="dropdown-item">Current Activities</a>
                            <a class="dropdown-item" href="{$SITE_URL}/faq">FAQ</a>
                        </div>
                    </li>
                    <!-- /about dropdown -->

                    <!-- contact -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CONTACT</a>

                        <div class="dropdown-menu" aria-labelledby="contactDropdown">
                            <a class="nav-link" href="{$SITE_URL}/contact">Contact Form</a>
                            <a class="nav-link" href="{$SITE_URL}/schedule-visit">Schedule Visit</a>
                        </div>
                    </li>
                    <!-- /contact -->


                    <!-- our stories -->
                    <li class="nav-item">
                        <a class="nav-link" href="{$SITE_URL}/stories">Our Stories</a>
                    </li>
                    <!-- /our stories -->
                    <!-- blog -->
<!--                     <li class="nav-item">
                        <a class="nav-link" href="{$SITE_URL}/news">News</a>
                    </li>
 -->                    <!-- /blog -->
                </ul>
            </div>
        </nav>
    </div>
</header>
<!-- /nav header -->
