<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.3/components.min.css"
	  integrity="sha512-f6TS5CFJrH/EWmarcBwG54/kW9wwejYMcw+I7fRnGf33Vv4yCvy4BecCKTti3l8e8HnUiIbxx3V3CuUYGqR1uQ=="
	  crossorigin="anonymous"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.3/utilities.min.css"
	  integrity="sha512-DjlU1UdCP+4lA/lTtRoMMyTlUBGgQueyJgEXSp08GgiW3BT5QWZNRSFhKcvCutJ66oF1YVAXbW8sDSnqeVSOeA=="
	  crossorigin="anonymous"/>
<style>
    .full-height, .c-slide-inner {
        min-height: calc(100vh - 70px) !important;
    }
	@supports (-webkit-overflow-scrolling: touch) {
		.c-slide-inner: {
			background-attachment: scroll !important;
		}
	}

</style>
<!-- hero section -->
<div class="c-slide-inner">
	<div class="pt-4 flex full-height flex-column md:bg-transparent bg-black bg-opacity-60">

		<div class="container-fluid p-5 rounded flex-1 ">
			<h1 class="text-gray-100 md:text-gray-900">Senior Living. Redefined.</h1>
			<p class="text-gray-100 md:text-gray-900 w-full md:w-1/2 lg:w-1/3">Aspen Creek is changing what Senior Living means
				for Alaskans. Assisted Living at Aspen Creek Senior
				Living is not only about compassionate care and healthy lifestyles, it is about community.</p>
			<a href="{$SITE_URL}/contact" class="btn btn-primary">Get more info</a>
			<!-- <a class="btn btn-primary" href="{$SITE_URL}/virtual-visit">Take a Virtual Visit</a> -->
		</div>


		<!-- VOTED #1 -->
		<div class="bg-blue-dark-ac hidden md:flex flex-col md:flex-row md:space-x-20 items-center justify-center py-8">
			<!-- lsft side -->
			<div class="flex flex-col space-y-4 text-center">
				<div class="text-blue-light-ac text-xl lg:text-2xl">
					VOTED
				</div>
				<div class="text-white text-2xl lg:text-4xl">
					#1 in Assisted Living
				</div>
				<div class="text-blue-light-ac text-xl lg:text-2xl">
					BY OUR FELLOW ALASKANS
				</div>
			</div>
			<!-- right side -->
			<div class="flex">
				<img class="my-8 md:my-0"
					 src="https://res.cloudinary.com/codefaber/image/upload/v1612740354/aspen-creek/boa-final_uvvczi.png"
					 alt="">
			</div>
		</div>
		<!-- /VOTED -->
	</div>
</div>
<!-- /hero section -->

<!-- VOTED #1 mobile -->
<div class="bg-blue-dark-ac flex md:hidden flex-col md:flex-row items-center justify-center py-20">
	<!-- lsft side -->
	<div class="flex flex-col space-y-4 text-center">
		<div class="text-blue-light-ac text-xl md:text-3xl">
			VOTED
		</div>
		<div class="text-white text-3xl md:text-6xl">
			#1 in Assisted Living
		</div>
		<div class="text-blue-light-ac text-xl md:text-3xl">
			BY OUR FELLOW ALASKANS
		</div>
	</div>
	<!-- right side -->
	<div class="flex">
		<img width="300"
			 src="https://res.cloudinary.com/codefaber/image/upload/v1612740354/aspen-creek/boa-final_uvvczi.png"
			 alt="">
	</div>
</div>
<!-- /VOTED -->

<!-- our community -->
<div class="container my-5">

	<div class="row">
		<div class="col-12 text-center">
			<h2 class="display-4">Your Community</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-2"></div>
		<div class="col-8 bg-blue-light-ac rounded mb-5">
			<p class="quote mb-0 pb-0">I want to <strong>thank you again for the wonderful job your staff is doing with my mom</strong>.  When I’m on the phone with her, I hear them joke with her and they have fun! Your staff checks on her all the time, and she gets mixed up thinking they are her friends coming to see her, and actually, <strong>they treat her like a dear friend!</strong> You are doing a wonderful job, even with all the covid stress!</p>
			<p class="quote text-right mt-0 pt-0">Thank you!<br>
			Teresa Zimmerman</p>
		</div>
		<div class="col-2"></div>
	</div>
	<div class="row pb-5">
		<div class="w-full md:w-2/3 mx-auto text-center space-y-4 text-2xl px-4">
			<p class="">Aspen Creek was designed to provide life-changing experiences for you, your
				family, and all who choose to be a part of our Senior Care Center Community in Anchorage Alaska.</p>
			<p class="">Senior Care in Alaska is about more than just having a beautiful environment for
				you to receive care with your activities of daily living. It is about creating relationships, making
				memories with your family members and with friends, old and new. It is about living life and enjoying
				each day to the fullest.</p>
			<p class="">These relationships that are strengthened and created affect more than just you,
				they have lasting impact on all of those who take time to appreciate the legacies you contribute to our
				society.</p>
		</div>
	</div>
