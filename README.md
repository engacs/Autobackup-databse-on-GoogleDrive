# Autobackup databse to GoogleDrive

<p><strong>Some settings</strong><br>
Firstly, we'll need to setup some variables for use throughout.</p>

<pre style="background-color: #FFFFFF; color: #000000">
 // User home directory (absolute)
  $homedir = "backup/"; // If this doesn't work, you can provide the full path yourself
 // Site directory (relative)
  $sitedir = ""; 
 // Base filename for backup file
 $fprefix = "sitebackup-";
 // Base filename for database file
 $dprefix = "dbbackup-";
 // MySQL username
 $dbuser = "";
 // MySQL password
 $dbpass = "";
 // MySQL database
 $dbname = "";
 // MySQL Host
 $host = "";
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
</pre>
<br>
<p><strong>Automating the process</strong><br>
Once everything is in place, you are authenticated and everything is working a simple crontab item should be enough to get things on the go.</p>
<pre style="background-color: #FFFFFF; color: #000000">5 0 * * 6 /usr/local/bin/php /home/myusername/backuptogoogledrive.php</pre>
<p>Or you can use <a href="https://cron-job.org/en/" target="_blank">cron-job.org</a>.</p>

<br>   Copyright (C) 2019 EngAcs <http://www.engacs.pw>
