# NSX-LogViewer is used for VMware NSX to help the organisation and people outside of the NSX team to check if traffic on a given IP/VM is dropped.

!["NSX-LogViewer Presentation"](http://sorenisager.com/wp-content/uploads/2020/10/NSX-LogViewer.png "NSX-LogViewer Presentation")
 
## Installation
1. Create virtual machine or docker lamp image (Could be like: https://www.howtoforge.com/tutorial/install-apache-with-php-and-mysql-on-ubuntu-18-04-lamp/)
2. GIT clone to the desired directory, may be /var/www/ or /var/www/html/ (git clone https://github.com/sorenisager/NSX-Logviewer.git)
3. Change the config file with your desired settings
4. Import SQL file into MariaDB/MySQL database to get it to work
5. Test if it works, if not - check errors or let me help you.
7. Add entries in the ReverseLookup file, so you can resolve the IP's in the log into servernames. (This is being showed directly in the log file)

For this to be working, you need to configure your NSX and VMware Hosts to send logs to LogInsight (With enabled NSX ContentPack)

## ReverseLookup
Gives the Option in the config file to reverse lookup ips into hostnames in the Alerthandler function. Loginsight is not sending the servernames in the logs. This helps us understand which system is affected at the dashboard directly.

Its a JSON file ex:
"10.10.100.85": "dhcp-server"

## Security
There is no page security at the moment added to the code, you may need to use .htaccess or other type of security to prevent others from seeing the applicationdata.

## Current Version - 1.0

The current version of the NSX-LogViewer

### Future Versions

There may be future versions, it depends on the demand.

## See It in Action

Look at sorenisager.com

## Requirements

* LAMP stack (PHP7+)
- MariaDB or MySQL is fine.

## Template design used from other project
[AdminLTE](https://github.com/ColorlibHQ/AdminLTE)

## MIT Licensing

The software is free to use, but i can never behold responsible for anything.