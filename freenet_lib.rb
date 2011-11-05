#!/usr/bin/ruby
#this lib was writen to support the infrastructure of the Internet service provider called FreeNET.
#The freenet ISP franchise is a specialized short to medium duration prepaid ISP services for resort condo and hotels.
# see http://freenet.surething.biz for details

# Copyright 2011  Scott Carlson  sacarlson@ipipi.com 
# Licensed under the Apache License, Version 2.0 (the "License");
#   you may not use this file except in compliance with the License.
#   You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
#   Unless required by applicable law or agreed to in writing, software
#   distributed under the License is distributed on an "AS IS" BASIS,
#   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#   See the License for the specific language governing permissions and
#   limitations under the License.

require 'rubygems'
require 'mysql'
require './config.rb'  


def openMysql(con=nil)
  if con == nil then    
    con = Mysql.new($host,$user,$password,$database)
  end
  return con
end

def enable_ip(ip)
  #enable Internet access to this ip with iptables changes
  system("sudo iptables -t nat -I PREROUTING  -s #{ip} -j RETURN ") 
  system("sudo iptables -I FORWARD -d #{ip}")
  system("sudo iptables -I FORWARD -s #{ip}")
end

def readtables(filename = "#{$workingdir}userusage.txt")
  #collect data from iptables to be used in bytesTX and bytesRX
  system("sudo iptables -nv -L FORWARD --exact > " + filename)
end 

def get_iptables_data(filename = "#{$workingdir}userusage.txt")
  # return an array from data collected in readtables to be used in bytesXX(ip,filedata,colnum) function
  f = File.open(filename)
  filedata = f.readlines
  f.close
  return filedata
end

def zerocounter()
  # zero byte counters in iptables 
  system("sudo iptables -Z")
end

def record_bytes_rx(ip,bytes,con=nil) 
  #add number of bytes used to present records of ip_link account of ip address to mysql freenet3 data
  con = openMysql(con)
  qstring = "UPDATE ip_links SET bytes_rx=bytes_rx+#{bytes} WHERE ip_address='#{ip}'"
  con.query(qstring)
  qstring = "UPDATE ip_links SET bytes_rx_last=#{bytes} WHERE ip_address='#{ip}'"
  con.query(qstring)
  if (get_bytes_rx_ip_max(ip,con) < bytes) then
    qstring = "UPDATE ip_links SET bytes_rx_max=#{bytes} WHERE ip_address='#{ip}'"
    con.query(qstring)
  end
end

def record_bytes_tx(ip,bytes,con=nil)
  #add number of bytes used to present records of ip_link account of ip address to mysql freenet3 data
  con = openMysql(con)
  qstring = "UPDATE ip_links SET bytes_tx=bytes_tx+#{bytes} WHERE ip_address='#{ip}'"
  con.query(qstring)
  qstring = "UPDATE ip_links SET bytes_tx_last=#{bytes} WHERE ip_address='#{ip}'"
  con.query(qstring)
  if (get_bytes_tx_ip_max(ip,con) < bytes) then
    qstring = "UPDATE ip_links SET bytes_tx_max=#{bytes} WHERE ip_address='#{ip}'"
    con.query(qstring)
  end
end



def get_ip_list(con=nil)
  #return list of ip address in ip_link of mysql freenet3
  con = openMysql(con)
  qstring = "SELECT * FROM ip_links "
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['ip_address'])}
  return list
end

def check_ip_exist(ip,con=nil)
  #see if ip already exists in ip_links table
  con = openMysql(con)
  list = get_ip_list(con)
  return list.include?(ip)
end

def get_active_ip(con=nil)
  #return array of present active IP's in freenet3 customers table accounts
  # this obsolete now , should use get_whitelist instead
  con = openMysql(con)
  qstring = "SELECT `customers_ip_address`
               FROM `customers`
               WHERE `customers_date_account_expires` > NOW( )"

  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['customers_ip_address'])}
  return list
