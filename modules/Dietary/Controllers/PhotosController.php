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
	 * Save the uploaded photo info 
	 *	
	 */
	public function save_photo_info() {
		if (input()->photo_id != "") {
			$photo = $this->loadModel("Photo", input()->photo_id);
		} else {
			$this->redirect(input()->current_url);
		}
		
		$photo->name = input()->name;
		$photo->description = input()->description;
		$photo->info_added = true;

		if ($photo->save()) {
			// when photos are uploaded, send an email to dietary managers?

			return true;
		}
		return false;
	}




	/* 
	 * View Photos page 
	 *	
	 */
	public function view_photos() {
		smarty()->assign('title', "View Photos");
		$photos = $this->loadModel("Photo")->fetchApprovedPhotos();
		smarty()->assign('photos', $photos);
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
				$photo->approved = false;
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


} // END CLASS