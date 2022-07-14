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