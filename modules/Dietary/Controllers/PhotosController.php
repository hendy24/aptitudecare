<?php

/**
 * PhotosController class
 *
 * @package default
 * @author kwh
 **/


class PhotosController extends DietaryController {


	/*
	 * Upload Photos page
	 *
	 */
	public function upload_photos() {
		smarty()->assign('title', "Add Photos");

		$user_id = auth()->getRecord()->id;
		// need to get the photos which have been uploaded by this user which have not add info added to them yet.
		$folders = $this->loadModel('PhotoCategory')->fetchAll();

		smarty()->assign('folders', $folders);

	}


	// Tag functionality removed on 2018.01.18 by kwh
	/*
	 * -------------------------------------------------------------------------
	 * AJAX call to delete tag from photo
	 * -------------------------------------------------------------------------
	 */
	// public function delete_tag() {
	// 	// Get the id for the tag by name
	// 	$tag = $this->loadModel('PhotoTag')->fetchByName(input()->tag_name);

	// 	// Delete the linked photo tag
	// 	if ($this->loadModel('PhotoLinkTag')->deleteLinkedTag(input()->photo_id, $tag->id)) {
	// 		return true;
	// 	}

	// 	return false;
	// }


	public function photo_info() {
		smarty()->assign('title', "Add Photo Info");

		$user_id = auth()->getRecord()->id;
		// need to get the photos which have been uploaded by this user which have not add info added to them yet.
		$photos = $this->loadModel("Photo")->fetchPhotosWithoutInfo($user_id);
		$categories = $this->loadModel('PhotoCategory')->fetchAll();
		$locations = $this->loadModel('Location')->fetchFacilities();

		smarty()->assign('categories', $categories);
		smarty()->assign('locations', $locations);
		smarty()->assignByRef('photos', $photos);
	}


	/*
	 * Save the uploaded photo info
	 *
	 */
	public function save_photo_info() {
		$success = false;
		$user_id = auth()->getRecord()->id;

		if (input()->photo_id != "") {
			$photo = $this->loadModel("Photo", input()->photo_id);
		} else {
			$this->redirect(input()->current_url);
		}

		if (input()->category != '') {
			$photo->category = input()->category;
		} else {
			// throw an error
			session()->setFlash("Please select a category and try again.", 'error');
			$this->redirect(input()->currentUrl);
		}

		if (input()->location != '') {
			$location = $this->loadModel('Location', input()->location);
			$photo->location_id = $location->id;
		} else {
			$photo->location_id = auth()->getRecord()->default_location;
		}

		if (input()->subcategory != '') {
			$photo->subcategory = input()->subcategory;
		}

		// we have added the info now
		$photo->info_added = true;
		$photo->user_approved = $user_id;

		// save the photo info
		if ($photo->save()) {
			return true;
		} 
		return false;
	}


	/*
	 * Find sub-categories
	 *
	 */
	public function find_subcategories() {
		// get sub-categories by id
		if (isset (input()->category) && input()->category != '') {
			$subcats = $this->loadModel('PhotoSubcategory')->fetchByCategoryId(input()->category);
		}

		if (!empty ($subcats)) {
			json_return($subcats);
		}

		return false;		
	}