</div>
<!-- /our community -->

<!-- virtual tours -->
<div class="container-fluid">
	<div class="row">
		<div class="col-12 px-4">
			<div class="call-to-action">
				<a href="https://gapanorams.com/tour/aspencreek/" class="d-flex align-items-center" data-lity>
					<h3>Click here to take a virtual tour.</h3>
				</a>
			</div>
		</div>
	</div>
</div>
<!-- /virtual tours -->

<!-- services -->
<div class="container my-5 services">
	<div class="row">
		<div class="col-md-4 col-sm-12 text-center">
			<img src="{$IMAGES}/tray.png" class="pb-4" alt="fine dining">
			<h4>FINE DINING</h4>
			<p>Our award winning Executive Chef creates beautifully presented, delicious, and nutritious meals every day
				and our dietitian is available to help with any dietary restrictions.</p>
		</div>


		<div class="col-md-4 col-sm-12 text-center">
			<a href="{$SITE_URL}/care-team">
				<img src="{$IMAGES}/24-7.png" class="pb-4" alt="24-hour elder care anchorage ak">
				<h4>24-HOUR COMPASSIONATE <br>CARE</h4>
				<p>We are extremely selective during our interview and hiring process. Our Care Partners are the best of
					the best, always delivering exceptional and compassionate caregiving. We take great pride in the
					opportunity we have to provide senior assistance in Alaska.</p>
			</a>
		</div>


		<div class="col-md-4 col-sm-12 text-center">
			<img src="{$IMAGES}/yoga.png" class="pb-4" alt="wellness in anchorage alaska assisted living">
			<h4>TOTAL WELLNESS</h4>
			<p>Our wellness program goes beyond providing engaging and interesting activities for our residents. We
				address the total wellness of each individual by focusing on their physical, social, spiritual, and
				intellectual goals.</p>
		</div>

		
	</div>
</div>
<!-- /services -->

<div id="resident-app-banner" class="container">
	<div class="row">
		<div class="col-12 text-center">
			<a class="btn btn-lg btn-primary mt-4" href="https://aspencreekalaska.com/resident-application">
			New Resident Application
			</a>
		</div>
	</div>

</div>

<!-- aspen creek experience -->
<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12 text-center">
			<img src="{$IMAGES}/home-team2.jpg" class="img-fluid" alt="">
		</div>
	</div>
</div>

<div class="container my-3">
	<div class="row">
		<div class="col-12">
			<h3 class="text-center">The Aspen Creek Experience.</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<p>As an assisted living community in Anchorage, we provide senior assistance in Alaska with activities of
				daily living such as bathing, dressing, medication reminders, and so forth and while we always provide
				all our services with compassion and to five-star standards those are just services we provide, just
				things we “do”. Our mission goes much deeper than providing exceptional care. Our focus is on the total
				wellness of each of our residents encompassing their physical, social, spiritual, and intellectual
				well-being. Everything we have created at Aspen Creek from our building to our fine-dining and activity
				programs are connected to each resident at an individual level.</p>
		</div>
	</div>
</div>
<!-- /aspen creek experience -->


<!-- tv commercial -->
<div class="container my-5">
	<div class="embed-responsive embed-responsive-16by9">
		<iframe src="https://youtube.com/embed/nyTgUbLWoLg" frameborder="0"></iframe>
	</div>
</div>
<!-- /tv commercial -->

<!-- map -->
<div class="container-fluid mt-5">
	<div class="embed-responsive embed-responsive-21by9">
		<iframe class="embed_responsive-item"
				src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d36623.229586051166!2d-149.81794623140055!3d61.15637104241877!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x56c897743c584f2b%3A0xa639396185109446!2s5915%20Petersburg%20St%2C%20Anchorage%2C%20AK%2099507%2C%20USA!5e0!3m2!1sen!2sin!4v1576504296729!5m2!1sen!2sin"></iframe>
	</div>
</div>

<script src="{$JS}/lity-2.4.0/dist/lity.js"></script>


