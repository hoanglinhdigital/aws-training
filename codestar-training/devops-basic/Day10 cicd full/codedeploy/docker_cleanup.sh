#!/bin/bash
source /home/ec2-user/web/.env

docker stop $(docker ps -qa) || true
docker system prune -f --volumes || true
docker image prune -a -f
