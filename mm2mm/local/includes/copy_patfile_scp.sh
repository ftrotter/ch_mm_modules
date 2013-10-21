#!/bin/bash
patfile_current_timestamp=`stat -c %Y /usr2/db2/patfile.dat`
patfile_synced_timestamp=`cat /root/patfile_last_sync`
if [ "$patfile_current_timestamp" -ne "$patfile_synced_timestamp" ] 
then 
#php /root/mm2mm/local/includes/patfile_split.php
scp /usr2/db2/patfile.dat medman@example.synseer.net:patfile.dat
scp /usr2/db2/offnotes.dme medman@example.synseer.net:offnotes.dme
stat -c %Y /usr2/db2/patfile.dat > /root/patfile_last_sync
fi
