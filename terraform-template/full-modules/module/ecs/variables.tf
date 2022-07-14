#Common variable
variable "system_code" {
  description = "code for system"
  type = string 
  default = "mysystem"
}
variable "env_code" {
  description = "code for environment"
  type = string 
  default = "dev"
}
variable "ecs_task_role_arn"{
  type = string
  description = "Role name for ECS Task"
}
variable "ecs_task_execution_role_arn"{
  type = string
  description = "Role name for ECS Task Execution"
}
variable "ecs_autoscaling_role_arn"{
  type = string
  description = "Role name for ECS autoscaling"
}
variable "ecs_log_group_name"{
  type = string
  description = "CloudWatch log - log group name for ECS"
  default = "ecs-cluster-log-01"
}
variable "ecs_service_subnets"{
  type = list
  description = "Subnets IDs for ECS Service"
}
variable "ecs_service_security_groups"{
  type = list
  description = "Security group IDs for ECS Service"
}
variable "ecs_main_service_target_group_arn"{
  type = string
  description = "ARN for main service target group"
}
#Main service
variable "ecr_backend_main_repository_url"{
  type = string
  description = "URL for main service ecr repository"
}
variable "ecs_main_service_cpu_limit"{
  type = number
  description = "CPU limit for main service task. Default: 1024 (1core)"
}
variable "ecs_main_service_memory_limit"{
  type = number
  description = "Memory limit for main service task. Default: 2048 (2GB)"
}
#Sidekiq service
variable "ecr_backend_sidekiq_repository_url"{
  type = string
  description = "URL for sidekiq service ecr repository"
}
variable "ecs_sidekiq_service_cpu_limit"{
  type = number
  description = "CPU limit for sidekiq service task. Default: 1024 (1core)"
}
variable "ecs_sidekiq_service_memory_limit"{
  type = number
  description = "Memory limit for sidekiq service task. Default: 2048 (2GB)"
}