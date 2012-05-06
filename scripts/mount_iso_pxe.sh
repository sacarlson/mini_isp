sudo mount -o loop /home/sacarlson/Downloads/ubuntu-10.10-desktop-i386.iso /nfs-share/ubuntu
#make sure this is in /etc/exports: /nfs-share/ubuntu/    *(no_root_squash,rw,async,no_subtree_check)
#sudo exportfs -rv
sudo cp /nfs-share/ubuntu/casper/vmlinuz /var/lib/tftpboot/vmlinuz
sudo cp /nfs-share/ubuntu/casper/initrd.lz /var/lib/tftpboot/initrd.lz
#tested working with virtualbox diskless ok
