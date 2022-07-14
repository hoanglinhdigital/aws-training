terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}
#CW Log group
resource "aws_cloudwatch_log_group" "ecs_log_group_01" {
  name = join("-", [var.system_code, var.env_code, var.ecs_log_group_name])
  retention_in_days = 180
  tags = {
    Environment = var.env_code
  }
}
#ECS Cluster
resource "aws_ecs_cluster" "ecs_main_cluster_01" {
  name = join("-", [var.system_code, var.env_code, "ecs-cluster-01"])
  configuration {
    execute_command_configuration {
      logging    = "OVERRIDE"
      log_configuration {
        cloud_watch_encryption_enabled = false
        cloud_watch_log_group_name     = aws_cloudwatch_log_group.ecs_log_group_01.name
      }
    }
  }
  setting {
    name = "containerInsights"
    value = "enabled"
  }
  depends_on = [
    aws_cloudwatch_log_group.ecs_log_group_01
  ]
}

resource "aws_ecs_cluster_capacity_providers" "ecs_cluster_capacity_provider_01" {
  cluster_name = aws_ecs_cluster.ecs_main_cluster_01.name

  capacity_providers = ["FARGATE"]

  default_capacity_provider_strategy {
    base              = 1
    weight            = 100
    capacity_provider = "FARGATE"
  }
}

#ECS Main Service
resource "aws_ecs_service" "ecs_backend_main_servcice_01" {
  name            = join("-", [var.system_code, var.env_code, "backend-main-service-01"])
  launch_type = "FARGATE"
  cluster         = aws_ecs_cluster.ecs_main_cluster_01.id
  task_definition = aws_ecs_task_definition.ecs_backend_main_servcice_task_definition.arn
  desired_count   = 0
  # iam_role        = var.ecs_task_execution_role_arn #Do not specify if you run in VPC mode.
  network_configuration {
    subnets = var.ecs_service_subnets
    security_groups = var.ecs_service_security_groups
    assign_public_ip = false
  }

  load_balancer {
    target_group_arn = var.ecs_main_service_target_group_arn
    container_name   = "test_backend"
    container_port   = 3000
  }
  deployment_circuit_breaker {
    enable = true
    rollback = true
  }
  deployment_controller {
    type = "ECS"
  }
  
  depends_on      = [
    aws_ecs_cluster.ecs_main_cluster_01, 
    aws_ecs_task_definition.ecs_backend_main_servcice_task_definition
  ]
}

#ECS Main Service Task Definition
resource "aws_ecs_task_definition" "ecs_backend_main_servcice_task_definition" {
  family = "backend_main"
  execution_role_arn = var.ecs_task_execution_role_arn
  requires_compatibilities = ["FARGATE"]
  network_mode             = "awsvpc"
  cpu                      = var.ecs_main_service_cpu_limit
  memory                   = var.ecs_main_service_memory_limit
  container_definitions    = <<TASK_DEFINITION
  [
    {
      "name": "test_backend",
      "image": "${var.ecr_backend_main_repository_url}",
      "cpu": ${var.ecs_main_service_cpu_limit},
      "memory": ${var.ecs_main_service_memory_limit},
      "essential": true,
      "portMappings": [
        {
          "containerPort": 3000,
          "hostPort": 3000
        }
      ]
    }
  ]
  TASK_DEFINITION

  runtime_platform {
    operating_system_family = "LINUX"
    cpu_architecture        = "X86_64"
  }
}

#ECS sidekiq Service
resource "aws_ecs_service" "ecs_backend_sidekiq_servcice_01" {
  name            = join("-", [var.system_code, var.env_code, "backend-sidekiq-service-01"])
  launch_type = "FARGATE"
  cluster         = aws_ecs_cluster.ecs_main_cluster_01.id
  task_definition = aws_ecs_task_definition.ecs_backend_sidekiq_servcice_task_definition.arn
  desired_count   = 0
  #iam_role        = var.ecs_task_execution_role_arn  #Do not specify if you run in VPC mode.
  network_configuration {
    subnets = var.ecs_service_subnets
    security_groups = var.ecs_service_security_groups
    assign_public_ip = false
  }
  # Temporary disable load balance for sidekiq service.
  # load_balancer {
  #   target_group_arn = var.ecs_main_service_target_group_arn
  #   container_name   = "test_sidekiq"
  #   container_port   = 80
  # }
  deployment_circuit_breaker {
    enable = true
    rollback = true
  }
  deployment_controller {
    type = "ECS" # Valid value: CODE_DEPLOY, ECS, EXTERNAL
  }
  depends_on      = [
    aws_ecs_cluster.ecs_main_cluster_01,
    aws_ecs_task_definition.ecs_backend_sidekiq_servcice_task_definition
  ]
}

#ECS Sidekiq Service Task Definition
resource "aws_ecs_task_definition" "ecs_backend_sidekiq_servcice_task_definition" {
  family = "backend_sidekiq"
  execution_role_arn = var.ecs_task_execution_role_arn
  requires_compatibilities = ["FARGATE"]
  network_mode             = "awsvpc"
  cpu                      = var.ecs_main_service_cpu_limit
  memory                   = var.ecs_main_service_memory_limit
  container_definitions    = <<TASK_DEFINITION
  [
    {
      "name": "test_sidekiq",
      "image": "${var.ecr_backend_main_repository_url}",
      "cpu": ${var.ecs_main_service_cpu_limit},
      "memory": ${var.ecs_main_service_memory_limit},
      "essential": true
    }
  ]
  TASK_DEFINITION

  runtime_platform {
    operating_system_family = "LINUX"
    cpu_architecture        = "X86_64"
  }
}