end

def set_ip_links_value(user_id, verible, value, con=nil)
  con = openMysql(con)
  qstring = "UPDATE ip_links SET #{verible}='#{value}' WHERE user_id='#{user_id}'" 
  #puts qstring                
  return con.query(qstring)
end


def reset_active(con=nil)
  con = openMysql(con)
  qstring = "UPDATE ip_links SET active='0' WHERE active='1'" 
  #puts qstring                
  return con.query(qstring)
end

def check_active_id(user_id, con=nil)
  #return true if user_id is set to active in ip_links table
  con = openMysql(con)
  qstring = "SELECT active
               FROM ip_links
               WHERE user_id='#{user_id}'"

  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['active'])}
  puts list[0]
  return (list[0]=="1")
end

def set_active_id(user_id,con=nil)
  # set active bit true in ip_links table for user_id
  # if no change detected return false, if bit changed set bit to 1 and return true
  puts "set user_id #{user_id} active ***"
  if !check_active_id(user_id) then
    set_ip_links_value(user_id, "active", 1)
    return true
  end
  set_ip_links_value(user_id, "active", 1)  
  return false
end

def set_not_active_id(user_id,con=nil)
  # set active bit true in ip_links table for user_id
  # if no change detected return false, if change needed set bit to 0 and return true
  puts "set user_id #{user_id} inactive"
  if check_active_id(user_id) then
    set_ip_links_value(user_id, "active", 0)
    return true
  end
  set_ip_links_value(user_id, "active", 0)
  return false
end

def check_ip(ip,active_list)
  #check to see that an ip is in the active user list array
  user_id = get_user_id_ip(ip)
  return active_list.include?(user_id)
end

def get_active_customers_id(con=nil)
  #return array with list of customers_id that are not time expired
  con = openMysql(con)
  #reset_active(con)
  qstring = "SELECT `customers_id`
               FROM `customers`
               WHERE `customers_date_account_expires` > NOW( )"
  rs = con.query(qstring)
  list = []
  listfinal = []
  rs.each_hash { |h| list.push( h['customers_id'])}
  return list
end

def set_active_ip(con=nil)
  # set the active bit in each active ip in ip_links list
  # return false if no changes were made in any of the list, true if one or more changes in status were set.
  change_detected = false
  con = openMysql(con)
  ip_list = get_ip_list(con)
  active_list = get_active_customers_id(con)
  ip_list.each do |ip|
    puts "ip = #{ip}"
    user_id = get_user_id_ip(ip)
    if check_ip(ip,active_list)     
      if check_byte_account(user_id,con)
        if set_active_id(user_id) then
          change_detected = true
        end
      else
        if set_not_active_id(user_id) then
          change_detected = true
        end
      end
    else
      if set_not_active_id(user_id) then
        change_detected = true
      end
    end
  end
  return change_detected
end


def get_ip_value(ip, verible, con=nil)
  #get any value in ip_links table with collum name verible for this ip
  con = openMysql(con) 
  qstring = "SELECT *
               FROM ip_links
               WHERE ip_address= '#{ip}'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h[verible])}
  if verible == "user_name" then
    return list[0]
  end
  return Integer(list[0]) 
end

def get_user_name(ip,con=nil)
  return get_ip_value(ip, "user_name", con)
end

def get_bytes_last_tot_ip(ip,con=nil)
  return get_ip_value(ip,'bytes_rx_last',con)+ get_ip_value(ip,'bytes_tx_last',con)
end

def get_bytes_rx_ip(ip,con=nil)
  return get_ip_value(ip,'bytes_rx',con)
end

def get_bytes_tx_ip(ip,con=nil)
  return get_ip_value(ip,'bytes_tx',con)
end

def get_bytes_rx_ip_max(ip,con=nil)
  return get_ip_value(ip,'bytes_rx_max',con)
end

def get_bytes_tx_ip_max(ip,con=nil)
  return get_ip_value(ip,'bytes_tx_max',con)
