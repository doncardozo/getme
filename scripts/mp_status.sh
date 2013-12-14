#!/bin/bash    

d=$1 # Mount point's directory
p=$2 # Mount point's name.
c=$d$p

if mountpoint -q "$c"; then    
    echo "yes"
else
    echo "no"
fi