terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}

resource "aws_db_subnet_group" "postgres_db_subnet_group" {
  name       = join("-", [var.system_code, var.env_code, "db-subnet-group"])
  subnet_ids = var.db_subnet_ids

  tags = {
    Name = join("-", [var.system_code, var.env_code, "db-subnet-group"])
  }
}

resource "aws_rds_cluster" "postgres_db_cluster_01" {
  cluster_identifier      = join("-", [var.system_code, var.env_code, "aurora-postgres-cluster-01"])
  engine                  = var.db_engine
  engine_version = var.db_engine_version
  db_subnet_group_name = aws_db_subnet_group.postgres_db_subnet_group.name
  database_name           = var.db_database_name
  master_username         = var.db_master_username
  master_password         = var.db_master_password
  backup_retention_period = 30
  preferred_backup_window = var.db_backup_windows
  skip_final_snapshot = var.db_skip_final_snapshot
  vpc_security_group_ids = var.db_security_group_ids
  allow_major_version_upgrade = false
}

resource "aws_rds_cluster_instance" "postgres_db_writer_node" {
  cluster_identifier = aws_rds_cluster.postgres_db_cluster_01.id
  identifier = join("-", [var.system_code, var.env_code, "aurora-postgres-cluster-01-writer-01"])
  instance_class     = var.db_instance_class
  engine             = aws_rds_cluster.postgres_db_cluster_01.engine
  engine_version     = aws_rds_cluster.postgres_db_cluster_01.engine_version
  promotion_tier = 0
  auto_minor_version_upgrade = false
}

resource "aws_rds_cluster_instance" "postgres_db_reader_node" {
  count = var.db_number_of_reader_node
  cluster_identifier = aws_rds_cluster.postgres_db_cluster_01.id
  identifier = join("-", [var.system_code, var.env_code, "aurora-postgres-cluster-01-reader", count.index])
  instance_class     = var.db_instance_class
  engine             = aws_rds_cluster.postgres_db_cluster_01.engine
  engine_version     = aws_rds_cluster.postgres_db_cluster_01.engine_version
  promotion_tier = 1
  auto_minor_version_upgrade = false
}

resource "aws_secretsmanager_secret" "db_master_username" {
  name = join("-", [var.system_code, var.env_code, "aurora-postgres-master-username-03"])
  description = "Secret to store Postgres master username"
}
resource "aws_secretsmanager_secret_version" "db_master_username_version" {
  secret_id     = aws_secretsmanager_secret.db_master_username.id
  secret_string = var.db_master_username
}

resource "aws_secretsmanager_secret" "db_master_password" {
  name = join("-", [var.system_code, var.env_code, "aurora-postgres-master-password-03"])
  description = "Secret to store Postgres master password"
}

resource "aws_secretsmanager_secret_version" "db_master_password_version" {
  secret_id     = aws_secretsmanager_secret.db_master_password.id
  secret_string = var.db_master_password
}