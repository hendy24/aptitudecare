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
		background: linear-gradient(to bottom, rgba(255,255,255,.5),rgba(255,255,255,.5)) no-repeat center,
				url('{$IMAGES}/memory_care-header.jpg') right -10px no-repeat;
		background-size: cover, cover;
	}

	@media (min-width: 768px) {
		#advanced-care .jumbotron {
			background: linear-gradient(to bottom, rgba(255,255,255,.5),rgba(255,255,255,.5)) no-repeat center,
					url('{$IMAGES}/memory_care-header.jpg') right -90px no-repeat;
			background-size: cover, cover;
		}
    }

	@media (min-width: 1280px) {
		#advanced-care .jumbotron {
			background: linear-gradient(to bottom, rgba(255,255,255,.5),rgba(255,255,255,.5)) no-repeat center,
					url('{$IMAGES}/memory_care-header.jpg') right -175px no-repeat;
			background-size: cover, cover;
		}
    }


</style>

<!-- heading text -->
<div class="bg-white my-8 sm:my-16 lg:my-28 w-full">
    <div class="flex w-full justify-center items-end">
        <span class="font-aspen text-blue-dark-ac text-4xl md:text-6xl lg:text-8xl">ADVANCED CARE</span>
    </div>
</div>
<!-- / heading text -->

<div id="advanced-care">
	<div class="jumbotron jumbotron-fluid h-600 flex flex-col">
		<p class="text-blue-dark-ac p-8 w-full md:w-2/3 text-2xl md:text-3xl lg:text-5xl leading-relaxed flex-1">
			The #1 Assisted Living Community in Alaska is now offering dedicated Advanced Care for your loved one!
		</p>
		<div class="flex w-full items-center justify-center my-12">
			<a class="btn btn-primary font-aspen text-xl md:text-2xl lg:text-4xl rounded-0 px-6 py-4" href="{$SITE_URL}/resident-application">New Resident Application</a>
		</div>
	</div>

	<!-- what is advanced care -->
	<div class="container">
		<div class="flex flex-col lg:flex-row lg:space-x-8 lg:items-center">
			<div class="flex flex-col justify-center py-8 space-y-4">
				<h2 class="text-blue-dark-ac">What is advanced care?</h2>

				<p>
					Advanced care provides additional, private support for those who might benefit from a quieter and more
					monitored setting. We offer advanced care in a separate and secure wing of our assisted living
					communities to provide this support. Though living in a separate area, these residents still have
					opportunities for social interaction.
				</p>

				<p>
					An example of a senior needing advanced care might be someone suffering memory loss or dementia. To
					support these special needs, we hire additional staff and give them extensive dementia training. Our
					advanced care section also provides a quiet sensory room with a relaxing environment away from the
					overstimulation that sometimes accompanies these challenges. Both of these distinguishing features can
					provide peace of mind to seniors and loved ones.
				</p>
			</div>
			<img src="{$IMAGES}/lucynt_memory_care.jpg" class="w-96 p-0" alt="">
		</div>
	</div>
	<!-- /what is advanced care -->

	<!-- need advanced care -->
	<div class="container-fluid">
		<div class="row bg-blue-dark-ac">
			<div class="container">
				<div class="flex flex-col-reverse lg:flex-row lg:space-x-8 lg:items-center py-8">

					<img src="{$IMAGES}/need_memory_care.jpg" class="w-96 p-0" alt="advanced care in anchorage, ak">

					<div class="text-white flex flex-col justify-center py-8 space-y-4">
						<h2 class="text-blue-light-ac">Does my loved one need advanced care?</h2>

						<p>
							Determining whether a senior needs advanced care rests in the decision of the senior and loved ones,
							with the advice of healthcare experts. A senior who suffers from cognitive decline does not necessarily
							need to move to advanced care. Many of our residents who suffer memory loss benefit from the
							socialization and activities in the larger areas of our assisted living community. We provide advanced
							care for those with different needs. The choice to move to advanced care comes at the discretion of the
							family.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /need advanced care -->

</div>
