<link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css">
<style>
    .font-aspen {
        font-family: "Josefin Sans", sans-serif;
    }

    .h-600 {
        height: 600px;
    }

    header {
        box-shadow: none;
    }

    #advanced-care .jumbotron {
        background: linear-gradient(to bottom, rgba(255, 255, 255, .5), rgba(255, 255, 255, .5)) no-repeat center,
        url('{$IMAGES}/memory_care-header.jpg') right -10px no-repeat;
        background-size: cover, cover;
    }

    @media (min-width: 768px) {
        #advanced-care .jumbotron {
            background: linear-gradient(to bottom, rgba(255, 255, 255, .5), rgba(255, 255, 255, .5)) no-repeat center,
            url('{$IMAGES}/memory_care-header.jpg') right -90px no-repeat;
            background-size: cover, cover;
        }
    }

    @media (min-width: 1280px) {
        #advanced-care .jumbotron {
            background: linear-gradient(to bottom, rgba(255, 255, 255, .5), rgba(255, 255, 255, .5)) no-repeat center,
            url('{$IMAGES}/memory_care-header.jpg') right -175px no-repeat;
            background-size: cover, cover;
        }
    }


</style>

<!-- heading text -->
<div class="bg-white my-8 sm:my-16 lg:my-28 w-full">
	<div class="flex w-full justify-center items-end">
		<span class="font-aspen text-blue-dark-ac text-4xl md:text-6xl lg:text-8xl">ASSISTED LIVING</span>
	</div>
</div>
<!-- / heading text -->

<div id="advanced-care">
	<div class="jumbotron jumbotron-fluid h-600 flex flex-col">
		<p class="text-blue-dark-ac p-8 w-full md:w-2/3 text-2xl md:text-3xl lg:text-5xl leading-relaxed flex-1">
			Answering Your Questions About Assisted Living
		</p>
		<div class="flex w-full items-center justify-center my-12">
			<a class="btn btn-primary font-aspen text-xl md:text-2xl lg:text-4xl rounded-0 px-6 py-4"
			   href="{$SITE_URL}/resident-application">New Resident Application</a>
		</div>
	</div>

	<!-- what is assisted living -->
	<div class="container">
		<div class="flex flex-col lg:flex-row lg:space-x-8 lg:items-center">
			<div class="flex flex-col justify-center py-8 space-y-4">
				<h2 class="text-blue-dark-ac">What is assisted living at Aspen Creek Alaska?</h2>

				<p>
					Assisted living communities touch lives.
				</p>

				<p>
					True assisted living not only provides consistent and loving help,
					but it gives so much more. It touches lives.
				</p>

				<p>
					Foremost, it involves respect and dignity for the whole individual. Because needs vary as much as
					the uniqueness of people, Aspen Creek takes into consideration the physical, emotional, mental, and
					social needs of each senior who enters its doors. This includes, but is not limited to, medication
					management, showering and hygiene assistance, customized nutrition guidance and meal preparation, and social
					activities.
				</p>

				<p>
					We also recognize that seniors need to continue relationships with others. To meet this need, we
					draw in everyone involved in a senior’s life—residents, families, staff, and even school children—into the
					circle of a thriving community. We know that we can only achieve the highest quality of assisted by
					creating a community that connects generations and fosters mutually giving relationships.
				</p>

				<p>
					Unlike nursing homes, which provide healthcare, we assist in daily living with attention to both
					individual and family needs. This empowers seniors and their loved ones with the knowledge that
					residents can remain as active and as independent as possible, for as long as possible, encompassed in a
					flourishing community.
				</p>
			</div>
			<img src="{$IMAGES}/lucynt_memory_care.jpg" class="w-96 p-0" alt="">
		</div>
	</div>
	<!-- /what is assisted living -->

	<!-- more independence -->
	<div class="container-fluid">
		<div class="row bg-blue-dark-ac">
			<div class="container">
				<div class="flex flex-col-reverse lg:flex-row lg:space-x-8 lg:items-center py-8">

					<img src="{$IMAGES}/need_memory_care.jpg" class="w-96 p-0" alt="advanced care in anchorage, ak">

					<div class="text-white flex flex-col justify-center py-8 space-y-4">
						<h2 class="text-blue-light-ac">
							Why does assisted living with us now mean more independence later?
						</h2>

						<p>
							Choosing assisted living now means seniors live independently longer.
						</p>

						<p>
							Longer independent living for Alaskan seniors has always been our goal. This can only come
							from seniors and loved ones making earlier choices to move to assisted living care, rather than
							encouraging living on one’s own longer. Experience has shown us that by delaying assisted living, seniors often
							have unforeseen accidents at times when we cannot provide a room. This means they have to live
							long-term in a hospital or even less desirable circumstances. Coming to live with us before the signs
							of aging increase, allows seniors to surround themselves with those who can assist and help prevent
							accidents that might occur if they were living on their own.
						</p>

						<p>
							We encourage you to choose to move to Aspen Creek earlier to give you that peace of mind
							that comes from the potential of a longer, healthier life, and reducing unnecessary early
							hospitalization for seniors. We want to help now; so please reach out to now. Call (999) 999-9999 to contact
							one of our staff members.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /more independence -->

	<!-- what makes aspen creek different -->
	<div class="container">
		<div class="flex flex-col lg:flex-row lg:space-x-8 lg:items-center">
			<div class="flex flex-col justify-center py-8 space-y-4">
				<h2 class="text-blue-dark-ac">
					What makes Assisted Living at Aspen Creek different?
				</h2>

				<p>
					We offer not just a home, but a community that touches lives.
					From the residents to their loved ones, we touch a myriad of individual lives. Rather than creating just a
					home to live in, we provide a whole community where everyone associated with our residents feel
					welcome. We are a thriving, active social environment where seniors not only engage but give of
					themselves.
				</p>

				<p>
					When residents walk into Aspen Creek’s doors, they feel welcome and accepted—from enrollment
					through every day of their stay. They enjoy open spaces to meet others and small gathering areas to
					develop closer friendships. Private rooms also offer a place of refuge away from others when needed.
					Couples can also enjoy shared living spaces and still have private baths. Unsurpassed compassionate care
					plus state-of-the-art amenities—a large theater, gym, planned activities, fine dining, and
					more--distinguish us as different.
				</p>
			</div>

			<img src="{$IMAGES}/lucynt_individual_needs.jpg" class="w-96 p-0"
				 alt="Individual needs are met for advanced care residents in anchorage, ak">
		</div>
	</div>
	<!-- /what makes aspen creek different -->
</div>
