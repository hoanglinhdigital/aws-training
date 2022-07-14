terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}

#Application load balancer
resource "aws_lb" "main_alb" {
  name               = join("-", [ var.system_code, var.env_code, "alb-01"])
  internal           = false
  load_balancer_type = "application"
  security_groups    = var.alb_security_group_ids
  subnets            = var.alb_subnet_ids

  enable_deletion_protection = false

  access_logs {
    bucket  = var.alb_log_bucket_name
    prefix  = join("-", [ var.system_code, var.env_code, "alb-01-log"])
    enabled = true
  }

  tags = {
    Environment = var.env_code
    System = var.system_code
  }
}
#Target Group
resource "aws_lb_target_group" "backend_main_service_target_group" {
  name     = join("-", [ var.system_code, var.env_code, "main-service-tg"])
  port     = 3000
  protocol = "HTTP"
  target_type = "ip" #Using target type IP for ECS container services.
  vpc_id   = var.alb_vpc_id
}
#Listener port HTTPS(443)
resource "aws_lb_listener" "alb_backend_listener" {
  load_balancer_arn = aws_lb.main_alb.arn
  port              = "80"
  protocol          = "HTTP"
  #TODO: Enable after issued a valid certificate
  #ssl_policy        = "ELBSecurityPolicy-2016-08"
  #certificate_arn   = var.alb_ssl_certificate_arn

  default_action {
    type             = "forward"
    target_group_arn = aws_lb_target_group.backend_main_service_target_group.arn
  }
  depends_on = [
    aws_lb.main_alb,
    aws_lb_target_group.backend_main_service_target_group
  ]
}