	/*
	 * New Photos page with folders
	 *
	 */
	public function photos() {
		$subcat_selected = false;
		$facility_selected = false;

		if (isset (input()->facility_id) && input()->facility_id != '') {
			$photos = $this->loadModel('Photo')->fetchByFacility(input()->facility_id);
			smarty()->assign('photos', $photos);
		} elseif (isset (input()->subcategory_id) && input()->subcategory_id != '') {
			$photos = $this->loadModel('Photo')->fetchBySubcategory(input()->subcategory_id);
			smarty()->assign('photos', $photos);
		} elseif (isset (input()->category_id) && input()->category_id != '') {
			if (input()->category_id == 'all_locations') {
				$subcats = $this->loadModel('Location')->fetchFacilities();
				$facility_selected = true;
			} else {
				$subcats = $this->loadModel('PhotoSubcategory')->fetchByCategoryId(input()->category_id);
			}
			
			$subcat_selected = true;
			smarty()->assign('categories', $subcats);

			if (empty ($subcats)) {
				// fetch the photos that go in the folder
				$photos = $this->loadModel('Photo')->fetchByCategory(input()->category_id);
				smarty()->assign('photos', $photos);
			}	
		} else {
			$categories = $this->loadModel('PhotoCategory')->fetchCategories();
			smarty()->assign('categories', $categories);
		}

		// need to get a list of the available folders
		smarty()->assign('subcat_selected', $subcat_selected);
		smarty()->assign('facility_selected', $facility_selected);
	}

	public function subfolder() {

	}


	// this page was replaced by the photos page (above) on 2018.01.17 by kwh
	/*
	 * View Photos page
	 *
	 */
	// public function view_photos() {

	// 	if (isset (input()->current_page)) {
	// 		$current_page = input()->current_page;
	// 	} else {
	// 		$current_page = false;
	// 	}

	// 	$facilities = $this->loadModel('Location')->fetchFacilities();

	// 	if (isset (input()->facility) && input()->facility != "all") {
	// 		$sel_facility = $this->loadModel('Location', input()->facility); 
	// 	} else {
	// 		$sel_facility = $this->loadModel('Location');
	// 	}

	// 	smarty()->assign('facilities', $facilities);
	// 	smarty()->assign('selectedFacility', $sel_facility);

	// 	$photos = $this->loadModel("Photo")->paginateApprovedPhotos($current_page, $sel_facility->id);
	// 	smarty()->assign('photos', $photos);
	// }




	// public function view_photos_json() {
	// 	$photos = $this->loadModel("Photo")->fetchApprovedPhotos();
	// 	echo json_encode($photos);
	// 	exit;
	// }


	public function search_photos() {

		if (input()->facility != "all" && input()->facility != "") {
			$location = $this->loadModel('Location', input()->facility);
		} else {
			$location = $this->loadModel('Location');
		}
		
		if (input()->term != "") {
			$tags = $this->loadModel('Photo')->fetchBySearch(input()->term, $location->id);
			json_return($tags);
		} else {
			$tags = $this->loadModel('PhotoTag')->fetchAll();
			json_return($tags);
		}
		
		return false;
	}



	/*
	 * Manage Photos page
	 *
	 */
	public function approve_photos() {
		if (!auth()->hasPermission('manage_dietary_photos')) {
			session()->setFlash("You do not have permission to access this page.", 'error');
			$this->redirect();
		}

		$photos = $this->loadModel("Photo")->fetchPhotosForApproval();

		// fetch tags
		foreach ($photos as $k => $p) {
			$photos[$k]->tag = $this->loadModel('PhotoTag')->fetchTags($p->id); 
		}
		smarty()->assign('photos', $photos);
	}



	public function manage_photos() {
		if (!auth()->hasPermission('manage_dietary_photos')) {
			session()->setFlash("You do not have permission to access this page.", 'error');
			$this->redirect();
		}

		if (isset (input()->facility) && input()->facility != "") {
			$facility = $this->loadModel('Location', input()->facility);
		} elseif (isset(input()->location) && input()->location != "") {
			$facility = $this->loadModel('Location', input()->location);
		} else {
			$facility = $this->loadModel('Location');
		}

		$photos = $this->loadModel("Photo")->fetchAllPhotos($facility->id);

		// fetch tags
		foreach ($photos as $k => $p) {
			$photos[$k]->tag = $this->loadModel('PhotoTag')->fetchTags($p->id); 
		}
		smarty()->assign('photos', $photos);
		smarty()->assign('facility', $facility);

	}






