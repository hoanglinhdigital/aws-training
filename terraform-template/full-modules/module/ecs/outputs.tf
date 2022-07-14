output "ecs_cluster_id" {
  value = aws_ecs_cluster.ecs_main_cluster_01.id
}
output "ecs_cluster_arn" {
    value = aws_ecs_cluster.ecs_main_cluster_01.arn
}