<?php
/* Autobackup-databse-on-GoogleDrive
   Copyright (C) 2019 EngAcs <http://www.engacs.pw>

   settings.inc.php
 */

  // User home directory (absolute)
  $homedir = "backup/"; // If this doesn't work, you can provide the full path yourself
  // Site directory (relative)
  $sitedir = ""; 
  // Base filename for backup file
  $fprefix = "sitebackup-";
  // Base filename for database file
  $dprefix = "dbbackup-";
  // MySQL username
  $dbuser = "root";
  // MySQL password
  $dbpass = "";
  // MySQL database
  $dbname = "taabo_db";
 // MySQL Host
  $host = "localhost";

  // Set the parent folder. Google Drive 
  $parentId = "";

  // Google Drive Client ID
  $clientId = ""; // Get this from the Google APIs Console https://code.google.com/apis/console/
  // Google Drive Client Secret
  $clientSecret = ""; // Get this from the Google APIs Console https://code.google.com/apis/console/
  // Google Drive authentication code
  $authCode = ""; // Needs to be set using getauthcode.php first!    
  // Request URI 
  $requestURI = "urn:ietf:wg:oauth:2.0:oob";
?>