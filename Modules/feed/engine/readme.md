About exportCSV and MysqlTimeSeries.php


To work like a real analog meter or smart meter, the meter result comes at the end of period.
In this example the time period is 15 minutes, all values are aligned at the end of time period
Because the  unix time stamp is not so easy the confert with a spreadsheet, the query makes the convertion.
The average value may be rounded with the sql query.

Beacaus CSV is different in Europe or US, the column separator and the decimal seperator
would be defined by the user, maybe in its profile.  The same applies to the date format to use when converting to human readable date and time.
It can be done through the translation process, but it's more related to the user's machine configuration.


The storage table structure uses a field called time, it may be a source of problems due to existing time instruction.
A name like tstamp is short and explains what it is, while it does not correspond to any instruction

I do'nt understand the range selection above and under 180000 seconds!

Here a demonstration query on feed id = 3, and 15 minutes averages, like a smart meter.

SELECT from_unixtime(CEIL(time/900)*900,'%Y-%m-%d %h:%i:%s') AS fulltime,
 from_unixtime(min(time),'%Y-%m-%d %h:%i:%s') AS mintime,
  from_unixtime(max(time),'%Y-%m-%d %h:%i:%s') AS maxtime,
   count(time) as countSamples,
   round(avg(data)*100)/100 as AverageDataValue
   FROM feed_3 
   WHERE time BETWEEN 1390608000 AND 1395515501 
   GROUP BY 1 
   order by time desc
