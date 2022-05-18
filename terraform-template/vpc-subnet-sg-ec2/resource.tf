#This template create bellow resource:
#1 VPC
#2 Subnets, route table
#3 Security groups
#4 NAT gateway (Optional)
#5 Internet gateway
#6 Ec2 instance with simple user-data to install httpd

terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
}

# Configure the AWS Provider
provider "aws" {
  region = "us-east-1"
  profile = "linhnguyen.admin"
  shared_credentials_file = "%USERPROFILE%\\.aws\\credentials"
}

# 1 Create a VPC
resource "aws_vpc" "terra-vpc" {
  cidr_block = "172.3.0.0/16"
  tags = {
    "Name" = "Terra"
  }
}
# Create Internet gateway
resource "aws_internet_gateway" "gw" {
  vpc_id = aws_vpc.terra-vpc.id
  
}
# Allocate an Elastic IP
resource "aws_eip" "eip-01" {
  vpc = true
}

#Subnets
resource "aws_subnet" "public-subnet-1" {
  vpc_id = aws_vpc.terra-vpc.id
  cidr_block = "172.3.1.0/24"
  availability_zone = "us-east-1a"
  tags = {
    "Name" = "public-subnet-1"
  }
}
resource "aws_subnet" "public-subnet-2" {
  vpc_id = aws_vpc.terra-vpc.id
  cidr_block = "172.3.2.0/24"
  availability_zone = "us-east-1f"
  tags = {
    "Name" = "public-subnet-2"
  }
}

resource "aws_subnet" "private-subnet-1" {
  vpc_id = aws_vpc.terra-vpc.id
  cidr_block = "172.3.3.0/24"
  availability_zone = "us-east-1a"
  tags = {
    "Name" = "private-subnet-1"
  }
}
resource "aws_subnet" "private-subnet-2" {
  vpc_id = aws_vpc.terra-vpc.id
  cidr_block = "172.3.4.0/24"
  availability_zone = "us-east-1f"
  tags = {
    "Name" = "private-subnet-2"
  }
}

#Main route table
resource "aws_route_table" "terra-main-rtb" {
  vpc_id = aws_vpc.terra-vpc.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.gw.id
  }

  tags = {
    Name = "terra-main-rtb"
  }
}
#Private route table
resource "aws_route_table" "terra-private-rtb" {
  vpc_id = aws_vpc.terra-vpc.id

  route {
    cidr_block = "0.0.0.0/0"
    nat_gateway_id = aws_nat_gateway.terra-nat.id
  }

  tags = {
    Name = "terra-private-rtb"
  }
  depends_on = [aws_nat_gateway.terra-nat]
}
#Subnet association
resource "aws_route_table_association" "terra-main-rtb-association1" {
  subnet_id = aws_subnet.public-subnet-1.id
  route_table_id = aws_route_table.terra-main-rtb.id
}
resource "aws_route_table_association" "terra-main-rtb-association2" {
  subnet_id = aws_subnet.public-subnet-2.id
  route_table_id = aws_route_table.terra-main-rtb.id
}

resource "aws_route_table_association" "terra-private-rtb-association1" {
  subnet_id = aws_subnet.private-subnet-1.id
  route_table_id = aws_route_table.terra-private-rtb.id
}
resource "aws_route_table_association" "terra-private-rtb-association2" {
  subnet_id = aws_subnet.private-subnet-2.id
  route_table_id = aws_route_table.terra-private-rtb.id
}
#Public Security Group
resource "aws_security_group" "terra-public-sg-01" {
    name = "terra-public-sg-01"
    description = "Allow public traffic"
    vpc_id = aws_vpc.terra-vpc.id
    ingress = [ {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All 443"
      from_port = 443
      to_port = 443
      protocol = "TCP"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    },
    {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All 80"
      from_port = 80
      to_port = 80
      protocol = "TCP"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    },
    {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All SSH"
      from_port = 22
      to_port = 22
      protocol = "TCP"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    } ]
    egress = [ {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All outbound"
      from_port = 0
      to_port = 0
      protocol = "-1"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    } ]
}
#Private security group
resource "aws_security_group" "terra-private-sg-01" {
    name = "terra-private-sg-01"
    description = "Allow public traffic"
    vpc_id = aws_vpc.terra-vpc.id
    ingress = [ 
    {
      cidr_blocks = []
      description = "All ssh"
      from_port = 22
      to_port = 22
      protocol = "TCP"
      source_security_group_id = aws_security_group.terra-public-sg-01.id
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    },
    {
      cidr_blocks = []
      description = "All SQL"
      from_port = 3306
      to_port = 3306
      protocol = "TCP"
      source_security_group_id = aws_security_group.terra-public-sg-01.id
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false

    } ]
    egress = [ {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All outbound"
      from_port = 0
      to_port = 0
      protocol = "-1"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    } ]
}
# Allocate an Elastic IP for NAT
resource "aws_eip" "terra-nat-eip" {
  vpc = true
  depends_on = [
    aws_internet_gateway.gw
  ]
}
#NAT Gateway
resource "aws_nat_gateway" "terra-nat" {
  allocation_id = aws_eip.terra-nat-eip.id
  subnet_id     = aws_subnet.public-subnet-1.id
  tags = {
    Name = "gw NAT"
  }
  depends_on = [aws_internet_gateway.gw, aws_eip.terra-nat-eip]
}

#Web server EC2
resource "aws_instance" "web-server" {
  ami = "ami-0c02fb55956c7d316" #Amz Linux 2
  instance_type = "t3.micro"
  subnet_id = aws_subnet.public-subnet-1.id  #-> Not specify because confict with: network_interface
  vpc_security_group_ids = [aws_security_group.terra-public-sg-01.id]  #-> conflict with: network_interface
  key_name = "linh-ec2-keypair-02"
  # network_interface {
  #   network_interface_id = aws_network_interface.web-server-nic.id
  #   device_index = 0
  # }
  associate_public_ip_address = true
  user_data = <<-EOF
              #!/bin/bash
              sudo yum update -y
              sudo yum install -y https://s3.region.amazonaws.com/amazon-ssm-region/latest/linux_amd64/amazon-ssm-agent.rpm
              sudo yum install httpd -y
              sudo systemctl enable httpd
              sudo systemctl start httpd
              sudo bash -c 'echo your very first web server > /var/www/html/index.html'
              EOF
  tags = {
    Name ="Web server"
  }
  # depends_on = [
  #   aws_network_interface.web-server-nic
  # ]
}

# resource "aws_eip" "terra-eip" {
#   vpc = true
#   network_interface = aws_network_interface.web-server-nic.id
#   associate_with_private_ip="172.3.1.15"
#   # depends_on = [aws_internet_gateway.gw]
  
# }

# #Network interface
# resource "aws_network_interface" "web-server-nic" {
#   subnet_id = aws_subnet.public-subnet-1.id
#   private_ip = "172.3.1.15"
#   security_groups = [aws_security_group.terra-public-sg-01.id]
#   # attachment {
#   #   instance     = aws_instance.web-server.id
#   #   device_index = 1
#   # }
#   # depends_on = [
#   #   aws_instance.web-server
#   # ]
# }

