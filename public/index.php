<?php
declare(strict_types=1);
if ( ! defined('CURL_SSLVERSION_TLSv1_2')) {
	define('CURL_SSLVERSION_TLSv1_2', 6);
}
//defined('CURL_SSLVERSION_DEFAULT') || define('CURL_SSLVERSION_DEFAULT', 0);
//defined('CURLOPT_SSLVERSION_TLSv1_2')   || define('CURLOPT_SSLVERSION_TLSv1_2', 1);
use AzurePHP\Service\mysql;
use AzurePHP\Service\AzureBlobService;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
//use MicrosoftAzure\Storage\Blob;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
require_once __DIR__ . '/../vendor/autoload.php';

$storageAccountName = getenv('STORAGE_ACCOUNT');
$connectionString = getenv('STORAGE_CONN_STRING') ?: '';

//echo $connectionString;

$blobClient = BlobRestProxy::createBlobService($connectionString);
$blobService = new AzureBlobService($blobClient);
$mysqlService = new mysql();
$containerName = 'azurephpdemo';
$filename = "";
//list blob
 $listBlobsOptions = new ListBlobsOptions();
   // $listBlobsOptions->setPrefix("HelloWorld");

    echo "These are the blobs present in the container: ";

    do{
	echo "<center>";
	echo "<table>";
        echo "<tr>";
	$i = 0;
        $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
        foreach ($result->getBlobs() as $blob)
        {
           $i=$i+1;
	   
	   $blobURL = $blob->getUrl();
           echo "<td align='center' style='width: 255px; height: 255px;'>";
	   //echo "<br>";
	   echo "<a href='" . $blobURL . "' >";
	   echo "<img src='";
           echo $blobURL;
           echo "' style='max-height:100%;max-width:100%;'/>";
	   echo "</a>";
	   echo "</td>";
	if($i==3){
        echo "</tr>";
        echo "<tr>";
	$i=0;
	} 
           // echo $blob->getName().": ".$blob->getUrl()."<br />";
        }
	echo "</table>";
	echo "</center>";
        $listBlobsOptions->setContinuationToken($result->getContinuationToken());
    } while($result->getContinuationToken());

//end

if ([] === $_FILES || !isset($_FILES['blob'])) {
    echo file_get_contents(__DIR__ . '/../tpl/upload-form.tpl');
    return;
}

if ('' === $connectionString) {
    throw new InvalidArgumentException(
        'Please set the environment variable STORAGE_CONN_STRING with the Azure Blob Connection String'
    );
}


try {
    $blobService->addBlobContainer($containerName);
    $blobService->setBlobContainerAcl($containerName, AzureBlobService::ACL_BLOB);
} catch (ServiceException $serviceException) {
    // Log the exception, most likely because the container already exists
}

try {
    
    $fileName = $blobService->uploadBlob($containerName, $_FILES['blob']);
} catch (ServiceException $serviceException) {
    // Log the exception, most likely connectivity issue
}

$fileLink = sprintf(
    '%s%s%s/%s/%s',
    'https://',
    $storageAccountName,
    '.blob.core.windows.net',
    strtolower($containerName),
    $fileName
);
$insertBlobSql = $mysqlService->insertDB1($filename, $fileLink);
echo sprintf(
    'Find the uploaded file at <a href="%s" target="_blank">%s</a>.',
    $fileLink,
    $fileLink
);

echo "<br>";
echo "<img src='";
echo $fileLink;
echo "' height='20%' width='20%' />";
echo '<br><a href="/">Reset</a>';

?>