	/*
	 * -------------------------------------------------------------------------
	 *  Process uploaded photos
	 * -------------------------------------------------------------------------
	 */




	/*
	 * Submit and process uploaded photos
	 *
	 */
	public function submit_upload() {

		if (isset (input()->location)) {
			$location = $this->loadModel("Location", input()->location);
		} else {
			$location = $this->loadModel("Location", auth()->getRecord()->default_location);
		}

		if ( !empty ($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileType = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
			$targetPath = 's3://advanced-health-care.s3.amazonaws.com/dietary_photos';
			// $targetPath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/";;
			$fileName = getRandomString() . strtotime('now') . '.' . $fileType;
			$targetFile = $targetPath . $fileName;

			if (move_uploaded_file($tempFile, $targetFile)) {
				// success
				// need to create a file name and save to photo table
				$photo = $this->loadModel("Photo");
				$photo->filename =  $fileName;
				$photo->location_id = $location->id;
				$photo->info_added = false;
				if ($photo->save()) {
					if ($this->createThumbnail($photo->filename)) {
						json_return (array("filetype" => $fileType, "name" => $photo->filename));
					}
					json_return(false);

				} else {
					json_return(false);
				}
			} else {
				// failure
				json_return(false);
			}

		} else {
			// error message
			json_return(false);
		}

	}


	/*
	 * Process approved photos
	 *
	 */
	// public function approve_photos() {
	// 	$success = false;
	// 	if (!empty (input()->photo)) {
	// 		foreach (input()->photo as $id => $approved) {
	// 			$photo = $this->loadModel("Photo", $id);
	// 			$photo->approved = $approved;
	// 			$photo->user_approved = auth()->getRecord()->id;
	// 			if ($photo->save()) {
	// 				if ($approved == false) {
	// 					// delete the file image and thumbnail
	// 					$targetImagePath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/";
	// 					$targetThumbsPath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/thumbnails/";
	// 					unlink($targetImagePath . $photo->filename);
	// 					unlink($targetThumbsPath . $photo->filename);
	// 				}
	// 				$success = true;
	// 			}
	// 		}
	// 		if ($success) {
	// 			session()->setFlash("The photos were approved.", 'success');
	// 			$this->redirect(array("module" => "Dietary"));
	// 		} else {
	// 			session()->setFlash("Could not save the photos. Please try again.", 'error');
	// 			$this->redirect(input()->current_url);
	// 		}
	// 	}


	// }




	/*
	 * Create and save photo thumbnails
	 *
	 */
	public function createThumbnail($filename) {
		$targetImagePath = S3_BUCKET . DS .'dietary_photos';
		$targetThumbsPath = S3_BUCKET . DS . 'dietary_photos/thumbails';
		// $targetImagePath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/";
		// $targetThumbsPath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/thumbnails/";

		if (preg_match('/[.](jpg)$/', $filename)) {
			$image = imagecreatefromjpeg($targetImagePath . $filename);
		} elseif (preg_match('/[.](gif)$/', $filename)) {
			$image = imagecreatefromgif($targetImagePath . $filename);
		} elseif (preg_match('/[.](png)$/', $filename)) {
			$image = imagecreatefrompng($targetImagePath . $filename);
		}

		$image_width = imagesx($image);
		$image_height = imagesy($image);

		$new_width = 175;
		$new_height = floor ($image_height * ($new_width / $image_width));

		$new_image = imagecreatetruecolor($new_width, $new_height);

		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
		// imagecopyresized($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);


		// if there is no photos directory, create it
		if (!file_exists($targetImagePath)) {
			mkdir($targetImagePath, 0777, true);
		}

		// if there is no thumbs directory, create it
		if (!file_exists($targetThumbsPath)) {
			mkdir($targetThumbsPath, 0777, false);
		}


		if (imagejpeg($new_image, $targetThumbsPath . $filename)) {
			
		} else {
			
		}

		exit;

	}

}