terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}

resource "aws_ecr_repository" "ecr_backend_main_repository" {
  name                 = join("-", [var.system_code, var.env_code, "backend-main-repository"])
  image_tag_mutability = "MUTABLE"
  image_scanning_configuration {
    scan_on_push = true
  }
}
resource "aws_ecr_lifecycle_policy" "ecr_backend_main_repository_lifecycle_policy" {
  repository = aws_ecr_repository.ecr_backend_main_repository.name
  policy = <<EOF
  {
      "rules": [
          {
              "rulePriority": 1,
              "description": "Only keep 30 latest version of images",
              "selection": {
                  "tagStatus": "untagged",
                  "countType": "imageCountMoreThan",
                  "countNumber": 30
              },
              "action": {
                  "type": "expire"
              }
          }
      ]
  }
  EOF
}

resource "aws_ecr_repository" "ecr_backend_sidekiq_repository" {
  name                 = join("-", [ var.system_code, var.env_code, "backend-sidekiq-repository"])
  image_tag_mutability = "MUTABLE"
  image_scanning_configuration {
    scan_on_push = true
  }
}
resource "aws_ecr_lifecycle_policy" "ecr_backend_sidekiq_repository_lifecycle_policy" {
  repository = aws_ecr_repository.ecr_backend_sidekiq_repository.name
  policy = <<EOF
  {
      "rules": [
          {
              "rulePriority": 1,
              "description": "Only keep 30 latest version of images",
              "selection": {
                  "tagStatus": "untagged",
                  "countType": "imageCountMoreThan",
                  "countNumber": 30
              },
              "action": {
                  "type": "expire"
              }
          }
      ]
  }
  EOF
}