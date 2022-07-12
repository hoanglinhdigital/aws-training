terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "3.72.0"
    }
  }
}

provider "aws" {
  region = "us-east-1"
}


###### Self custom vpc module

# module "vpc" {
#   source = "../../module/vpc"

#   environment = "stag"
# }

###### Pre defined AWS modules

locals {
  project_name = "demo"

  common_tags = {
    Project     = local.project_name
    Environment = "stag"
  }
}


module "vpc" {
  source = "terraform-aws-modules/vpc/aws"

  name = "my-vpc"
  cidr = "10.0.0.0/16"

  azs             = ["us-east-1a", "us-east-1c"]
  private_subnets = ["10.0.1.0/24", "10.0.2.0/24"]
  public_subnets  = ["10.0.3.0/24", "10.0.4.0/24"]

  #   enable_nat_gateway = true
  #   enable_vpn_gateway = true

  tags = local.common_tags

}


# module "instance" {
#   source = "../module/ec2_ebs"
# }

resource "aws_network_interface" "instance_eni" {
  subnet_id = module.vpc.private_subnets[0]

  tags = merge(local.common_tags,
    tomap({
      Name = "primary_network_interface"
  }))
}

resource "aws_instance" "project_instance" {

  ami           = "ami-055d15d9cfddf7bd3"
  instance_type = "t2.micro"

  network_interface {
    network_interface_id = aws_network_interface.instance_eni.id
    device_index         = 0
  }

  tags = merge(local.common_tags,
    tomap({
      Name = "demo"
  }))
}


module "common_sg" {
  source = "../../module/common_sg"

  vpc_id = module.vpc.vpc_id
}

# resource "aws_lb_target_group_attachment" "tg_attachment" {
#   target_group_arn = module.alb.arn
#   target_id        = aws_instance.project_instance.id
#   port             = 80
# }

module "alb" {
  source  = "terraform-aws-modules/alb/aws"
  version = "~> 6.0"

  name = "alb"

  load_balancer_type = "application"

  vpc_id          = module.vpc.vpc_id
  subnets         = module.vpc.public_subnets
  security_groups = [aws_security_group.allow_http.id]

  # access_logs = {
  #   bucket = "my-alb-logs"
  # }

  target_groups = [
    {
      name             = "${local.project_name}-tg1"
      backend_protocol = "HTTP"
      backend_port     = 80
      target_type      = "instance"
      targets = [
        {
          target_id = aws_instance.project_instance.id
          port      = 80
        }
      ]
    }
  ]

  #   https_listeners = [
  #     {
  #       port               = 443
  #       protocol           = "HTTPS"
  #       certificate_arn    = "arn:aws:iam::123456789012:server-certificate/test_cert-123456789012"
  #       target_group_index = 0
  #     }
  #   ]

  http_tcp_listeners = [
    {
      port               = 80
      protocol           = "HTTP"
      target_group_index = 0
    }
  ]

  tags = {
    Environment = "Test"
  }
}

# module "database" {
#   source = "../module/db"
# }
