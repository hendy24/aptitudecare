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
		smarty()->assign('title', "Upload Photos");
	}


	/*
	 * -------------------------------------------------------------------------
	 * AJAX call to delete tag from photo
	 * -------------------------------------------------------------------------
	 */
	public function delete_tag() {
		// Get the id for the tag by name
		$tag = $this->loadModel('PhotoTag')->fetchByName(input()->tag_name);

		// Delete the linked photo tag
		if ($this->loadModel('PhotoLinkTag')->deleteLinkedTag(input()->photo_id, $tag->id)) {
			return true;
		}

		return false;
	}


	/*
	 * Add photo info page
	 *
	 */
	public function photo_info() {
		smarty()->assign('title', "Add Photo Info");

		$user_id = auth()->getRecord()->id;
		// need to get the photos which have been uploaded by this user which have not add info added to them yet.
		$photos = $this->loadModel("Photo")->fetchPhotosWithoutInfo($user_id);

		smarty()->assignByRef('photos', $photos);
	}



	/*
	 * -------------------------------------------------------------------------
	 *  Fetch the available photo tags
	 * -------------------------------------------------------------------------
	 */
	public function fetchTags() {
		$options = $this->loadModel('PhotoTag')->fetchAll();
		json_return($options);

	}



	/*
	 * Save the uploaded photo info
	 *
	 */
	public function save_photo_info() {
		$success = false;

		if (input()->photo_id != "") {
			$photo = $this->loadModel("Photo", input()->photo_id);
		} else {
			$this->redirect(input()->current_url);
		}

		// add the photo name
		if (input()->name != "") {
			$photo->name = input()->name;
		}

		// add description
		if (input()->description != "") {
			$photo->description = input()->description;
		}

		// we have added the info now
		$photo->info_added = true;

		if (isset (input()->approved)) {
			$photo->approved = input()->approved;
		}

		// save the photo info
		if ($photo->save()) {
			// save the tags
			foreach (input()->photo_tag as $tag) {
				// create an empty object for the photo tag link
				$photo_link_tag = $this->loadModel('PhotoLinkTag');

				// set the photo id for the linked tag
				$photo_link_tag->photo_id = $photo->id;

				// check for pre-existing tags with the same name
				$photo_tag = $this->loadModel('PhotoTag')->find_existing($tag);

				// if an existing tag is found set the id equal to the existing tag
				if (!empty ($photo_tag)) {
					$photo_link_tag->tag_id = $photo_tag->id;
				} else {
					// if no tag is found we need to create it first
					$new_photo_tag = $this->loadModel('PhotoTag');
					$new_photo_tag->name = $tag;
					$new_photo_tag->save();

					// now we can set the id
					$photo_link_tag->tag_id = $new_photo_tag->id;
				}

				$photo_link_tag->save();
				$success = true;
			}

			if ($success) {
				return true;
			}
			return false;
		} 
		return false;
	}




	/*
	 * View Photos page
	 *
	 */
	public function view_photos() {
		smarty()->assign('title', "View Photos");

		if (isset (input()->current_page)) {
			$current_page = input()->current_page;
		} else {
			$current_page = false;
		}

		$photos = $this->loadModel("Photo")->paginateApprovedPhotos($current_page);
		smarty()->assign('photos', $photos);
	}

	public function view_photos_json() {
		$photos = $this->loadModel("Photo")->fetchApprovedPhotos();
		echo json_encode($photos);
		exit;
	}



	/*
	 * Manage Photos page
	 *
	 */
	public function manage_photos() {
		if (!auth()->hasPermission('manage_dietary_photos')) {
			session()->setFlash("You do not have permission to access this page.", 'error');
			$this->redirect();
		}

		smarty()->assign('title', "Manage Photos");
		$photos = $this->loadModel("Photo")->fetchPhotosForApproval();

		// fetch tags
		foreach ($photos as $k => $p) {
			$photos[$k]->tag = $this->loadModel('PhotoTag')->fetchTags($p->id); 
		}
		smarty()->assign('photos', $photos);
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
			$targetPath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/";;
			$fileName = getRandomString() . "." . $fileType;
			$targetFile = $targetPath . $fileName;

			if (move_uploaded_file($tempFile, $targetFile)) {
				// success
				// need to create a file name and save to photo table
				$photo = $this->loadModel("Photo");
				$photo->location_id = $location->id;
				$photo->filename = $fileName;
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
	public function approve_photos() {
		$success = false;
		if (!empty (input()->photo)) {
			foreach (input()->photo as $id => $approved) {
				$photo = $this->loadModel("Photo", $id);
				$photo->approved = $approved;
				$photo->user_approved = auth()->getRecord()->id;
				if ($photo->save()) {
					if ($approved == false) {
						// delete the file image and thumbnail
						$targetImagePath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/";
						$targetThumbsPath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/thumbnails/";
						unlink($targetImagePath . $photo->filename);
						unlink($targetThumbsPath . $photo->filename);
					}
					$success = true;
				}
			}
			if ($success) {
				session()->setFlash("The photos were approved.", 'success');
				$this->redirect(array("module" => "Dietary"));
			} else {
				session()->setFlash("Could not save the photos. Please try again.", 'error');
				$this->redirect(input()->current_url);
			}
		}


	}




	/*
	 * Create and save photo thumbnails
	 *
	 */
	public function createThumbnail($filename) {
		$targetImagePath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/";
		$targetThumbsPath = dirname(dirname(dirname(dirname (__FILE__)))) . "/public/files/dietary_photos/thumbnails/";

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
			echo "hello";
		} else {
			echo "goodbye";
		}

		exit;

	}

}