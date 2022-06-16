#   ami           = "ami-0cff7528ff583bf9a"
#   instance_type = "t2.micro"
#   name          = "Day6-Demo-Instance"
#   keyname = "codestar-keypair"
#   subnet_id = "subnet-0c71523976a14a7eb"
#   vpc_id = "vpc-0bf416dc63841708b"
variable "ami" {
  default = "ami-0cff7528ff583bf9a"
  type = string
  description = "AMI id of EC2"
}

variable "instance_type" {
  default = "t2.micro"
  type = string
  description = "Instance type of Ec2"
}

variable "name" {
  default = "Day6-Demo-Instance"
  type = string
  description = "Name type of Ec2"
}

variable "keyname" {
  default = "codestar-keypair"
  type = string
  description = "Keypair to login to Ec2"
}

variable "subnet_id" {
  default = "subnet-0c71523976a14a7eb"
  type = string
  description = "Subnet to deploy Ec2"
}

variable "vpc_id" {
  default = "vpc-0bf416dc63841708b"
  type = string
  description = "VPC to create security group"
}