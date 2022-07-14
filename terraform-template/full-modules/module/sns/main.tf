terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}

resource "aws_sns_topic" "level_a_alarm_topic_01" {
  name = join("-", [var.region_code, var.system_code, var.env_code, "level-a-alarm-topic-01"])
}

resource "aws_sns_topic" "level_b_alarm_topic_01" {
  name = join("-", [var.region_code, var.system_code, var.env_code, "level-b-alarm-topic-01"])
}

resource "aws_sns_topic" "level_c_alarm_topic_01" {
  name = join("-", [var.region_code, var.system_code, var.env_code, "level-c-alarm-topic-01"])
}

resource "aws_sns_topic" "cloud_trail_alarm_topic_01" {
  name = join("-", [var.region_code, var.system_code, var.env_code, "cloud-trail-alarm-topic-01"])
}						

