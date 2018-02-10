 <?php

   	require(APP_PROTECTED_DIR . DS . 'vendor/autoload.php');

   	use Aws\S3\S3Client;
   	use Aws\S3\Exception\S3Exception;


   	function connectToAws($region = 'us-east-1') {
   		$s3 = new Aws\S3\S3Client([
   			'version' => 'latest',
   			'region' => $region
   		]);

   		return $s3;
   	}
