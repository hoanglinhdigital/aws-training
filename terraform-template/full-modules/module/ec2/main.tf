terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}


resource "aws_network_interface" "bastion_linux_nic" {
  subnet_id   = var.ec2_bastion_subnet_id
  # private_ips = ["172.16.10.100"]
  security_groups = var.ec2_bastion_security_group_ids
  tags = {
    Name = join("-", [var.system_code, var.env_code, "bastion_linux_nic"])
  }
}
resource "aws_eip" "bastion_linux_eip" {
  vpc = true
  network_interface = aws_network_interface.bastion_linux_nic.id
}

resource "aws_instance" "bastion_linux_server" {
  ami = var.ec2_bastion_ami_id
  instance_type = var.ec2_bastion_instance_type
  # subnet_id = var.ec2_bastion_subnet_id  #-> Not specify because confict with: network_interface
  # vpc_security_group_ids = var.ec2_bastion_security_group_ids  #-> conflict with: network_interface
  key_name = var.ec2_bastion_keypair
  network_interface {
    network_interface_id = aws_network_interface.bastion_linux_nic.id
    device_index = 0
  }
  root_block_device {
    volume_size = var.ec2_bastion_volume_size
    volume_type = "gp3"
    tags = {
      FileSystem = "/root"
    }
  }
  # associate_public_ip_address = true
  tags = {
    Name = join("-", [var.system_code, var.env_code, "bastion-host-linux-01"])
  }
  depends_on = [
    aws_network_interface.bastion_linux_nic
  ]
  iam_instance_profile = aws_iam_instance_profile.bastion_instance_profile.name
}

resource "aws_iam_instance_profile" "bastion_instance_profile" {
  name = join("-", [var.system_code, var.env_code, "bastion-host-instance-profile"])
  role = var.ec2_bastion_role_name
}