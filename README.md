# hMailServer-MySQL-Connection-Log

Searchable Event Log for hMailServer 


# Prerequisites: 
* MySQL
* Apache/IIS with PHP
* hMailServer 5.7 (or custom build w/newer features)


# Instructions:

 1) Clone to web accessible folder
 2) Rename `/config.dist.php` and `/tasks/hMSLogConfig.dist.ps1` and fill in the variables
 3) Run `/tasks/hMSLogSetupTasks.ps1` to create database tables and scheduled tasks
 4) Use examples in `/events/EventHandlers.vbs` to modify your own EventHandlers.vbs