end

def get_fairnat_kbit_start(ip,con=nil)
  return get_ip_value(ip, 'fairnat_kbit_start', con)
end

def get_fairnat_kbit(ip,con=nil)
  return get_ip_value(ip, 'fairnat_kbit', con)
end

def get_fairnat_speed(ip,con=nil)
  return get_ip_value(ip, 'fairnat_speed', con)
end

def get_bytes_xx_user_id(user_id,tx=0,con=nil)
  # return recorded byte count for user_id as seen in ip_links data in mysql
  # tx = 1  for tx byte count 0 for rx byte count
  con = openMysql(con)
  if tx == 1 then
    qstring = "SELECT bytes_tx
               FROM ip_links
               WHERE user_ID= '#{user_id}'"
  else
    qstring = "SELECT bytes_rx
               FROM ip_links
               WHERE user_ID= '#{user_id}'"
  end
  rs = con.query(qstring)
  list = []
  if tx == 0 then
    puts "rx mode"
    rs.each_hash { |h| list.push( h['bytes_rx'])}
  else
    puts "tx mode"
    rs.each_hash { |h| list.push( h['bytes_tx'])}
  end
  total = 0
  list.each do |bytes|
    puts "bytes = #{bytes}"
    total = total + Integer(bytes)
  end
  return total             
end

def get_bytes_tx_user_id(user_id,con=nil)
  tx = 1
  return get_bytes_xx_user_id(user_id,tx,con)
end

def get_bytes_rx_user_id(user_id,con=nil)
  tx = 0
  return get_bytes_xx_user_id(user_id,tx,con)
end

def get_bytes_total_user_id(user_id,con=nil)
  #return total recieved + transmited network trafic byte count from user_id as tracked in ip_links table
  con = openMysql(con)
  return (get_bytes_tx_user_id(user_id,con) + get_bytes_rx_user_id(user_id,con))
end
 
def get_customers(user_id,verible,con=nil)
  # lookup the value in customers table for the value of table verible name of verible
  con = openMysql(con)  
  qstring = "SELECT *
               FROM customers
               WHERE customers_id= '#{user_id}'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h[verible])}  
  return list[0]
end

def get_bytes_start(user_id,con=nil)
  # get customers_bytes_start from mysql customers table
  return Integer(get_customers(user_id,"customers_bytes_start",con))
end

def get_bytes_ordered(user_id,con=nil)
  # get customers_bytes_ordered from mysql customers table
  return Integer(get_customers(user_id,"customers_bytes_ordered",con))
end

def set_bytes_start(user_id, bytes, con=nil)
  #set the byte count in customers_bytes_start to present value in records of ip_links table
  con = openMysql(con)  
  qstring = "UPDATE customers SET customers_bytes_start=#{bytes} WHERE customers_id='#{user_id}'"
  return con.query(qstring)
end

def set_bytes_ordered(user_id, bytes, con=nil)
  #set the byte count in customers_bytes_start to present value in records of ip_links table
  con = openMysql(con)  
  qstring = "UPDATE customers SET customers_bytes_ordered=#{bytes} WHERE customers_id='#{user_id}'"
  return con.query(qstring)
end

def set_customer_expire(user_id, datestamp, con=nil)
  #set the byte count in customers_bytes_start to present value in records of ip_links table
  con = openMysql(con)  
  qstring = "UPDATE customers SET customers_date_account_expires=#{datestamp} WHERE customers_id='#{user_id}'"
  return con.query(qstring)
end

def make_byte_order(user_id, mbytes, hours=1440,con=nil)
  #setup a byte count order for user_id
  # used when a customer sets up a freenet order with limited byte usage
  # bytes are counted as a total of transmited and recieved forwarded bytes counted in ip_links table for user_id
  con = openMysql(con)
  bytes_start = get_bytes_total_user_id(user_id,con)
  last_bytes_start = get_bytes_start(user_id)
  lastorder = get_bytes_ordered(user_id)
  bytes_leftover = lastorder-(bytes_start - last_bytes_start)
  if bytes_leftover < 0 then
    bytes_leftover = 0
  end
  set_bytes_start(user_id, bytes_start, con) 
  set_bytes_ordered(user_id, (mbytes*1000000)+bytes_leftover, con)
  make_time_order(user_id, hours)
  set_active_id(user_id)
