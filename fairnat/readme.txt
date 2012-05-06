to get this to work just edit the config file and change line with
USERS=" 180@4kbit|2kbit"

where 180 is singling out address 192.168.2.180 to go at 4kbit/sec download max  and 2kbit/sec uploadmax

then run the script sudo /home/freenet/fairnat/fairnat-0.80-dhcp.sh

now tested on ubuntu 11.04  didn't crash or give any errors but not sure it works yet


to stop: sudo ./fairnat-0.80-dhcp.sh stop
this is now added to foropen.sh


