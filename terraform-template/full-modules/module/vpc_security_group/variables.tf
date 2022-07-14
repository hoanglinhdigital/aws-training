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