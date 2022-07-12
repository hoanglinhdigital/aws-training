variable "container_insights" {
  description = "Controls if ECS Cluster has container insights enabled"
  default     = false
  type        = bool
}

variable "default_capacity_provider_strategy" {
  description = "The capacity provider strategy to use by default for the cluster. Can be one or more."
  default = [{
    capacity_provider = "FARGATE"
    weight            = 1
    base              = 1
  }]

  type = list(map(any))
}

variable "create_ecs" {
  default = true
  type    = bool
}


variable "name" {
  default = "demo"
  type    = string
}

variable "tags" {
  description = "A map of tags to add to ECS Cluster"
  type        = map(string)
  default     = {}
}

variable "environment" {
  description = "Environment nname"
  default     = "test"
  type        = string
}


variable "capacity_providers" {
  description = "Declare capacity providers allowed in this cluster"
  default     = ["FARGATE", "FARGATE_SPOT"]
  type        = list(string)
}
