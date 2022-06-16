terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.27"
    }
  }
  required_version = ">= 0.14.9"
}
provider "aws" {
  profile = "default"
  region  = "us-east-1"
}
module "ec2_and_sg" {
  source        = "../../module/ec2_sg"
  ami           = "ami-0cff7528ff583bf9a"
  instance_type = "t2.micro"
  name          = "Day6-Demo-Instance"
  keyname       = "codestar-keypair"
  subnet_id     = "subnet-0c71523976a14a7eb"
  vpc_id        = "vpc-0bf416dc63841708b"
}