end

def make_time_order(user_id, hours,con=nil)
  verifble = "customers_date_account_expires"
  #expires = get_customers(user_id,verible,con=nil)
  set_customer_expire(user_id, "DATE_ADD(NOW(), INTERVAL #{hours} HOUR)")
end

def check_byte_account(user_id,con=nil)
  #check to see if user_id has any bytes left in limited byte usage account
  # returns true if customer_bytes_ordered is zero or if (customer_bytes_ordered < (customer_total_bytes_now - customer_bytes_start)
  #con = openMysql(con)
  bytes_ordered = get_bytes_ordered(user_id)
  if bytes_ordered == 0 then
    puts "bytes ordered zero"
    return true
  end
  bytes_start = get_bytes_start(user_id)
  total_bytes_now = get_bytes_total_user_id(user_id) 
  puts "bytes_start = #{bytes_start}"
  puts "total_bytes_now = #{total_bytes_now}" 
  if bytes_ordered >= (total_bytes_now - bytes_start) then
    puts "check_bytes_account returned true"
    return true
  else
    if bytes_ordered > 0 then
       set_bytes_ordered(user_id, 0)
       set_bytes_start(user_id, 0)
       set_customer_expire(user_id, "NOW()")
    end
    return false
  end
end

def get_whitelist(con=nil) 
  con = openMysql(con)
  #change_detected = set_active_customers_id(con)
  qstring = "SELECT * FROM ip_links WHERE active='1'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['ip_address'])}
  return list
end

def gen_whitelist(whitelistfile=nil)
  if whitelistfile == nil then
    whitelistfile = "#{$workingdir}whitelist.txt"
  end
  list = get_whitelist()
  File.open(whitelistfile, 'w') do |f|
    list.each do |ip|    
      f.puts(ip)
    end
  end
end

def get_user_id_ip(ip,con=nil)
  #get the user_id linked to ip address in ip_links table
  con = openMysql(con)
  qstring = "SELECT `user_ID`
               FROM `ip_links`
               WHERE ip_address= '#{ip}'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['user_ID'])}
  return list[0]
end

def get_customers_id(ip,con=nil)
   #get customers_id from registered ip address
   con = openMysql(con)
   qstring = "SELECT `customers_id`
               FROM `customers`
               WHERE customers_ip_address= '#{ip}'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['customers_id'])}
  return list[0]
end

def get_customers_name(ip,con=nil)
   #get customers first name from registered ip address
   con = openMysql(con)
   qstring = "SELECT `customers_firstname`
               FROM `customers`
               WHERE customers_ip_address= '#{ip}'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['customers_firstname'])}
  return list[0]
end

def get_customers_name_id(user_id,con=nil)
   #get customer first name from user_id number
   con = openMysql(con)
   qstring = "SELECT `customers_firstname`
               FROM `customers`
               WHERE customers_id= '#{user_id}'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['customers_firstname'])}
  return list[0]
end

def check_mac(ip,con=nil)
   #return mac address now in mysql register 
   con = openMysql(con)
   qstring = "SELECT `mac_address`
               FROM `ip_links`
               WHERE ip_address= '#{ip}'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['mac_address'])}
  return list[0]
end

def get_mac_address(ip)
  #return mac address of an ip address, system must be active on network to work
  mac = `sudo arp -n #{ip}`
  mac2 = mac.split(" ")
  #puts mac2
  puts "mac = #{mac2[8]}"
  return mac2[8]
end


