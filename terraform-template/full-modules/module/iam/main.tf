terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}
#ECS Task role
resource "aws_iam_role" "ecs_task_role" {
  name = join("-", [var.system_code, var.env_code, "ecs-task-role-01"])
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Sid    = ""
        Principal = {
          Service = "ecs.amazonaws.com"
        }
      },
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Sid    = ""
        Principal = {
          Service = "ecs.amazonaws.com"
        }
      }
    ]
  })
  managed_policy_arns = ["arn:aws:iam::aws:policy/CloudWatchAgentServerPolicy"]
  tags = {
    Name = join("-", [var.system_code, var.env_code, "ecs-task-role-01"])
  }
}

resource "aws_iam_role_policy" "ecs_custom_policy_01" {
  name = join("-", [var.system_code, var.env_code, "ecs-custom-policy-01"])
  role = aws_iam_role.ecs_task_role.id
  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Action = [
          "s3:GetObject", "s3:PutObject"
        ]
        Effect   = "Allow"
        Resource = "arn:aws:s3:::${var.region_code}-${var.system_code}-${var.env_code}-*/*"
      }
    ]
  })
}
#ECS Task Execution role
resource "aws_iam_role" "ecs_task_execution_role" {
  name = join("-", [var.system_code, var.env_code, "ecs-task-execution-role-01"])
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Sid    = ""
        Principal = {
          Service = "ecs.amazonaws.com"
        }
      },
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Sid    = ""
        Principal = {
          Service = "ecs.amazonaws.com"
        }
      }
    ]
  })
  managed_policy_arns = [
    "arn:aws:iam::aws:policy/service-role/AmazonECSTaskExecutionRolePolicy",
    "arn:aws:iam::aws:policy/service-role/AmazonEC2ContainerServiceRole"
    ]
  tags = {
    Name = join("-", [var.system_code, var.env_code, "ecs-task-execution-role-01"])
  }
}

#ECS Autoscaling Role role
resource "aws_iam_role" "ecs_auto_scaling_role" {
  name = join("-", [var.system_code, var.env_code, "ecs-auto-scaling-role-01"])
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Sid    = ""
        Principal = {
          Service = "ecs.amazonaws.com"
        }
      },
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Sid    = ""
        Principal = {
          Service = "ecs.amazonaws.com"
        }
      }
    ]
  })
  managed_policy_arns = ["arn:aws:iam::aws:policy/service-role/AmazonEC2ContainerServiceAutoscaleRole"]
  tags = {
    Name = join("-", [var.system_code, var.env_code, "ecs-auto-scaling-role-01"])
  }
}

#Bastion Role
resource "aws_iam_role" "ec2_bastion_role" {
  name = join("-", [var.system_code, var.env_code, "ec2-bastion-role-01"])
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Sid    = ""
        Principal = {
          Service = "ec2.amazonaws.com"
        }
      },
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Sid    = ""
        Principal = {
          Service = "ecs.amazonaws.com"
        }
      }
    ]
  })
  managed_policy_arns = [
    "arn:aws:iam::aws:policy/service-role/AmazonEC2RoleforSSM",
    "arn:aws:iam::aws:policy/CloudWatchAgentServerPolicy"
  ]
  tags = {
    Name = join("-", [var.system_code, var.env_code, "ec2-bastion-role-01"])
  }
}