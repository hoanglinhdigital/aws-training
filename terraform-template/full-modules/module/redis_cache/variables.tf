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
#Elasticache variable
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
