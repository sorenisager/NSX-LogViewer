<?php
# . Made by Soren Isager @ Sorenisager.com @ https://github.com/sorenisager/
# . Free to use, MIT license
# . If any improvements, send me a new request ;)

# Site Configuration
    $SiteTitle = "NSX LOG VIEWER";
    $ApplicationName = "NSX LOG VIEWER v1";

# ReverseLookup Configuration
    $ReverseLookup = true; # True = Try to lookup known servers (Default) -- False = Do not try to lookup (Faster)
    $ReverseLookupJsonFilePath = "ReverseLookup.json"; # Filepath for the ReverseLookup in json format

# Search Options
    $SearchInterval = array(
            "15 min" => "900000",
            "1 hour" => "3600000", 
            "3 hours" => "10800000",
            "24 hours" => "86400000",
            "7 days" => "604800000"
    ); # The search timeframe option, add one specific if you need, have in mind the limit (LogInsightLimit) and queries taking long time if searching logdata for a long period of time.

# Log configuration
    $ShowLogAction = array("DROP","PASS"); # Possible: PASS|DROP|PUNT|REDIRECT|COPY|TERMINATE|TERM (Default: DROP+PASS)
    $ShowLogActionColor = array("DROP" => "danger","PASS" => "success"); # Dethermine which color to show in the interface, Inputs is the same as $ShowLogAction but there are multiple colors: sucess|danger|warning|info|default (Default: "DROP" => "danger","PASS" => "success")

# Other Configuration
    $DateFormat = "d-m-Y"; # Date configuration : Do your own, see ref. https://www.php.net/manual/en/datetime.format.php (Default: d-m-Y)
    $TimeFormat = "H:i:s"; # Time configuration : Do your own, see ref. https://www.php.net/manual/en/datetime.format.php (Default: H:i:s)
    $DateTimeZone = "Europe/Copenhagen"; # TimeZone configuration : Do your own, see ref. https://www.php.net/manual/en/timezones.php (Default: Europe/Copenhagen)

# Loginsight Configuration
    $LogInsightFQDN = ""; # FQDN to log-insight, if using loadbalancer, use this
    $LogInsightUserName = ""; # Username to LogInsight
    $LogInsightPassword = ""; # Password to LogInsight
    $LogInsightLoginProvider = "Local"; # Which authentication provider does you use? (Default: Local) Options: Local | ActiveDirectory
    $LogInsightLimit = 5000; # Number of logs trying to fetch from Loginsight service (Default: 5000)


# MySQL Config
$MysqlHost = ""; # Insert FQDN on remote mysql database server or localhost if the mysql is hosted on the same server
$MysqlUserName = ""; # Username for login
$MysqlPassword = ""; # Password for login
$MysqlDatabase = ""; # Database to connect (Create one and then type it in)
    

# MySQL Connection
    $MySQLConnection = mysqli_connect(
                    $MysqlHost,
                    $MysqlUserName,
                    $MysqlPassword,
                    $MysqlDatabase
                );

    # Error handling
        if (!$MySQLConnection)
            {
                die("Connection failed: " . mysqli_connect_error());
            }

?>
