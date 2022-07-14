#Comon variables
system_code = "mysystem"
env_code = "dev"
region_code = "virginia"

#VPC and Security Group variables
vpc_cidr = "172.6.0.0/16"
public_subnet_cidrs = ["172.6.1.0/24", "172.6.2.0/24" ]
private_subnet_cidrs = ["172.6.3.0/24", "172.6.4.0/24"]
trusted_subnet_cidrs = ["172.6.5.0/24", "172.6.6.0/24"]
management_subnet_cidrs = ["172.6.7.0/24", "172.6.8.0/24"]
vpc_first_availability_zone = "ap-northeast-1a"
vpc_second_availability_zone = "ap-northeast-1c"
management_sg_allowed_cidrs = [
    "14.161.50.178/32" #Shift Asia
    ]

#Database variables
db_subnet_ids = [] #get from VPC Output
db_security_group_id = "" #get from VPC Output
db_instance_class = "db.m5.large"
db_engine = "aurora-postgresql"
db_engine_version = "12.8"
db_database_name = "postgresdb"
db_master_username = "postgres_admin"
db_master_password = "M(#gJQFF^GM6'Ev)"
db_backup_windows = "20:00-22:00"
db_number_of_reader_node = 0
db_skip_final_snapshot = true

#IAM variables
#N/A

#EC2 variables
ec2_bastion_ami_id = "ami-0b7546e839d7ace12" #Amz Linux 2
ec2_bastion_keypair = "mysystem-dev"
ec2_bastion_instance_type = "t3.micro"
ec2_bastion_security_group_ids = []  #get from VPC Output
ec2_bastion_subnet_id = "" #get from VPC Output
ec2_bastion_volume_size = 10
ec2_bastion_role_name = "" #get from IAM Output
#S3 Variables
#N/A

#SNS Variables
#N/A

#Elasticache variables
elasticache_subnet_ids = []  #get from VPC Output
elasticache_security_group_ids = []  #get from VPC Output
elasticache_engine = "redis"
elasticache_node_type = "cache.t3.micro"
elasticache_number_cache_node = 1
elasticache_engine_version ="6.x"
elasticache_parameter_group = "default.redis6.x"
elasticache_az_mode = "single-az"

#ALB variables
alb_vpc_id = "" #Input from VPC output
alb_subnet_ids = [] #Input from VPC output
alb_security_group_ids = [] #Input from VPC output
alb_log_bucket_name = "" #Input from S3 output
alb_ssl_certificate_arn = ""

#ECR variables
#N/A

#ECS variables
ecs_task_role_arn =  ""
ecs_task_execution_role_arn = ""
ecs_autoscaling_role_arn = ""
ecs_log_group_name = ""
ecs_service_subnets = []  #Get from VPC Subnet stack
ecs_service_security_groups = [] #Get from VPC Subnet stack
ecs_main_service_target_group_arn = "" #Get from "alb" module

ecr_backend_main_repository_url = "" #Get from "ecr" module
ecs_main_service_cpu_limit = 1024
ecs_main_service_memory_limit = 2048

ecr_backend_sidekiq_repository_url = ""#Get from "ecr" module
ecs_sidekiq_service_cpu_limit = 1024
ecs_sidekiq_service_memory_limit = 2048

#Backup variables

ec2_target_backup_instances_arn = [] #Get from EC2 stack 
ec2_daily_backup_retention = 30
ec2_weekly_backup_retention = 90
rds_target_backup_databases_arn = [] #Get from Database stack 
rds_weekly_backup_retention = 90
# Schedule for backup
# https://docs.aws.amazon.com/AmazonCloudWatch/latest/events/ScheduledEvents.html
ec2_daily_backup_schedule = "cron(0 18 * * ? *)" #18:00 UTC Every Day
ec2_weekly_backup_schedule = "cron(0 22 ? * SAT *)" #22:00 UTC Every Saturday
rds_weekly_backup_schedule = "cron(0 22 ? * SAT *)" #22:00 UTC Every Saturday

#Cloudfront variables
s3_website_bucket_name = "" #Get from S3 output
s3_website_bucket_arn = "" #Get from S3 output
s3_website_bucket_domain_name = "" #Get from S3 output
s3_website_bucket_regional_domain_name = "" #Get from S3 output
s3_website_bucket_website_endpoint = "" #Get from S3 output
alb_domain_name = "" #Get from alb output
s3_cloudfront_log_bucket_name = "" #Get from S3 output
s3_cloudfront_log_bucket_domain_name = ""#Get from S3 output

#Monitoring variables


