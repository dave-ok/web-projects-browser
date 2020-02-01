#!/bin/bash
myfile="$PWD/$1"
$(touch -a "$myfile")
# current=$(date +%s)
# last_modified=$(stat -c "%Y" "$myfile")
# timediff=$((current-$last_modified))
# echo $(date -d @$current)
# echo $(date -d @$last_modified)
# echo $timediff
# if [ $timediff -ge 5 ]; then 
#   touch -a "$myfile"
# fi
# #echo "$myfile"