def set_mac(ip,mac,con=nil)
  #set mac address of this ip in mysql ip_links table
  con = openMysql(con)  
  qstring = "UPDATE ip_links SET mac_address='#{mac}' WHERE ip_address='#{ip}'" 
  puts qstring                
  rs = con.query(qstring)
end

def make_dhcpd_conf(fileout="#{$workingdir}dhcpd.conf")
  dhcp_template_file = "#{$workingdir}dhcpd.conf.template"
  system("cp #{dhcp_template_file} #{fileout}")
  list = get_whitelist(con=nil)
  list.each do |ip|    
    add_dhcp(ip,fileout)
  end
end

def add_dhcp(ip,fileout=nil)
  
  if (fileout == nil) then
    fileout = "#{$workingdir}dhcpd.conf"
  end
  
  mac = check_mac(ip)
  if (mac.length < 11) then
    return false
  end
  user_name = get_user_name(ip,con=nil)
  
  columns = ip.split(".")
  lastdigit = columns[3]
  puts "lastdigit = #{lastdigit}"

  entry = "
host #{user_name}#{lastdigit} {
  hardware ethernet #{mac};
  fixed-address #{ip};
  option broadcast-address 192.168.2.255;
  option domain-name-servers freenet_dns,opendns1,opendns2;
  option routers freenet_router;
}"
 #max-lease-time 300;

 puts entry

 File.open( fileout, 'a') do |f|     
    f.puts entry
 end
end


def bytesTotal(ip,filedata)
   return bytesTX(ip,filedata) + bytesRX(ip,filedata)
end


def bytesXX(ip,filedata,colnum)
  if ip.length < 6 then
    return 0
  end
  filedata.each do |row|
    columns = row.split(" ")
    if columns[colnum].include?(ip.strip) then
      puts "columns[1] = #{columns[1]}"
      return Integer(columns[1])
    end  
  end
  return 0
end

def bytesTX(ip,filedata)
  colnum = 6
  return bytesXX(ip,filedata,colnum)
end

def bytesRX(ip,filedata)
  colnum = 7
  return bytesXX(ip,filedata,colnum)
end


def ip_add(ip, user_id=nil)
  # add ip address to usage tracker
  
  if user_id == nil then
    user_id = get_customers_id(ip)
  end 
  mac = get_mac_address(ip)
  puts "user_id = #{user_id}"
  if (user_id == "0") then
    user_name = get_customers_name(ip)
    puts "user_name1 = #{user_name}"
  end
  if (user_id != "0" and user_name == nil) then
    user_name = get_customers_name_id(user_id)
    puts "user_name2 = #{user_name}"
  end
  if ip.length < 6 then
    return nil
  end
  puts "user_name3 = #{user_name}" 
  qstring = "INSERT INTO ip_links (ip_address, user_ID, user_name, mac_address)
                VALUES ('#{ip}', '#{user_id}', '#{user_name}', '#{mac}')" 
  puts qstring 
  con = openMysql()                
  rs = con.query(qstring)
  puts rs
end

def update_tracker()
  list = get_active_ip()
  list.each do |ip|
    if ip == nil then
      next
    end
    user_id = get_customers_id(ip)
    puts ip
    puts user_id
    if !check_ip_exist(ip) then
      if ip.length < 6 then
        next
      end
      ip_add(ip,user_id)
    end
  end
end


def update_track_record()
  list = get_whitelist() 
  readtables()
  sleep 1
  data = get_iptables_data()
  list.each { |ip|
    puts "ip = #{ip}"
    puts "RX = #{bytesRX(ip,data)}"
    puts "TX = #{bytesTX(ip,data)}"
    record_bytes_rx(ip,bytesRX(ip,data))
    record_bytes_tx(ip,bytesTX(ip,data))
  }
  zerocounter()
end

def update_last_seen(ip,con=nil)
  con = openMysql(con)  
  qstring = "UPDATE ip_links SET date_last_seen=NOW() WHERE ip_address='#{ip}'" 
  puts qstring                
  rs = con.query(qstring)
