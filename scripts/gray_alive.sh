#!/bin/bash
check ()
{
result=$(ssh sacarlson@192.168.2.112 /home/sacarlson/imalive.sh)
if [ "$result" == "ok" ]; then
  echo "return ok"
  return 0
else
  return 255
fi
}

check

#$(ssh -f sacarlson@192.168.2.112 ./imalive.sh)
#result="$( ssh -f sacarlson@192.168.2.112 /home/sacarlson/imalive.sh )"
#echo $result
#nohup 'ssh  sacarlson@192.168.2.112 ./imalive.sh' > foo.out 2> foo.err < /dev/null &
