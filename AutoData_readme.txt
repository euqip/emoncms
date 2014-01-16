AutoData_readme.txt

# create a bash file to run with CRON



#!/bin/sh

# $r is a random value in the range of 100
r=$(( $RANDOM % 100));
# do not forget you write apikey there is no coockies when using command line
/usr/bin/wget -q "http:/myServerAddress/input/post.json?json={inputname:$r}&apikey=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
# same syntax to run different inputs with its own random or steady value
# end of File

# setup a CRON job to execute bash file each let say 15 minutes
crontab -e
# select your prfered plain text editor
# add following line
0,15,30,45 * * * * [username] bash /pathToBashFile/bashfile.sh


Save crontab file
close corntab file
Linux will execute the bash file every hour at 0,15,30, and 45min.
