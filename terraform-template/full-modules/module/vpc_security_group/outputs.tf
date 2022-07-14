#Output
#VPC and subnet
output "vpc_id" {
  description = "ID of main vpc"
  value = aws_vpc.main-vpc.id
}
output "public_subnet_1_id" {
  description = "ID of Public Subnet 1"
  value = aws_subnet.public-subnet-1.id
}
output "public_subnet_2_id" {
  description = "ID of Public Subnet 2"
  value = aws_subnet.public-subnet-2.id
}

output "private_subnet_1_id" {
  description = "ID of private Subnet 1"
  value = aws_subnet.private-subnet-1.id
}
output "private_subnet_2_id" {
  description = "ID of private Subnet 2"
  value = aws_subnet.private-subnet-2.id
}

output "trusted_subnet_1_id" {
  description = "ID of trusted Subnet 1"
  value = aws_subnet.trusted-subnet-1.id
}
output "trusted_subnet_2_id" {
  description = "ID of trusted Subnet 2"
  value = aws_subnet.trusted-subnet-2.id
}

output "management_subnet_1_id" {
  description = "ID of management Subnet 1"
  value = aws_subnet.management-subnet-1.id
}
output "management_subnet_2_id" {
  description = "ID of management Subnet 2"
  value = aws_subnet.management-subnet-2.id
}

#Security group
output "public_sg_id" {
  description = "ID of public Security group"
  value = aws_security_group.public-sg-01.id
}
output "app_sg_id" {
  description = "ID of app Security group"
  value = aws_security_group.app-sg-01.id
}
output "db_sg_id" {
  description = "ID of db Security group"
  value = aws_security_group.db-sg-01.id
}
output "management_sg_id" {
  description = "ID of management Security group"
  value = aws_security_group.management-sg-01.id
}