terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.27"
    }
  }
  required_version = ">= 0.14.9"
}
provider "aws" {
  profile = "default"
  region  = "us-east-1"
}
locals {
  ami           = var.ami
  instance_type = var.instance_type
  name          = var.name
  keyname = var.keyname
  subnet_id = var.subnet_id
  vpc_id = var.vpc_id
}


#Ec2 instance for test
# resource "aws_instance" "myapp" {
#   ami                         = local.ami
#   subnet_id                   = local.subnet_id
#   vpc_security_group_ids = [aws_security_group.allow_ssh.id]
#   key_name                    = local.keyname
#   associate_public_ip_address = true
#   instance_type               = local.instance_type
#   tags = {
#     Name = local.name
#   }
#   depends_on = [
#     aws_security_group.allow_ssh
#   ]
# }

#Security Group
resource "aws_security_group" "allow_ssh" {
  name        = "allow-ssh-lab2"
  description = "Allow SSH inbound traffic"
  vpc_id = local.vpc_id
  ingress { // inbound
    description = "Allow SSH"
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  egress { // outbound
    from_port        = 0
    to_port          = 0
    protocol         = "-1"
    cidr_blocks      = ["0.0.0.0/0"]
    ipv6_cidr_blocks = ["::/0"]
  }
  tags = {
    Name = "allow_ssh"
  }
}
