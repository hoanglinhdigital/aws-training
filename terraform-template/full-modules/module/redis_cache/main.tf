terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}

resource "aws_elasticache_subnet_group" "elasticache_subnet_group" {
  name       = join("-", [var.system_code, var.env_code, "elasticache-subnet-group"])
  description = "Subnet group for elasticache"
  subnet_ids = var.elasticache_subnet_ids

  tags = {
    Name = join("-", [var.system_code, var.env_code, "elasticache-subnet-group"])
  }
}
resource "aws_elasticache_cluster" "elasticache_cluster" {
  cluster_id           = join("-", [var.system_code, var.env_code, "elasticache-cluster-01"])
  engine               = var.elasticache_engine
  engine_version       = var.elasticache_engine_version
  node_type            = var.elasticache_node_type
  num_cache_nodes      = var.elasticache_number_cache_node
  parameter_group_name =var.elasticache_parameter_group
  az_mode = var.elasticache_az_mode
  security_group_ids = var.elasticache_security_group_ids
  subnet_group_name = aws_elasticache_subnet_group.elasticache_subnet_group.name
  tags = {
    Name = join("-", [var.system_code, var.env_code, "elasticache-cluster-01"])
  }
  depends_on = [
    aws_elasticache_subnet_group.elasticache_subnet_group
  ]
}
