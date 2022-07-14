terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}
#===Backup Vaults=====
#EC2 backup vault - Daily
resource "aws_backup_vault" "backup_vault_ec2_daily" {
  name        = join("-", [ var.system_code, var.env_code, "ec2-daily-backup-vault"])
}

#EC2 backup plan - Weekly
resource "aws_backup_vault" "backup_vault_ec2_weekly" {
  name        = join("-", [ var.system_code, var.env_code, "ec2-weekly-backup-vault"])
}

#RDS Backup vault - Weekly
resource "aws_backup_vault" "backup_vault_rds_weekly" {
  name        = join("-", [ var.system_code, var.env_code, "rds-weekly-backup-vault"])
}

#===Backup Plan=====
#EC2 Daily backup plan
resource "aws_backup_plan" "backup_plan_ec2_daily" {
  name = join("-", [ var.system_code, var.env_code, "ec2-daily-backup-plan"])

  rule {
    rule_name         = "ec2-daily-backup-plan-rule-01"
    target_vault_name = aws_backup_vault.backup_vault_ec2_daily.name
    schedule          = var.ec2_daily_backup_schedule
    lifecycle {
      delete_after = var.ec2_daily_backup_retention
    }
  }
  tags = {
    Environment = var.env_code
    System = var.system_code
  }
}
#EC2 Weekly backup plan
resource "aws_backup_plan" "backup_plan_ec2_weekly" {
  name = join("-", [ var.system_code, var.env_code, "ec2-weekly-backup-plan"])

  rule {
    rule_name         = "ec2-weekly-backup-plan-rule-01"
    target_vault_name = aws_backup_vault.backup_vault_ec2_weekly.name
    schedule          = var.ec2_weekly_backup_schedule
    lifecycle {
      delete_after = var.ec2_weekly_backup_retention
    }
  }
  tags = {
    Environment = var.env_code
    System = var.system_code
  }
}

#RDS Weekly backup plan
resource "aws_backup_plan" "backup_plan_rds_weekly" {
  name = join("-", [ var.system_code, var.env_code, "rds-weekly-backup-plan"])

  rule {
    rule_name         = "rds-weekly-backup-plan-rule-01"
    target_vault_name = aws_backup_vault.backup_vault_ec2_weekly.name
    schedule          = var.rds_weekly_backup_schedule
    lifecycle {
      delete_after = var.rds_weekly_backup_retention
    }
  }
  tags = {
    Environment = var.env_code
    System = var.system_code
  }
}

#Backup selection
resource "aws_iam_role" "aws_backup_role_01" {
  name               = join("-", [ var.system_code, var.env_code, "backup-role-01"])
  assume_role_policy = <<POLICY
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Action": ["sts:AssumeRole"],
      "Effect": "allow",
      "Principal": {
        "Service": ["backup.amazonaws.com"]
      }
    }
  ]
}
POLICY
}

resource "aws_iam_role_policy_attachment" "aws_backup_role_01_policy_01" {
  policy_arn = "arn:aws:iam::aws:policy/service-role/AWSBackupServiceRolePolicyForBackup"
  role       = aws_iam_role.aws_backup_role_01.name
}

resource "aws_backup_selection" "backup_ec2_daily_selection" {

  iam_role_arn = aws_iam_role.aws_backup_role_01.arn
  name = join("-", [ var.system_code, var.env_code, "ec2-backup-daily-selection"])
  plan_id = aws_backup_plan.backup_plan_ec2_daily.id
  resources = var.ec2_target_backup_instances_arn
}

resource "aws_backup_selection" "backup_ec2_weekly_selection" {

  iam_role_arn = aws_iam_role.aws_backup_role_01.arn
  name = join("-", [ var.system_code, var.env_code, "ec2-backup-weekly-selection"])
  plan_id = aws_backup_plan.backup_plan_ec2_weekly.id
  resources = var.ec2_target_backup_instances_arn
}

resource "aws_backup_selection" "backup_rds_weekly_selection" {
  iam_role_arn = aws_iam_role.aws_backup_role_01.arn
  name = join("-", [ var.system_code, var.env_code, "rds-backup-weekly-selection"])
  plan_id = aws_backup_plan.backup_plan_rds_weekly.id
  resources = var.rds_target_backup_databases_arn
}

