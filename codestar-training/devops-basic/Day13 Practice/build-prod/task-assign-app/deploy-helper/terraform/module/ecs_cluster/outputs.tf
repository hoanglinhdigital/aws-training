output "id" {
  description = "ID of the ECS Cluster"
  value       = aws_ecs_cluster.current_cluster.id
}

output "arn" {
  description = "ARN of the ECS Cluster"
  value       = aws_ecs_cluster.current_cluster.arn
}

output "name" {
  description = "The name of the ECS cluster"
  value       = var.name
}

output "taskExecRoleArn" {
  value       = aws_iam_role.ecs_task_exec_role.arn
  description = "ARN of task exec role"
}
