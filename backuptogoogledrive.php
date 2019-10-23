<?php
/*  Autobackup-databse-on-GoogleDrive
   Copyright (C) 2019 EngAcs <http://www.engacs.pw>

   backuptogoogledrive.php
   Main script file which creates gzip files and sends them to GoogleDrive */
   
  set_time_limit(0);
  ini_set('memory_limit', '1024M'); 
  require_once("google-api-php-client/src/Google_Client.php");
  require_once("google-api-php-client/src/contrib/Google_DriveService.php");
  include("settings.inc.php");
  
  if($authCode == "") die("You need to run getauthcode.php first!\n\n");
  
  /* PREPARE FILES FOR UPLOAD */
  
  // Use the current date/time as unique identifier

  $uid = date("YmdHis");

// Get connection object and set the charset
$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);
$conn->set_charset("utf8");
// Get All Table Names From the Database
$tables = array();
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}
$sqlScript = "";
foreach ($tables as $table) {
    
    // Prepare SQLscript for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);
    
    $sqlScript .= "\n\n" . $row[1] . ";\n\n";
    
    
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);
    
    $columnCount = mysqli_num_fields($result);
    
    // Prepare SQLscript for dumping data for each table
    for ($i = 0; $i < $columnCount; $i ++) {
        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j ++) {
                $row[$j] = $row[$j];
                
                if (isset($row[$j])) {
                    $sqlScript .= '"' . $row[$j] . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
    }
    
    $sqlScript .= "\n"; 
}

if(!empty($sqlScript))
{
    // Save the SQL script to a backup file
    $backup_file_name = $homedir.$dprefix.$dbname . '_' .   $uid . '.sql';
    $fileHandler = fopen($backup_file_name, 'w+');
    $number_of_lines = fwrite($fileHandler, $sqlScript);
    fclose($fileHandler); 

    $zip = new ZipArchive;
if ($zip->open($homedir.$fprefix.$uid.'.zip', ZipArchive::CREATE) === TRUE)
{
    // Add files to the zip file
    $zip->addFile($backup_file_name);

    // All files are added, so close the zip file.
    $zip->close();
}
}
  

  /* SEND FILES TO GOOGLEDRIVE */
  
  $client = new Google_Client();
  // Get your credentials from the APIs Console
  $client->setClientId($clientId);
  $client->setClientSecret($clientSecret);
  $client->setRedirectUri($requestURI);
  $client->setScopes(array("https://www.googleapis.com/auth/drive"));
  $service = new Google_DriveService($client);  
  // Exchange authorisation code for access token
  if(!file_exists("token.json")) {
    // Save token for future use
    $accessToken = $client->authenticate($authCode);      
    file_put_contents("token.json",$accessToken);  
  }
  else $accessToken = file_get_contents("token.json");
  $client->setAccessToken($accessToken); 

  // Upload file to Google Drive  
  $file = new Google_DriveFile();
  $file->setTitle($fprefix.$uid.'.zip');
  $file->setDescription("Server backup file");
  $file->setMimeType("application/gzip");
  $data = file_get_contents($homedir.$fprefix.$uid.'.zip');


   if ($parentId != null) {
    $parent = new Google_ParentReference();
    $parent->setId($parentId);
    $file->setParents(array($parent));
  }


  $createdFile = $service->files->insert($file, array('data' => $data, 'mimeType' => "application/gzip",));
  // Process response here....
  print_r($createdFile);     

  
  /* CLEANUP */
  
  // Delete created files
  unlink($homedir.$fprefix.$uid.".zip");
  unlink($homedir.$dprefix.$dbname .'_' . $uid . '.sql');
  
  /* References:
       https://developers.google.com/drive/quickstart-php */
?>