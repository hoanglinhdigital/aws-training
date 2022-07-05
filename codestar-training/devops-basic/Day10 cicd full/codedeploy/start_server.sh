#!/bin/bash
aws ecr get-login-password --region us-east-1 | docker login --username AWS --password-stdin 430950558682.dkr.ecr.us-east-1.amazonaws.com
cd /home/ec2-user/web/ && /usr/local/bin/docker-compose -f docker-compose.yml up -d
