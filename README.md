# hMailServer-MySQL-Connection-Log

Searchable Event Log for hMailServer 


# Prerequisites: 
* MySQL
* Apache/IIS with PHP
* RvdH's OnHELO custom build (to obtain sender EHLO): http://hmailserver.com/forum/viewtopic.php?p=213193#p213193


# Instructions:

1) Create MySQL table in existing hmailserver database:

```
CREATE TABLE `hm_accrej` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `stringport` varchar(4) NOT NULL,
  `port` int(3) NOT NULL,
  `event` varchar(20) NOT NULL,
  `accrej` varchar(20) NOT NULL,
  `reason` varchar(20) NOT NULL,
  `ipaddress` varchar(15) NOT NULL,
  `country` varchar(30) DEFAULT NULL,
  `helo` varchar(192) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

2) Download VbsJson.vbs and place in program files\hMailServer\Events folder.
https://github.com/eklam/VbsJson

3) Modify EventHandlers.vbs

4) Place index.php in a web accessible folder.