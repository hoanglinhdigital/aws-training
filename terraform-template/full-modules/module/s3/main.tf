terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}

data "aws_caller_identity" "current" {}
data "aws_region" "current" {}
locals {
    account_id = data.aws_caller_identity.current.account_id
    # Reference: https://docs.aws.amazon.com/elasticloadbalancing/latest/application/load-balancer-access-logs.html#access-logging-bucket-permissions
    elb_account_id = {
      "us-east-1" : "127311923021", #Virginia
      "ap-northeast-1": "582318560864" #Tokyo
    }
    current_region = data.aws_region.current.name
}

# tokyo-test-dev-cloudtrail-log-bucket
resource "aws_s3_bucket" "s3_cloud_trail_log_bucket" {
  bucket = join("-", [var.region_code, var.system_code, var.env_code, "cloudtrail-log-bucket-01"])
  acl    = "private"
  lifecycle_rule {
    id      = "log"
    enabled = true
    prefix = "log/"
    tags = {
      rule      = "log"
      autoclean = "true"
    }
    transition {
      days          = 30
      storage_class = "ONEZONE_IA" # or "ONEZONE_IA"
    }
    transition {
      days          = 90
      storage_class = "GLACIER"
    }
    expiration {
      days = 270
    }
  }
}


# tokyo-test-dev-cloudfront-log-bucket
resource "aws_s3_bucket" "s3_cloudfront_log_bucket" {
  bucket = join("-", [var.region_code, var.system_code, var.env_code, "cloudfront-log-bucket-01"])
  acl    = "private"
  lifecycle_rule {
    id      = "log"
    enabled = true
    prefix = "log/"
    tags = {
      rule      = "log"
      autoclean = "true"
    }
    transition {
      days          = 30
      storage_class = "ONEZONE_IA" # or "ONEZONE_IA"
    }
    transition {
      days          = 90
      storage_class = "GLACIER"
    }
    expiration {
      days = 270
    }
  }
}
# tokyo-test-dev-cloudwatch-log-archive-bucket
resource "aws_s3_bucket" "s3_cloudwatch_log_archive_bucket" {
  bucket = join("-", [var.region_code, var.system_code, var.env_code, "cloudwatch-log-archive-bucket-01"])
  acl    = "private"
  lifecycle_rule {
    id      = "log"
    enabled = true
    prefix = "log/"
    tags = {
      rule      = "log"
      autoclean = "true"
    }
    transition {
      days          = 30
      storage_class = "ONEZONE_IA" # or "ONEZONE_IA"
    }
    transition {
      days          = 90
      storage_class = "GLACIER"
    }
    expiration {
      days = 270
    }
  }
}
# tokyo-test-dev-website-bucket	
resource "aws_s3_bucket" "s3_website_bucket" {
  bucket = join("-", [var.region_code, var.system_code, var.env_code, "website-bucket-01"])
  acl    = "private"
  versioning {
    enabled = false
  }
}
resource "aws_s3_bucket_website_configuration" "s3_website_bucket_configuration" {
  bucket = aws_s3_bucket.s3_website_bucket.bucket

  index_document {
    suffix = "index.html"
  }
  routing_rule {
    redirect {
      protocol = "https"
    }
  }
}
# ALB Log bucket
resource "aws_s3_bucket" "s3_alb_log_bucket" {
  bucket = join("-", [var.region_code, var.system_code, var.env_code, "alb-log-bucket-01"])
  acl    = "private"
  versioning {
    enabled = false
  }
  lifecycle_rule {
    enabled = true
    noncurrent_version_transition {
      days          = 60
      storage_class = "STANDARD_IA"
    }
    noncurrent_version_expiration {
      days = 120
    }
  }
}
#Policy allow alb to write log
resource "aws_s3_bucket_policy" "s3_alb_log_bucket_custom_policy" {
  bucket = aws_s3_bucket.s3_alb_log_bucket.id
  policy = data.aws_iam_policy_document.s3_alb_log_bucket_custom_policy_document.json
}
data "aws_iam_policy_document" "s3_alb_log_bucket_custom_policy_document" {
  statement {
    principals {
      type        = "AWS"
      identifiers = ["${lookup(local.elb_account_id, local.current_region, "ap-northeast-1")}"]
    }
    actions = [
      "s3:GetObject",
      "s3:ListBucket",
      "s3:PutObject"
    ]
    resources = [
      aws_s3_bucket.s3_alb_log_bucket.arn,
      "${aws_s3_bucket.s3_alb_log_bucket.arn}/*",
    ]
  }
  statement {
    principals {
      type        = "Service"
      identifiers = ["logdelivery.elb.amazonaws.com"]
    }
    actions = [
      "s3:GetBucketAcl"
    ]
    resources = [
      aws_s3_bucket.s3_alb_log_bucket.arn,
      "${aws_s3_bucket.s3_alb_log_bucket.arn}/*",
    ]
  }
}