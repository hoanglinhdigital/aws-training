terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.27"
    }
  }
  required_version = ">= 0.14.9"
}
# Configure the AWS Provider
provider "aws" {
  region = "us-east-1"
  profile = "codestar-dev-env"
  shared_credentials_file = "%USERPROFILE%\\.aws\\credentials"
}


module "vpc_security_group" {
  source        = "../../module/vpc_security_group"
  
  system_code   = var.system_code
  env_code      = var.env_code
  vpc_cidr      = var.vpc_cidr
  vpc_first_availability_zone = var.vpc_first_availability_zone
  vpc_second_availability_zone = var.vpc_second_availability_zone
  public_subnet_cidrs = var.public_subnet_cidrs
  private_subnet_cidrs = var.private_subnet_cidrs
  trusted_subnet_cidrs = var.trusted_subnet_cidrs
  management_subnet_cidrs = var.management_subnet_cidrs
  management_sg_allowed_cidrs = var.management_sg_allowed_cidrs
}

module "database" {
  source        = "../../module/database"
  system_code   = var.system_code
  env_code      = var.env_code

  db_subnet_ids = [module.vpc_security_group.trusted_subnet_1_id, module.vpc_security_group.trusted_subnet_2_id]
  db_security_group_ids = [module.vpc_security_group.db_sg_id]
  db_instance_class = var.db_instance_class
  db_engine = var.db_engine
  db_engine_version = var.db_engine_version
  db_database_name = var.db_database_name
  db_master_username = var.db_master_username
  db_master_password = var.db_master_password
  db_backup_windows = var.db_backup_windows
  db_number_of_reader_node = var.db_number_of_reader_node
  db_skip_final_snapshot = var.db_skip_final_snapshot
  depends_on = [
    module.vpc_security_group
  ]
}

module "iam_role" {
  source        = "../../module/iam"
  system_code   = var.system_code
  env_code      = var.env_code
  region_code = var.region_code
}

# module "ec2-instance" {
#   source        = "../../module/ec2"
#   system_code   = var.system_code
#   env_code      = var.env_code
#   region_code = var.region_code
#   ec2_bastion_ami_id = var.ec2_bastion_ami_id
#   ec2_bastion_keypair = var.ec2_bastion_keypair
#   ec2_bastion_instance_type = var.ec2_bastion_instance_type
#   ec2_bastion_security_group_ids = [module.vpc_security_group.management_sg_id]
#   ec2_bastion_subnet_id = module.vpc_security_group.management_subnet_1_id
#   ec2_bastion_volume_size = var.ec2_bastion_volume_size
#   ec2_bastion_role_name = module.iam_role.ec2_bastion_role_name
#   depends_on = [
#     module.vpc_security_group,
#     module.iam_role
#   ]
# }

# module "s3-bucket" {
#   source        = "../../module/s3"
#   system_code   = var.system_code
#   env_code      = var.env_code
#   region_code = var.region_code
#   depends_on = [
#     module.vpc_security_group,
#     module.iam_role
#   ]
# }

# module "sns" {
#   source        = "../../module/sns"
#   system_code   = var.system_code
#   env_code      = var.env_code
#   region_code = var.region_code
#   depends_on = [
#     module.vpc_security_group,
#     module.iam_role
#   ]
# }

# module "redis_cache" {
#   source        = "../../module/redis_cache"
#   system_code   = var.system_code
#   env_code      = var.env_code
#   elasticache_subnet_ids = [module.vpc_security_group.trusted_subnet_1_id, module.vpc_security_group.trusted_subnet_2_id]
#   elasticache_security_group_ids = [module.vpc_security_group.db_sg_id]
#   elasticache_engine = var.elasticache_engine
#   elasticache_node_type = var.elasticache_node_type
#   elasticache_number_cache_node = var.elasticache_number_cache_node
#   elasticache_engine_version = var.elasticache_engine_version
#   elasticache_parameter_group = var.elasticache_parameter_group
#   elasticache_az_mode = var.elasticache_az_mode
#   depends_on = [
#     module.vpc_security_group,
#     module.iam_role
#   ]
# }

module "ecr" {
  source = "../../module/ecr"
  system_code   = var.system_code
  env_code      = var.env_code
}

module "alb" {
  source = "../../module/alb"
  system_code   = var.system_code
  env_code      = var.env_code
  alb_log_bucket_name = module.s3-bucket.s3_alb_log_bucket_name
  alb_vpc_id = module.vpc_security_group.vpc_id
  alb_subnet_ids = [module.vpc_security_group.public_subnet_1_id, module.vpc_security_group.public_subnet_2_id]
  alb_security_group_ids = [module.vpc_security_group.public_sg_id]
  alb_ssl_certificate_arn = var.alb_ssl_certificate_arn
  depends_on = [
    module.vpc_security_group,
    module.iam_role,
    module.database,
    module.s3-bucket
  ]
}

module "ecs" {
  source = "../../module/ecs"
  system_code   = var.system_code
  env_code      = var.env_code
  ecs_task_role_arn =  module.iam_role.ecs_task_role_arn
  ecs_task_execution_role_arn = module.iam_role.ecs_task_execution_role_arn
  ecs_autoscaling_role_arn = module.iam_role.ecs_auto_scaling_role_arn
  ecs_log_group_name = var.ecs_log_group_name
  ecs_service_subnets = [module.vpc_security_group.private_subnet_1_id, module.vpc_security_group.private_subnet_2_id]  #Get from VPC Subnet stack
  ecs_service_security_groups = [module.vpc_security_group.app_sg_id] #Get from VPC Subnet stack
  ecs_main_service_target_group_arn = module.alb.alb_backend_service_targetgroup_arn #Get from "alb" module

  ecr_backend_main_repository_url = module.ecr.ecr_backend_main_repository_url
  ecs_main_service_cpu_limit = var.ecs_main_service_cpu_limit
  ecs_main_service_memory_limit = var.ecs_main_service_memory_limit

  ecr_backend_sidekiq_repository_url = module.ecr.ecr_backend_sidekiq_repository_url
  ecs_sidekiq_service_cpu_limit = var.ecs_sidekiq_service_cpu_limit
  ecs_sidekiq_service_memory_limit = var.ecs_sidekiq_service_memory_limit

  depends_on = [
    module.vpc_security_group,
    module.iam_role,
    module.database,
    module.alb
  ]
}

# #backup
# module "backup" {
#   source = "../../module/backup"
#   system_code   = var.system_code
#   env_code      = var.env_code
#   ec2_target_backup_instances_arn = [module.ec2-instance.ec2_bastion_linux_arn] #Get from EC2 stack 
#   ec2_daily_backup_retention = var.ec2_daily_backup_retention
#   ec2_weekly_backup_retention = var.ec2_weekly_backup_retention
#   rds_target_backup_databases_arn = [module.database.db_postgres_db_cluster_01_arn] #Get from Database stack 
#   rds_weekly_backup_retention = var.rds_weekly_backup_retention
#   ec2_weekly_backup_schedule = var.ec2_weekly_backup_schedule
#   ec2_daily_backup_schedule = var.ec2_daily_backup_schedule
#   rds_weekly_backup_schedule = var.ec2_daily_backup_schedule
#   depends_on = [
#     module.vpc_security_group,
#     module.iam_role,
#     module.database,
#     module.alb,
#     module.ec2-instance,
#     module.ecs,

#   ]
# }