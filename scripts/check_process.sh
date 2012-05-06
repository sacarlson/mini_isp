#!/bin/sh -e
echo [ "$(pidof openvpn)" ]
if [ "$(pidof openvpn)" ] 
then
  echo process was found
else
  echo process not found
fi
