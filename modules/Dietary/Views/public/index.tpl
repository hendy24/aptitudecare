<!-- modules/Dietary/Views/public/index.tpl -->

<div id="transitionDiv">
	<!-- Main menu content page -->
	<div id="panel-0" class="rotatingPage">
		<div id="mainContent">
			<div id="mainLogo">
				<img src="{$IMAGES}/{$location->logo}" alt="">
			</div>
			<div id="menuTitle">
				<img src="{$IMAGES}/featured_menu.png" alt="">
			</div>
				<div id="menuContent">
					<div class="menu">
						<h2>Breakfast</h2>
						<p class="text-14 time">{$meal[0]->start|date_format:"%l:%M %P"} - {$meal[0]->end|date_format:"%l:%M %P"}</p>
						{foreach from=$menuItems[0]->content item=menu}
						<p>{$menu}</p>
						{/foreach}
					</div>

					<div class="menu">
						<h2>Lunch</h2>
						<p class="text-14 time">{$meal[1]->start|date_format:"%l:%M %P"} - {$meal[1]->end|date_format:"%l:%M %P"}</p>
						{foreach from=$menuItems[1]->content item=menu}
						<p>{$menu}</p>
						{/foreach}
					</div>

					<div class="menu">
						<h2>Dinner</h2>
						<p class="text-14 time">{$meal[2]->start|date_format:"%l:%M %P"} - {$meal[2]->end|date_format:"%l:%M %P"}</p>
						{foreach from=$menuItems[2]->content item=menu}
						<p>{$menu}</p>
						{/foreach}
					</div>

					<div id="altMenu">
						<h2 style="font-size: 16px;">Alternate Menu Items</h2>			
						{$alternates->content}
					</div>
					<div id="guestWelcome">
						{$locationDetail->menu_greeting}
					</div>
			</div>
			<div id="menuPic">
				<div class="raisingTheStandard">
					<img src="{$IMAGES}/raising_the_standard.png" alt="">
				</div>
			</div>
		</div>	


	</div>


	<!-- Activities page -->
	<div id="panel-1" class="rotatingPage" style="display: none;">
		<div class="transitionDiv">
			<div id="activitiesContent">	
				<?php if ($facilityId == 12): ?>
					<div id="grangeville-teton">
				<?php else: ?>
					<div id="teton">
				<?php endif; ?>
					<div id="mainLogo">
						<img src="{$IMAGES}/{$location->logo}" alt="">
						{if $location->id == 12}
							<div class="grangevilleActivitiesStandard">
						{else}
							<div class="activitiesStandard">
						{/if}
							<img src="{$IMAGES}/raising_the_standard.png" alt="Raising the Standard">
						</div>
					</div>
				</div>
				

				<div id="activitiesTitle">
					<img src="{$IMAGES}/weekly_activities.png" alt="Weekly Activities">
				</div>
{* 				<?php foreach ($weekActivities as $k => $activity):  ?>
					<div class="activity">
						<h2><?php echo strftime('%A', strtotime($k));  ?></h2>
						<?php foreach ($activity as $a): ?>
							<p>
								<?php if (isset ($a['ActivitySchedule'])): ?>
									<strong><?php echo strftime('%l:%M %P', strtotime($a['ActivitySchedule']['datetime_start'])); ?></strong>
								<?php endif; ?> 
								<?php echo $a['Activity']['activity']; ?>
							</p>
							
						<?php endforeach; ?>
					</div>
				<?php endforeach;  ?>
 *}
			</div>
		</div>
	</div>


	<!-- Additional page -->
	{if $additionalPage->active}
		<div id="panel-2" class="rotatingPage" style="display: none;">
	
		</div>
	{/if}
</div>
