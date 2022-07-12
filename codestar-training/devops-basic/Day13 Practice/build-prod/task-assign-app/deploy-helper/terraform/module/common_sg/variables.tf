variable "vpc_id" {
  description = "Specific VPC id for these SGs. If it's not present, then use VPC default"
  default     = ""
  type        = string
}


locals {
  common_tags = {
    TFModule = "common_sg"
  }
}