end

def update_mac(con=nil)
  con = openMysql(con) 
  get_ip_list(con).each do |ip|
    puts "ip = #{ip}"
    mac = get_mac_address(ip)
    if mac != nil then
      update_last_seen(ip)
    else
      next
    end
    if check_mac(ip).length == 0 then
      puts "no records for this ip found"      
      if mac != nil then
        set_mac(ip,mac)
        puts "mac for ip #{ip} now added, registered as #{mac}"
      end
    end
  end
end

def append_to_file(string,filename)
  File.open( filename, 'a') do |f|     
    f.puts string
  end 
end

def set_fairnat_speed(ip, value, con=nil)
  #set the byte count in customers_bytes_start to present value in records of ip_links table
  con = openMysql(con)  
  qstring = "UPDATE ip_links SET fairnat_speed=#{value} WHERE ip_address='#{ip}'"
  return con.query(qstring)
end

def set_fairnat_kbit(ip, value, con=nil)
  #set the byte count in customers_bytes_start to present value in records of ip_links table
  con = openMysql(con)  
  qstring = "UPDATE ip_links SET fairnat_kbit=#{value} WHERE ip_address='#{ip}'"
  return con.query(qstring)
end


#use get_fairnat_speed instead 
def check_fairnat_speed2(ip,con=nil)
  #get customer first name from user_id number
   con = openMysql(con)
   qstring = "SELECT fairnat_speed
               FROM ip_links
               WHERE ip_address= '#{ip}'"
  rs = con.query(qstring)
  list = []
  rs.each_hash { |h| list.push( h['fairnat_speed'])}
  return Integer(list[0])
end

def make_fairnat_users(triger_slow=nil, triger_mid=nil, iptables_data_file=nil, force=false)
  #create the USERS= string needed to append to fairnat.config.template to control bandwidth of users
  #iptables_data_file name is what was created with readtables(filename = "#{$workingdir}userusage.txt")
  #triger_slow is the value that trigers puting a user into slow_speed b/w mode if this value is greater than get_bytes_last_tot
  #triger_mid is the same as triger_slow above this value will put user in mid_speed b/w mode
  #force will force it to gen users return even if no changes detected
  # return true if any changes detected in fairnat settings
  #slow_speed = "@800kbit|800kbit"
  #mid_speed = "@1200kbit|1200kbit"
  #high_speed = "@2000kbit|2000kbit"
  slow_mult = 0.4
  mid_mult = 0.6
  high_mult = 1
  min_speed = 500
  mult = 1
  change_detected = force
  if iptables_data_file == nil then
    iptables_data_file = "#{$workingdir}userusage.txt"
  end
  if triger_slow == nil then
    triger_slow = 450000000
  end
  if triger_mid == nil then
    triger_mid = 300000000
  end
  puts "triger_slow = #{triger_slow}"
  puts "triger_mid = #{triger_mid}"
  list = get_whitelist()
  users = "USERS=" + '"'
  list.each do |ip|
    bytes_last_tot_ip = get_bytes_last_tot_ip(ip)
    fairnat_kbit = get_fairnat_kbit(ip)
    fairnat_kbit_start = get_fairnat_kbit_start(ip)
    puts "fairnat_kbit_start = #{fairnat_kbit_start} for ip #{ip}"  
    columns = ip.split(".")
    puts "ip = #{ip}"
    puts "columns[3] = #{columns[3]}"
    puts "bytes used in last sample for ip #{ip}) = #{bytes_last_tot_ip}"
    speed = Integer(fairnat_kbit_start * slow_mult)
    if ( bytes_last_tot_ip > triger_slow) then
      puts "ip #{ip} set to slow"
      speed = Integer(fairnat_kbit_start * slow_mult)
      #users = users + columns[3] + slow_speed + " "
      if fairnat_kbit != speed then
        change_detected = true
        set_fairnat_kbit(ip, speed)
      else
        if speed > min_speed then
          speed = speed - 100
          change_detected = true
          set_fairnat_kbit(ip, speed)
        end
      end
    elsif ( bytes_last_tot_ip > triger_mid) then
      puts "ip #{ip} set to mid"
      speed = Integer(fairnat_kbit_start * mid_mult)
      #users = users + columns[3] + mid_speed + " "
      if (fairnat_kbit != speed) then
        change_detected = true
        set_fairnat_kbit(ip, speed)
      end
    else
      puts "ip #{ip} set to high"
      speed = Integer(fairnat_kbit_start * high_mult)
      #users = users + columns[3] + high_speed + " "
      if fairnat_kbit != speed then
        change_detected = true
        set_fairnat_kbit(ip, speed)
      end
    end
    speedstring = "@#{speed}kbit|#{speed}kbit"
    users = users + columns[3] + speedstring + " "     
  end
  users = users + '"'
  puts users
  if change_detected then
    return users
  else
    puts "no triger detected"
    return nil
  end
