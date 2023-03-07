######################## format using xfs ##################
#list all partition and mountpoint
lsblk
#display all volume and free space
df -h
#Using fdisk to create partition.
sudo fdisk /dev/xvdf

#format partition
sudo mkfs -t xfs /dev/xvdf1
#Mount
sudo mount /dev/xvdf1 /data

#Grow part after extend EBS
sudo growpart /dev/xvdf 1

#=====Mount a EBS volume created from snapshot to recover data=====
#Method 1: Generate new UUID then mount
#Change UUID of new EBS Volume
xfs_admin -U generate /dev/xvdg1
#Mount
mount -t xfs /dev/xvdg1 /data-backup

#Method 2: Workaround by using -o nouuid
mount -t xfs -o nouuid /dev/xvdg1 /data-backup
