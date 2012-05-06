#! /bin/sh
iptables -nv -L -t nat --line-number --exact
iptables -nv -L --exact
#iptables -nv -L -t mangle --exact
#iptables -nv -L -t mangle
