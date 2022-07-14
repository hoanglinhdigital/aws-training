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