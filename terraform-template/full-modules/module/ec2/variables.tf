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
  description = "Code for system region, example: tokyo"
  default = "tokyo"
}
#EC2 Variables
variable "ec2_bastion_ami_id" {
  type = string
  description = "AMI id of bastion host."
}
variable "ec2_bastion_keypair" {
  type = string
  description = "Keypair to create bastion host"
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