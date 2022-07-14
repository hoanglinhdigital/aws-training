#Variable section
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
#Database variable
variable "db_subnet_ids" {
  description = "Subnets ids for database"
  type =  list
}
variable "db_security_group_ids" {
  description = "Security group id for database"
  type =  list
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
}
variable "db_database_name" {
  type = string
  description = "Database name"
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