end

def make_fairnat_config(triger_slow=nil, triger_mid=nil, iptables_data_file=nil, force=false)
  users = make_fairnat_users(triger_slow, triger_mid, iptables_data_file, force)
  if users == nil then
    puts "no trigers detected no changes in fairnat config created"
    return false
  end
  system("cp #{$workingdir}fairnat.config.template #{$workingdir}fairnat.config")
  append_to_file(users,"#{$workingdir}fairnat.config")
  return true
end

def get_ip_dhcp_value(verible,con=nil)
  #get customers_id from registered ip address
   con = openMysql(con)
   qstring = "SELECT #{verible}
               FROM ip_dhcp"
  rs = con.query(qstring)
  rs.each do |row|
    return Integer(row[0])
  end
end

def get_last_ip_added(con=nil)
   return get_ip_dhcp_value("last_ip_added",con)
end

def get_start_ip_added(con=nil)
   return get_ip_dhcp_value("start_ip_added",con)
end

def get_max_add(con=nil)
   return get_ip_dhcp_value("max_add",con)
end

def set_last_ip_added(value,con=nil)
  #set the last_ip_added to value in ip_dhcp table in mysql records
  con = openMysql(con)  
  qstring = "UPDATE ip_dhcp SET last_ip_added=#{value} "
  return con.query(qstring)
end

def change_ip_address(ip,newip)
  con = openMysql(con)
  qstring = "UPDATE ip_links SET ip_address='#{newip}' WHERE ip_address='#{ip}'" 
  #puts qstring                
  con.query(qstring)
  qstring = "UPDATE customers SET customers_ip_address='#{newip}' WHERE customers_ip_address='#{ip}'"
  return con.query(qstring) 
end

def set_perm_ip()
  #fix any newly added customer ip to perminent status in upper IP address ariea outside dhcp range
  #using ip_dhcp settings
  flag = false
  last_ip = get_last_ip_added()
  if (last_ip > get_max_add(con=nil)) then return nil end
  start_ip = get_start_ip_added() 
  list = get_whitelist()
  list.each do |ip|
    if check_mac(ip).length == 0 then
      next
    end
    if (Integer(ip.split(".")[3]) < start_ip) then
      ipsplit = ip.split(".")
      puts "ip = #{ip}"
      newip = "#{ipsplit[0]}.#{ipsplit[1]}.#{ipsplit[2]}.#{last_ip+1}"
      puts "newip = #{newip}"
      set_last_ip_added(last_ip+1,con=nil)
      change_ip_address(ip,newip)
      flag = true
    end
  end
  if flag then
    puts "flag set will update dhcpd.conf"
    make_dhcpd_conf(fileout="#{$workingdir}dhcpd.conf")
    sleep 1
    system("sudo cp #{$workingdir}dhcpd.conf /etc/dhcp3/dhcpd.conf")
    sleep 2
    system("sudo /etc/init.d/dhcp3-server restart")
  end  
end

