locals {
  container_insights                 = var.container_insights ? "enabled" : "disabled"
  name                               = var.name
  capacity_providers                 = var.capacity_providers
  default_capacity_provider_strategy = var.default_capacity_provider_strategy
  common_tags                        = var.tags
  environment                        = var.environment
}

resource "aws_ecs_cluster" "current_cluster" {

  name = local.name
  setting {
    name  = "containerInsights"
    value = local.container_insights
  }

  tags = merge(
    local.common_tags,
    tomap({
      "Name"        = "${lower(var.environment)}-ecs",
      "Description" = "ECS"
    })
  )
}

resource "aws_ecs_cluster_capacity_providers" "cluster_providers" {
  cluster_name = aws_ecs_cluster.current_cluster.name

  capacity_providers = local.capacity_providers

  dynamic "default_capacity_provider_strategy" {
    for_each = local.default_capacity_provider_strategy
    iterator = strat

    content {
      capacity_provider = strat.value["capacity_provider"]
      weight            = lookup(strat.value, "weight", null)
      base              = lookup(strat.value, "base", null)
    }
  }

}
