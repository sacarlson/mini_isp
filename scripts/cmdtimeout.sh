#!/bin/bash
command=$1
# run $command in background, sleep for our timeout then kill the process if it  is running
$command &
pid=$!
echo "sleep $2; kill $pid" | at now
wait $pid &> /dev/null
if [ $? -eq 143 ]; then
echo "WARNING - command was terminated - timeout of $2 secs reached."
echo
fi

