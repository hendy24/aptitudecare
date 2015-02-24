<?php

class MenuController extends MainPageController {
	public $module = "Dietary";

	public function edit() {
		smarty()->assign('title', "Edit Menu");
		$menuMod = false;

		if (input()->id == "") {
			session()->setFlash("Could not find the menu item you were looking for.", 'error');
			$this->redirect();
		}

		// Need to fetch the menu item
		if (input()->type == "MenuMod") {
			// fetch the menu modification
			$menuItem = $this->loadModel("MenuMod", input()->id);
			$menuMod = true;

		} elseif (input()->type == "MenuChange") {
			// fetch the changed menu
			$menuItem = $this->loadModel("MenuChange", input()->id);
		} else {
			// fetch the menu item
			$menuItem = $this->loadModel("MenuItem", input()->id);
		}

		$location = $this->loadModel('Location', input()->location);

		smarty()->assign('location', $location);
		smarty()->assign('menuItem', $menuItem);
		smarty()->assign('menuType', input()->type);
		smarty()->assign('date', input()->date);
		smarty()->assign('menuMod', $menuMod);
	}


	public function submitEdit() {

		// If this is alread a menu mod load the current changes...
		if (input()->menu_type == "MenuMod") {
			$menuItem = $this->loadModel('MenuMod', input()->public_id);
		} else {
			$menuItem = $this->loadModel('MenuMod');
		}

		// get the location
		if (input()->location == "") {
			session()->setFlash("No facility menu was selected. Please try again.", 'error');
			$this->redirect();
		} else {
			$location = $this->loadModel('Location', input()->location);
		}


		// if reset is not empty then delete the menu mod item
		if (isset (input()->reset)) {
			if ($menuItem->delete()) {
				session()->setFlash("The menu changes have been deleted and the menu has been reset to the original menu items.", 'success');
				$this->redirect(array('module' => 'Dietary', 'page' => 'dietary', 'action' => 'current', 'location' => $location->public_id));
			} else {
				session()->setFlash("Could not reset the menu changes. Please try again", 'error');
				$this->redirec(input()->path);
			}
		}


		// get the original menu item
		$origMenuItem = $this->loadModel('MenuItem', input()->public_id);

				
		
		// if there was no reason for a menu change entered throw an error
		if (input()->reason == "") {
			session()->setFlash("You must enter the reason for the menu change.", 'error');
			$this->redirect(input()->path);
		} else {
			$menuItem->reason = input()->reason;
		}

		// set the menu item id
		$menuItem->menu_item_id = $origMenuItem->id;

		// set the location
		$menuItem->location_id = $location->id;

		// set the date
		$menuItem->date = input()->date;

		// set the menu content to be saved...
		$menuItem->content = input()->menu_content;

		// set the user info who made the change
		$menuItem->user_id = auth()->getRecord()->id;

		if ($menuItem->save()) {
			session()->setFlash("The menu for " . display_date(input()->date) . " has been saved.", 'success');
			$this->redirect(array('module' => 'Dietary'));
		} else {
			session()->setFlash("Could not save the menu information. Please try again.", 'error');
			$this->redirect(input()->path);
		}

	}
}