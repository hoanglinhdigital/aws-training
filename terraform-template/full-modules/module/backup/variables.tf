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