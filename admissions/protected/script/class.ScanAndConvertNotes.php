<?php

class ScanAndConvertNotes extends CLIScript {
	
	protected static $firstRun = "2011-09-14 09:00:00";
	protected static $intervalDays = 0;
	protected static $intervalHours = 0;
	protected static $intervalMinutes = 2;

	protected static $enabled = false;
	
	protected static function processBucket($pdfPath, $bucketPath, &$record, $idx) {
		echo "Convert {$pdfPath}:\n";
		// split the PDF into one PDF per page
		$cmd = "/usr/bin/pdftk {$pdfPath} burst output \"{$bucketPath}/%02d.pdf\"";
		exec($cmd);
		
		// go to sleep so the previous command can complete
		sleep(15);
		
		// convert each PDF page into a PNG
		$cmd = "for i in {$bucketPath}/*.pdf; do /usr/bin/convert -density 600x600 -resize 600 -quality 90 \$i {$bucketPath}/`basename \$i .pdf`.png; done";
		exec($cmd);
		
		// go to sleep so the previous command can complete
		sleep(15);
		
		// go through all the PNGs we did create, and drop the corresponding source PDFs
		$cmd = "for i in {$bucketPath}/*.png; do rm {$bucketPath}/`basename \$i .png`.pdf; done";
		exec($cmd);
		
		$record->{"notes_converted{$idx}"} = 1;
		$record->save();
		//echo ">> Done.\n";
		
	}
	
	public static function exec() {
		
		ini_set('error_reporting', 0);
		
		// fetch records that have notes pending conversion
		$notFlagged = CMS_Patient_Admit::fetchForNotesConversion();
		
		
		// cycle through them
		foreach ($notFlagged as $record) {			
			// check every possible notes column
			for ($i=0;$i<11;$i++) {
				// note uploaded for this column but not converted yet
				if ($record->{"notes_file{$i}"} != '' && $record->{"notes_converted{$i}"} == 0) {
					//echo "Looking at " . $record->{"notes_file{$i}"} . "\n";
					
					// resolve path to, and name of, lockfile
					$lockFilename = $record->{"notes_file{$i}"} . ".lock";
					$lockFilePath =  APP_PATH . '/protected/var/note-scan-locks/' . $lockFilename;
					
					//echo ">> Lockfile is " . $lockFilePath . "\n";
					
					
					// make sure it exists
					if (! file_exists($lockFilePath) ) {
						touch($lockFilePath);
					}
					
					// acquire exclusive, non-blocking lock on the lockfile
					$fp = fopen($lockFilePath, 'r+');
					if (! flock($fp, LOCK_EX | LOCK_NB)) {
						// skip to next file or record if lock cannot be acquired
						//echo ">> Lock is in use, moving on.\n";
						continue;
					}			
					//echo ">> Acquired lock\n";
					
					$basename = basename($record->{"notes_file{$i}"}, ".pdf");
					$pdfPath = APP_PATH . "/protected/assets/patient_admit_notes_file{$i}/" . $record->{"notes_file{$i}"};
					$bucketPath = APP_PATH . "/protected/assets/patient_admit_notes_file{$i}/" . $basename;
					
					// the source file has to actually exist, otherwise skip it.
					if (file_exists($pdfPath)) {
						//echo ">> {$pdfPath} does exist.\n";
						// make the bucket directory, make it writable
						if (! file_exists($bucketPath) ) {
							echo ">> Creating bucket path\n";
							@mkdir($bucketPath);
							@chmod($bucketPath, 0777);
						}
						
						//echo ">> Starting conversion\n";
						
						//echo ">> But first, going to sleep for 20 seconds\n";
						//sleep(20);
						
						// do the conversion
						static::processBucket($pdfPath, $bucketPath, $record, $i);
					} else {
						//echo ">> {$pdfPath} does not exist.\n";
						// file does not exist on disk, so let's null it out.
						// this will prevent future invocations of this script from attempting this one again.
						$record->{"notes_file{$i}"} = '';
						$record->save();
					}
					
					// release the lock
					flock($fp, LOCK_UN);
					fclose($fp);
					//echo ">> Released lock\n";
					
					// drop the lockfile
					@unlink($lockFilePath);
				}
			}
		}
		
		
	}
	
	/*
	 * 2012-09-19 - bjc - old version, now removed. this was very filesystem resource intensive.
	 * 
	public static function exec() {
		ini_set('error_reporting', 0);
		// open the assets dir
		$dirAssets = dir(APP_PATH . "/protected/assets");
		while (false !== ($entryAssets = $dirAssets->read())) {
			if ($entryAssets == "." || $entryAssets == "..") {
				continue;
			}
			if (preg_match("/^patient_admit_notes_file/", $entryAssets)) {
				$dirNotesIdx = dir($dirAssets->path . "/{$entryAssets}");
				while (false !== ($entryNotesIdx = $dirNotesIdx->read())) {
					if ($entryNotesIdx == "." || $entryNotesIdx == ".." || ! preg_match("/\.pdf$/", $entryNotesIdx)) {
						continue;
					}
					
					$pdfPath = "{$dirAssets->path}/{$entryAssets}/{$entryNotesIdx}";
					$basename = basename($entryNotesIdx, ".pdf");
					$bucketPath = "{$dirAssets->path}/{$entryAssets}/{$basename}";
					
					if (! file_exists($bucketPath) ) {
						// make the bucket directory, make it writable
						@mkdir($bucketPath);
						@chmod($bucketPath, 0777);
						
						// process the bucket
						static::processBucket($pdfPath, $bucketPath);
					}
					
					// bucket path does exist. if it's not empty, drop it and process.
					else {
						$dirBucket = dir($bucketPath);
						$countBucket = 0;
						$clearBucket = false;
						while (false !== ($entryBucket = $dirBucket->read())) {
							// ignore unix traversals
							if ($entryBucket == "." || $entryBucket == "..") {
								continue;
							}
							
							// if there are any PDFs, it means we had an aborted conversion and should clear the bucket
							if (preg_match("/\.pdf$/", $entryBucket)) {
								$clearBucket = true;
								break;
							}
							
						}
						
						if ($clearBucket == true) {
							// drop everything from the bucket
							exec("rm -rf {$bucketPath}/*");
							
							// re-process the bucket
							static::processBucket($pdfPath, $bucketPath);
						}
					}
					
				}
			}
		}
	}
	*/
	
}