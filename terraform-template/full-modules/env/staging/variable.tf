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
variable "region_code" {
  type = string
  description = "Code for system region, example: virginia"
  default = "virginia"
}
#Variable for VPC and Security group
variable "vpc_cidr" {
  description = "cidr block of VPC"
  type = string 
  #default = "172.3.0.0/16"
}
variable "vpc_first_availability_zone" {
  description = "Name of first AZ"
  type = string
}

variable "vpc_second_availability_zone" {
  description = "Name of second AZ"
  type = string
}
variable "public_subnet_cidrs" {
  description = "list of cidrs for 2 public subnets"
  type = list 
}

variable "private_subnet_cidrs" {
  description = "list of cidrs for 2 private subnets"
  type = list 
}

variable "trusted_subnet_cidrs" {
  description = "list of cidrs for 2 trusted subnets"
  type = list 
}

variable "management_subnet_cidrs" {
  description = "list of cidrs for 2 management subnets"
  type = list 
}

variable "management_sg_allowed_cidrs" {
  description = "List of cidr that allowed to connect SSH to management security group"
  type = list 
}
#Database variables
variable "db_subnet_ids" {
  description = "Subnets ids for database"
  type =  list
}
variable "db_security_group_id" {
  description = "Security group id for database"
  type =  string
}
variable "db_instance_class" {
  description = "Instance type for database"
  type =  string
}
variable "db_engine" {
  description = "Engine for database"
  type =  string
  default = "aurora-postgresql"
}
variable "db_engine_version" {
  description = "DB Engine version"
  type =  string
  default = "12.6"
}
variable "db_database_name" {
  type = string
  description = "Database name"
  default = "postgres-db"
}
variable "db_master_username" {
  description = "Master username for DB"
  type =  string
}
variable "db_master_password" {
  description = "Master password for DB"
  type =  string
}
variable "db_backup_windows" {
  type = string
  description = "Backup windows for DB in UTC time. Example: 20:00-22:00"
  default = "20:00-22:00"
}
variable "db_number_of_reader_node" {
  description = "Number of reader node for database"
  type =  number
  default = 1
}
variable "db_skip_final_snapshot" {
  description = "Skip final snapshot when delete or not"
  type = bool
  default = false
}
#IAM variables
#N/A

#EC2 Variables
variable "ec2_bastion_ami_id" {
  type = string
  description = "AMI id of bastion host."
}
variable "ec2_bastion_keypair" {
  type = string
  description = "Keypair to create bastion host"
  default = "riccardo-test"
}
variable "ec2_bastion_instance_type" {
  type = string
  description = "Instance  type of bastion host."
}
variable "ec2_bastion_security_group_ids" {
  type = list
  description = "Security group ids for bastion host."
}
variable "ec2_bastion_subnet_id" {
  type = string
  description = "Subnet id for bastion host."
}
variable "ec2_bastion_volume_size" {
  type = number
  description = "Volume size for bastion host."
  default = 10
}
variable "ec2_bastion_role_name" {
  type = string
  description = "Role name for bastion host."
}
#S3 Variables
#N/A

#SNS Variables
#N/A

#Elasticache variables
variable "elasticache_subnet_ids" {
  description = "Subnets ids for elasticache"
  type =  list
}
variable "elasticache_security_group_ids" {
  description = "Security group id for elasticache"
  type =  list
}
variable "elasticache_node_type" {
  description = "Node type for elasticache"
  type =  string
  default = "cache.t3.micro"
}
variable "elasticache_engine" {
  description = "Engine for elasticache"
  type =  string
  default = "redis"
}
variable "elasticache_engine_version" {
  description = "cache Engine version"
  type =  string
  default = "6.2"
}
variable "elasticache_parameter_group" {
  description = "Cache paremeter group"
  type =  string
  default = "default.redis6.x"
}
variable "elasticache_number_cache_node" {
  description = "Number of node for elasticache"
  type =  number
  default = 1
}
variable "elasticache_az_mode" {
  description = "AZ mode for elasticache"
  type =  string
  default = "single-az"
}
variable "elasticache_number_of_read_replica" {
  description = "Number read replica node"
  type =  number
  default = 0
}

#ALB variables
variable "alb_vpc_id" {
  description = "ID of VPC"
  type = string
}
variable "alb_subnet_ids" {
  description = "ID of subnets for Application load balancer. Should be public subnet 1,2"
  type = list
}
variable "alb_security_group_ids" {
  description = "ID of Security Group for Application load balancer. Should be public security group"
  type = list
}
variable "alb_log_bucket_name" {
  description = "Name of bucket to store log of ALB"
  type = string
}
variable "alb_ssl_certificate_arn" {
  description = "ARN for ACM certificate that use for HTTPS listener on ALB"
  type = string  
}
#ECR variables

#ECS variables
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
#Task definition - main
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
#task definition - sidekiq
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

#Backup variables
#List of ec2 need to backup
variable "ec2_target_backup_instances_arn" {
  description = "List of ec2 arn needed to backup"
  type = list 
}
#EC2 backup retention - Daily
variable "ec2_daily_backup_retention" {
  description = "Auto delete ec2 daily backup after n days"
  type = number
  default = 30
}
#EC2 backup Daily schedule
variable "ec2_daily_backup_schedule" {
  description = "CRON Expression for EC2 daily backup schedule"
  type = string
}
#EC2 backup retention - Weekly
variable "ec2_weekly_backup_retention" {
  description = "Auto delete ec2 weekly backup after n days"
  type = number
  default = 90
}
#EC2 backup Weekly schedule
variable "ec2_weekly_backup_schedule" {
  description = "CRON Expression for EC2 weekly backup schedule"
  type = string
}
#List of RDS need to backup
variable "rds_target_backup_databases_arn" {
  description = "List of databases arn needed to backup"
  type = list 
}

#RDS backup retention - Weekly
variable "rds_weekly_backup_retention" {
  description = "Auto delete rds weekly backup after n days"
  type = number
  default = 90
}
#EC2 backup Weekly schedule
variable "rds_weekly_backup_schedule" {
  description = "CRON Expression for RDS weekly backup schedule"
  type = string
}

#Cloudfront variables

variable "s3_website_bucket_name" {
  description = "Name for s3 website bucket"
  type = string 
}
variable "s3_website_bucket_domain_name" {
  description = "Bucket Domain Name for s3 website bucket"
  type = string 
}
variable "s3_website_bucket_regional_domain_name" {
  description = "Regional Domain name for s3 website bucket"
  type = string 
}
variable "s3_website_bucket_website_endpoint" {
  description = "Website endpoint for s3 website bucket"
  type = string 
}
variable "s3_website_bucket_arn" {
  description = "ARN for s3 website bucket"
  type = string 
}
variable "alb_domain_name" {
  type = string
  description = "Domain name for Application Load Balancer"
}
variable "s3_cloudfront_log_bucket_domain_name" {
  type = string
  description = "Domain name for log bucket name of cloudfront distribution"
}

#Monitoring variables

