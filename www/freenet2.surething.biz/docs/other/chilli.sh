#!/bin/sh /etc/rc.common
# ============================================
# == Coova Chilli Startup Script =============
# == Hardware: Ubiquity PicoStation2 =========
# == Version: 0.1 ============================
# == Date: 2009-10-22 ========================
# == Author: Dirk van der Walt ===============
# ============================================

START=80
STOP=85

. /etc/chilli/functions

start() {
        echo start
        # commands to launch application
        /sbin/modprobe tun > /dev/null 2>&1
        echo 1 > /proc/sys/net/ipv4/ip_forward
        writeconfig
        radiusconfig
        iptables -F POSTROUTING -t nat
        iptables -I POSTROUTING -t nat -o $HS_WANIF -j MASQUERADE
        ifconfig $HS_LANIF 0.0.0.0
        checkrunning


}

stop() {
        echo stop
        killall chilli
        # commands to kill application
}

checkrunning(){
        check=`/bin/pidof chilli`
        if [ -z $check ]
        then
                echo "Chilli not running"
                chilli

        else
                echo "Chilli runnig PID: "$check
        fi
}
