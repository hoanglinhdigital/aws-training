terraform {
  required_providers {
    aws = {
        source = "hashicorp/aws"
        version = "~> 3.0"
    }
  }
}
provider "aws" {
  region = "us-east-1"
  profile = "linhnguyen.admin"
  shared_credentials_file = "%USERPROFILE%\\.aws\\credentials"
}

#VPC
resource "aws_vpc" "terra-vpc" {
  cidr_block       = "10.0.0.0/16"
  instance_tenancy = "default"

  tags = {
    Name = "terra-vpc-01"
  }
}

#Subnets
resource "aws_subnet" "terra-public-01" {
  vpc_id     = aws_vpc.terra-vpc.id
  cidr_block = "10.0.1.0/24"
  availability_zone = "us-east-1a"
  tags = {
    Name = "terra-public-subnet-01"
  }
}
resource "aws_subnet" "terra-public-02" {
  vpc_id     = aws_vpc.terra-vpc.id
  cidr_block = "10.0.2.0/24"
  availability_zone = "us-east-1e"
  tags = {
    Name = "terra-public-subnet-02"
  }
}
resource "aws_subnet" "terra-private-01" {
  vpc_id     = aws_vpc.terra-vpc.id
  cidr_block = "10.0.3.0/24"
  availability_zone = "us-east-1a"
  tags = {
    Name = "terra-private-subnet-01"
  }
}
resource "aws_subnet" "terra-private-02" {
  vpc_id     = aws_vpc.terra-vpc.id
  cidr_block = "10.0.4.0/24"
  availability_zone = "us-east-1e"
  tags = {
    Name = "terra-private-subnet-02"
  }
}
#EC2
# resource "aws_instance" "linh-test-ec2" {
#   ami = "ami-0c02fb55956c7d316"
#   instance_type = "t3.medium"
#   subnet_id = "subnet-02803e231845c7c46"
#   vpc_security_group_ids = ["sg-0e820ff569f5916b1"]
#   key_name = "linh-ec2-keypair-01"
#   tags = {
#     Name = "ExampleInstance"
#   }
# }

